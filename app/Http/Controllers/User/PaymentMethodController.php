<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;


class PaymentMethodController extends Controller
{
    public function index()
{
    $userId = Auth::id();

    // separate paginations
    $cards = \App\Models\PaymentMethod::where('user_id',$userId)
        ->where('type','card')
        ->orderByDesc('is_default')
        ->paginate(6, ['*'], 'cards_page');

    $banks = \App\Models\PaymentMethod::where('user_id',$userId)
        ->where('type','bank')
        ->orderByDesc('is_default')
        ->paginate(6, ['*'], 'banks_page');

    return view('user.payments.index', compact('cards','banks'));
}

// app/Http/Controllers/User/PaymentMethodController.php
public function details(\App\Models\PaymentMethod $paymentMethod)
{
    abort_unless($paymentMethod->user_id === Auth::id(), 403);

    if ($paymentMethod->type === 'card') {
        return response()->json([
            'type'        => 'card',
            'brand'       => $paymentMethod->brand,
            'last4'       => $paymentMethod->last4,
            'exp_month'   => $paymentMethod->exp_month,
            'exp_year'    => $paymentMethod->exp_year,
            // IMPORTANT: one of these must actually exist in your table
            // e.g. `pan` (encrypted), `full_number`, etc.
            'full_number' => $paymentMethod->pan ?? $paymentMethod->full_number ?? null,
        ]);
    }

    // bank
    return response()->json([
        'type'         => 'bank',
        'bank_name'    => $paymentMethod->bank_name,
        'iban'         => $paymentMethod->iban,
        'account'      => $paymentMethod->account_number,
        // IMPORTANT: whichever column you really store
        'full_iban'    => $paymentMethod->full_iban ?? $paymentMethod->iban ?? null,
        'full_account' => $paymentMethod->full_account ?? $paymentMethod->account_number ?? null,
    ]);
}



    public function create()
    {
        return view('user.payments.create');
    }

    /** Store CARD (tokenize in real production) */
    public function storeCard(Request $r)
    {
        $r->validate([
            'holder_name' => 'required|string|max:120',
            'number'      => 'required|string',
            'exp_month'   => 'required|integer|min:1|max:12',
            'exp_year'    => 'required|integer|min:'.date('Y').'|max:'.(date('Y')+15),
            'cvv'         => 'required|string|min:3|max:4',
        ]);

        // Luhn validation
        if (! $this->luhnCheck($r->number)) {
            return back()->withErrors(['number' => 'Invalid card number'])->withInput();
        }

        // ————— Replace this block with Stripe/Paystack tokenization —————
        // Example for Stripe:
        // \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        // $tokenObj = \Stripe\Token::create([
        //     'card' => [
        //         'name' => $r->holder_name,
        //         'number' => preg_replace('/\D/','',$r->number),
        //         'exp_month' => $r->exp_month,
        //         'exp_year' => $r->exp_year,
        //         'cvc' => $r->cvv,
        //     ],
        // ]);
        // $token = $tokenObj->id;
        $token = 'tok_local_'.uniqid(); // placeholder
        // ————————————————————————————————————————————————————————————————

        $last4 = substr(preg_replace('/\D/','',$r->number), -4);
        $brand = $this->detectBrand($r->number);

        $firstCard = ! PaymentMethod::where('user_id',Auth::id())->where('type','card')->exists();

        PaymentMethod::create([
            'user_id'   => Auth::id(),
            'type'      => 'card',
            'brand'     => $brand,
            'last4'     => $last4,
            'exp_month' => $r->exp_month,
            'exp_year'  => $r->exp_year,
            'token'     => $token,          // store token ONLY
            'is_default'=> $firstCard,
        ]);

        return redirect()->route('user.payments.index')->with('success','Card added.');
    }

    /** Store BANK (mask account/IBAN) */
    public function storeBank(Request $r)
    {
        $r->validate([
            'bank_name'      => 'required|string|max:120',
            'iban'           => 'nullable|string|max:34',
            'account_number' => 'nullable|string|max:34',
        ]);

        $maskedIban  = $r->iban ? $this->maskMiddle($r->iban, 4) : null;
        $maskedAcct  = $r->account_number ? $this->maskMiddle($r->account_number, 4) : null;

        $firstBank = ! PaymentMethod::where('user_id',Auth::id())->where('type','bank')->exists();

        PaymentMethod::create([
            'user_id'        => Auth::id(),
            'type'           => 'bank',
            'bank_name'      => $r->bank_name,
            'iban'           => $maskedIban,
            'account_number' => $maskedAcct,
            'is_default'     => $firstBank,
        ]);

        return redirect()->route('user.payments.index')->with('success','Bank account added.');
    }

    public function setDefault(PaymentMethod $paymentMethod)
    {
        abort_unless($paymentMethod->user_id === Auth::id(), 403);

        PaymentMethod::where('user_id',Auth::id())
            ->where('type',$paymentMethod->type)
            ->update(['is_default'=>false]);

        $paymentMethod->update(['is_default'=>true]);

        return back()->with('success','Default updated.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        abort_unless($paymentMethod->user_id === Auth::id(), 403);
        $type = $paymentMethod->type;

        $paymentMethod->delete();

        $remaining = PaymentMethod::where('user_id',Auth::id())->where('type',$type)->get();
        if ($remaining->count() && ! $remaining->firstWhere('is_default',true)) {
            $remaining->first()->update(['is_default'=>true]);
        }

        return back()->with('success','Payment method removed.');
    }

    // ---------- Helpers ----------

    private function luhnCheck($number): bool
    {
        $n = preg_replace('/\D/','',$number);
        $sum = 0; $alt = false;
        for ($i = strlen($n) - 1; $i >= 0; $i--) {
            $d = (int)$n[$i];
            if ($alt) { $d *= 2; if ($d > 9) $d -= 9; }
            $sum += $d; $alt = ! $alt;
        }
        return ($sum % 10) === 0;
    }

    private function detectBrand($num): string
    {
        $n = preg_replace('/\D/','',$num);
        if (preg_match('/^4\d{12}(\d{3})?(\d{3})?$/',$n)) return 'Visa';
        if (preg_match('/^5[1-5]\d{14}$/',$n)) return 'MasterCard';
        if (preg_match('/^3[47]\d{13}$/',$n)) return 'Amex';
        if (preg_match('/^6(?:011|5\d{2})\d{12}$/',$n)) return 'Discover';
        return 'Card';
    }

    private function maskMiddle(string $s, int $visible = 4): string
    {
        $s = preg_replace('/\s+/', '', $s);
        $len = strlen($s);
        if ($len <= $visible * 2) return str_repeat('*', $len);
        return substr($s, 0, $visible) . str_repeat('*', $len - ($visible * 2)) . substr($s, -$visible);
    }


}
