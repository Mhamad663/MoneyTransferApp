<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WalletController extends Controller
{
  public function index()
{
    $user = User::with('wallet')->findOrFail(Auth::id());

    if (! $user->wallet) {
        $user->wallet()->create([
            'wallet_id' => 'WAL' . rand(100000000, 999999999),
            'balance'   => 0,
        ]);
        $user->load('wallet');
    }

    $wallet = $user->wallet;
    $transactions = \App\Models\WalletTransaction::where('sender_id', $user->id)
        ->orWhere('receiver_id', $user->id)
        ->latest()->take(10)->get();

    return view('user.wallet.index', compact('wallet','transactions'));
}


  public function topup(Request $request) {
    $request->validate(['amount'=>'required|numeric|min:1']);
    $wallet = Auth::user()->wallet;
    $wallet->increment('balance',$request->amount);

    WalletTransaction::create([
      'tx_type'=>'topup',
      'amount'=>$request->amount,
      'reference'=>Str::uuid(),
      'receiver_id'=>Auth::id(),
      'status'=>'completed'
    ]);

    return back()->with('success','Wallet topped up successfully!');
  }

  public function transfer(Request $request) {
    $request->validate([
      'wallet_id'=>'required|exists:wallets,wallet_id',
      'amount'=>'required|numeric|min:1'
    ]);

    $senderWallet = Auth::user()->wallet;
    $receiverWallet = \App\Models\Wallet::where('wallet_id',$request->wallet_id)->first();

    if($senderWallet->id == $receiverWallet->id)
      return back()->with('error','Cannot send to your own wallet');

    if($senderWallet->balance < $request->amount)
      return back()->with('error','Insufficient balance');

    // Transfer
    $senderWallet->decrement('balance',$request->amount);
    $receiverWallet->increment('balance',$request->amount);

    WalletTransaction::create([
      'sender_id'=>Auth::id(),
      'receiver_id'=>$receiverWallet->user_id,
      'tx_type'=>'transfer',
      'amount'=>$request->amount,
      'reference'=>Str::uuid(),
      'status'=>'completed'
    ]);

    return back()->with('success','Transfer completed successfully!');
  }
}
