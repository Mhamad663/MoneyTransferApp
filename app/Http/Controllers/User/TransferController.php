<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\{
    Transfer, TransferEvent, Beneficiary, Wallet,
    WalletTransaction, PaymentMethod, TransferService
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Notifications\TransferStatusUpdated;

class TransferController extends Controller
{
    /*Show Send form */
    public function create(Request $r)
    {
        $beneficiaries = Beneficiary::where('user_id', Auth::id())
            ->orderByDesc('is_favorite')
            ->orderBy('name')
            ->get();

        $wallet   = Auth::user()->wallet;
        $services = TransferService::where('active', true)->get();

        $selectedServiceCode = $r->service_code;
        $selectedPromoCode   = $r->promo_code;

        $selectedServiceId = null;
        if ($selectedServiceCode) {
            $svc = TransferService::where('code', $selectedServiceCode)->first();
            $selectedServiceId = $svc?->id;
        }

        return view('user.send', compact(
            'beneficiaries',
            'wallet',
            'services',
            'selectedServiceCode',
            'selectedPromoCode',
            'selectedServiceId'
        ));
    }

    /* WALLET → WALLET */
    public function storeWallet(Request $r)
    {
        $this->attachServiceId($r, 'wallet');

        $data = $r->validate([
            'beneficiary_id'     => 'nullable|exists:beneficiaries,id',
            'receiver_wallet_id' => 'nullable|string|max:32',
            'amount'             => 'required|numeric|min:1',
            'note'               => 'nullable|string|max:255',
            'service_id'         => 'nullable|exists:transfer_services,id',
        ]);

        $sender       = Auth::user();
        $senderWallet = $sender->wallet;
        $note         = $r->input('note');
        $receiverWalletId = $data['receiver_wallet_id'] ?? null;

        if (!$receiverWalletId && $data['beneficiary_id']) {
            $b = Beneficiary::where('id', $data['beneficiary_id'])
                ->where('user_id', Auth::id())
                ->first();

            if (!$b) {
                return back()->with('error', ' Invalid beneficiary selected.');
            }
            $receiverWalletId = $b->platform_wallet_id;
        }

        if (!$receiverWalletId) {
            return back()->with('error', 'Receiver wallet ID required.');
        }

        $receiverWallet = Wallet::where('wallet_id', $receiverWalletId)->first();
        if (!$receiverWallet) {
            return back()->with('error', ' Receiver wallet not found.');
        }

        if ($receiverWallet->id === $senderWallet->id) {
            return back()->with('error', ' You cannot send money to your own wallet.');
        }

        $service = TransferService::find($r->service_id);
        if (!$service) {
            return back()->with('error', ' Transfer service not found.');
        }

        $fee   = $service->calculateFee($data['amount']);
        $total = $data['amount'] + $fee;

        if ($senderWallet->balance < $total) {
            return back()->with('error', ' Insufficient wallet balance.');
        }

        $ref = (string) Str::uuid();
        $transfer = null;

        DB::transaction(function () use ($data, $sender, $senderWallet, $receiverWallet, $service, $fee, $total, $ref, $note, &$transfer) {
            $transfer = Transfer::create([
                'user_id'        => $sender->id,
                'beneficiary_id' => $data['beneficiary_id'] ?? null,
                'method'         => 'wallet',
                'service_id'     => $service->id,
                'source_wallet_id'      => $senderWallet->id,
                'destination_wallet_id' => $receiverWallet->id,
                'src_currency'   => 'USD',
                'dst_currency'   => 'USD',
                'amount_src'     => $total,
                'amount_dst'     => $data['amount'],
                'fee'            => $fee,
                'fx_rate'        => 1,
                'status'         => 'processing',
                'reference'      => $ref,
            ]);

            TransferEvent::create([
                'transfer_id' => $transfer->id,
                'event'       => 'created',
                'meta'        => $note,
            ]);
        });

        if ($transfer) {
            $sender->notify(new TransferStatusUpdated(
                $transfer,
                'Your wallet transfer of $' . number_format($transfer->amount_dst, 2) . ' is pending agent approval.'
            ));
        }

        return redirect()->route('user.send')->with('success', 'Transfer submitted successfully and awaiting approval.');
    }

    /*ADMIN: Approve a pending transfer */
    public function approveTransfer($id)
{
    $transfer = Transfer::find($id);

    if (!$transfer) {
        return back()->with('error', 'Transfer not found.');
    }

    if ($transfer->status !== 'processing') {
        return back()->with('error', ' Only pending transfers can be approved.');
    }

    DB::transaction(function () use ($transfer) {

        $senderWallet   = Wallet::find($transfer->source_wallet_id);
        $receiverWallet = Wallet::find($transfer->destination_wallet_id);

        if (!$senderWallet || !$receiverWallet) {
            throw new \Exception('Wallet not found');
        }

        $senderWallet->decrement('balance', $transfer->amount_src);
        $receiverWallet->increment('balance', $transfer->amount_dst);


        
        $transfer->update(['status' => 'completed']);

        TransferEvent::create([
            'transfer_id' => $transfer->id,
            'event'       => 'approved',
            'meta'        => 'Wallet transfer approved and completed.',
        ]);
    });

    // Notify user
    $transfer->user->notify(new TransferStatusUpdated(
        $transfer,
        'Your wallet transfer of $' . number_format($transfer->amount_dst, 2) . ' has been approved.'
    ));

    return back()->with('success', 'Transfer approved.');
}


    /* CARD */
    public function storeCard(Request $r)
    {
        $this->attachServiceId($r, 'card');

        $data = $r->validate([
            'beneficiary_id' => 'nullable|exists:beneficiaries,id',
            'receiver_name'  => 'nullable|string|max:120',
            'amount'         => 'required|numeric|min:1',
            'currency'       => 'required|string|size:3',
            'note'           => 'nullable|string|max:255',
            'service_id'     => 'nullable|exists:transfer_services,id',
        ]);

        $note = $r->input('note');
        $b = null;

        if (!empty($data['beneficiary_id'])) {
            $b = Beneficiary::where('id', $data['beneficiary_id'])
                ->where('user_id', Auth::id())
                ->first();
        }

        $receiverName = $b->name ?? $data['receiver_name'];
        if (!$receiverName) {
            return back()->with('error', ' Receiver name is required.');
        }

        $card = PaymentMethod::where('user_id', Auth::id())
            ->where('type', 'card')
            ->where('is_default', true)
            ->first();

        if (!$card) {
            return back()->with('error', ' Default card not found.');
        }

        $service = TransferService::find($r->service_id);
        if (!$service) {
            return back()->with('error', ' Transfer service not found.');
        }

        $fee   = $service->calculateFee($data['amount']);
        $total = $data['amount'] + $fee;

        if (strtoupper($card->currency) !== strtoupper($data['currency'])) {
            return back()->with('error', ' Currency mismatch for card.');
        }

        if ($card->balance < $total) {
            return back()->with('error', ' Insufficient balance in your card.');
        }

        $ref = (string) Str::uuid();

        DB::transaction(function () use ($data, $b, $receiverName, $card, $service, $fee, $total, $ref, $note) {
            $t = Transfer::create([
                'user_id'        => Auth::id(),
                'beneficiary_id' => $b->id ?? null,
                'method'         => 'card',
                'service_id'     => $service->id,
                'source'         => 'card:' . $card->last4,
                'destination'    => $receiverName,
                'src_currency'   => strtoupper($data['currency']),
                'dst_currency'   => strtoupper($data['currency']),
                'amount_src'     => $total,
                'amount_dst'     => $data['amount'],
                'fee'            => $fee,
                'fx_rate'        => 1,
                'status'         => 'processing',
                'reference'      => $ref,
            ]);

            TransferEvent::create([
                'transfer_id' => $t->id,
                'event'       => 'created',
                'meta'        => $note,
            ]);
        });

        return redirect()->route('user.send')->with('success', '✅ Card transfer submitted. Awaiting agent approval.');
    }

    /* BANK */
    public function storeBank(Request $r)
    {
        $this->attachServiceId($r, 'bank');

        $data = $r->validate([
            'service_id'     => 'nullable|exists:transfer_services,id',
            'beneficiary_id' => 'nullable|exists:beneficiaries,id',
            'receiver_name'  => 'nullable|string|max:120',
            'iban'           => 'nullable|string|max:34',
            'amount'         => 'required|numeric|min:1',
            'src_currency'   => 'required|string|size:3',
            'dst_currency'   => 'required|string|size:3',
            'note'           => 'nullable|string|max:255',
        ]);

        $note = $r->input('note');
        $b = null;

        if (!empty($data['beneficiary_id'])) {
            $b = Beneficiary::where('id', $data['beneficiary_id'])
                ->where('user_id', Auth::id())
                ->first();
        }

        $receiverName = $b->name ?? $data['receiver_name'];
        $iban         = $b->iban ?? $data['iban'];

        if (!$receiverName || !$iban) {
            return back()->with('error', 'Receiver name and IBAN are required.');
        }

        $bank = PaymentMethod::where('user_id', Auth::id())
            ->where('type', 'bank')
            ->where('is_default', true)
            ->first();

        if (!$bank) {
            return back()->with('error', ' Default bank account not found.');
        }

        $service = TransferService::find($data['service_id']);
        if (!$service) {
            return back()->with('error', ' Transfer service not found.');
        }

        $fee   = $service->calculateFee($data['amount']);
        $total = $data['amount'] + $fee;

        if (strtoupper($bank->currency) !== strtoupper($data['src_currency'])) {
            return back()->with('error', ' Currency mismatch for bank account.');
        }

        if ($bank->balance < $total) {
            return back()->with('error', ' Insufficient bank balance.');
        }

        $ref = (string) Str::uuid();

        DB::transaction(function () use ($data, $receiverName, $iban, $b, $bank, $service, $fee, $total, $ref, $note) {
            $t = Transfer::create([
                'user_id'        => Auth::id(),
                'beneficiary_id' => $b->id ?? null,
                'method'         => 'bank',
                'service_id'     => $service->id,
                'source'         => 'bank:' . $bank->bank_name,
                'destination'    => $iban,
                'src_currency'   => strtoupper($data['src_currency']),
                'dst_currency'   => strtoupper($data['dst_currency']),
                'amount_src'     => $data['amount'],
                'amount_dst'     => $data['amount'],
                'fee'            => $fee,
                'fx_rate'        => 1,
                'status'         => 'processing',
                'reference'      => $ref,
            ]);

            TransferEvent::create([
                'transfer_id' => $t->id,
                'event'       => 'created',
                'meta'        => "Receiver: $receiverName | IBAN: $iban" . ($note ? " | Note: $note" : ''),
            ]);
        });

        return redirect()->route('user.send')->with('success', '✅ Bank transfer submitted. Awaiting approval.');
    }

    /* Show transfer details */
    public function show(Transfer $transfer)
    {
        if ((int)$transfer->user_id !== (int)Auth::id()) {
            return back()->with('error', ' Unauthorized access to this transfer.');
        }

        $events = $transfer->events()->orderBy('id')->get();
        return view('user.transfers.show', compact('transfer', 'events'));
    }

    /* Attach service ID helper */
    private function attachServiceId(Request $r, string $method): void
    {
        if ($r->filled('service_id')) return;

        $svc = TransferService::where('active', true)
            ->where('method', $method)
            ->orderByRaw("FIELD(speed,'instant','same_day','3_days','5_days')")
            ->orderBy('fee_percent')
            ->orderBy('fixed_fee')
            ->first();

        if ($svc) {
            $r->merge(['service_id' => $svc->id]);
        } else {
            $default = TransferService::firstOrCreate(
                ['method' => $method, 'code' => 'DEFAULT-' . strtoupper($method)],
                [
                    'name'        => ucfirst($method) . ' Standard',
                    'fee_percent' => 0,
                    'fixed_fee'   => 0,
                    'speed'       => 'instant',
                    'active'      => true,
                ]
            );
            $r->merge(['service_id' => $default->id]);
        }
    }
}
