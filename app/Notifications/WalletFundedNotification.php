<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Http\Request;                     
use App\Models\Wallet;                           
use App\Models\WalletTransaction;                
use Illuminate\Support\Str;                     
use Illuminate\Support\Facades\Auth;  
class WalletFundedNotification extends Notification
{
    use Queueable;

    public $amount;
    public $walletId;
    public $agent;

    public function __construct($amount, $walletId, $agent)
    {
        $this->amount   = $amount;
        $this->walletId = $walletId;
        $this->agent    = $agent;
    }

    public function via($notifiable)
    {
        return ['database']; 
    }

    public function toArray($notifiable)
    {
        return [
            'message'   => 'Your wallet ' . $this->walletId .
                           ' has been credited with ' .
                           number_format($this->amount, 2) .
                           ' by agent ' . ($this->agent->name ?? 'Agent') . '.',
            'amount'    => $this->amount,
            'wallet_id' => $this->walletId,
            'agent_id'  => $this->agent->id ?? null,
        ];
    }
    public function fund(Request $request)
{
    $request->validate([
        'wallet_id' => 'required|exists:wallets,wallet_id',
        'amount'    => 'required|numeric|min:1',
    ]);

    // find wallet + user
    $wallet = Wallet::where('wallet_id', $request->wallet_id)
        ->with('user')
        ->firstOrFail();

    // update balance
    $wallet->increment('balance', $request->amount);

    // log transaction
    WalletTransaction::create([
        'tx_type'     => 'agent_topup',
        'amount'      => $request->amount,
        'reference'   => Str::uuid(),
        'receiver_id' => $wallet->user_id,
        'status'      => 'completed',
    ]);

    // send notification to that user
    $wallet->user->notify(
        new WalletFundedNotification(
            $request->amount,
            $wallet->wallet_id,
            Auth::user()       
        )
    );

    return back()->with('success', 'Wallet funded and user notified.');
}
}
