<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Notifications\WalletFundedNotification;

class AgentWalletController extends Controller
{
    // Step 1 – show search form
    public function create()
    {
        return view('agent.wallet.topup');
    }

    // Step 2 – lookup wallet and show user info
    public function lookup(Request $request)
    {
        $request->validate([
            'wallet_id' => 'required|string',
        ]);

        $wallet = Wallet::with('user')
            ->where('wallet_id', $request->wallet_id)
            ->first();

        if (! $wallet) {
            return back()
                ->withErrors(['wallet_id' => 'Wallet not found'])
                ->withInput();
        }

        return view('agent.wallet.topup', [
            'wallet' => $wallet,
        ]);
    }

    // Step 3 – add balance + create notification
    public function store(Request $request)
    {
        $request->validate([
            'wallet_id' => 'required|exists:wallets,wallet_id',
            'amount'    => 'required|numeric|min:1',
        ]);

        $wallet = Wallet::with('user')
            ->where('wallet_id', $request->wallet_id)
            ->firstOrFail();

        // credit wallet
        $wallet->increment('balance', $request->amount);

        // log transaction
        WalletTransaction::create([
            'sender_id'   => null, // or Auth::id() if you want to track the agent as sender
            'receiver_id' => $wallet->user_id,
            'tx_type'     => 'agent_topup',
            'amount'      => $request->amount,
            'reference'   => Str::uuid(),
            'status'      => 'completed',
        ]);

        // notify user
        if ($wallet->user) {
            $wallet->user->notify(
                new WalletFundedNotification(
                    $request->amount,
                    $wallet->wallet_id,
                    Auth::user()
                )
            );
        }

        return redirect()
            ->route('agent.wallet.topup.form')
            ->with('success', 'Balance added successfully.');
    }
}
