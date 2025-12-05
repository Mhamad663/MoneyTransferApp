<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use App\Models\TransferEvent;
use Illuminate\Http\Request;

class AgentPayoutController extends Controller
{
    public function index()
    {
        // Transfers waiting for cash-out
        $payouts = Transfer::where('status', 'cashout_requested')
            ->with(['user', 'beneficiary'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('agent.payouts.index', compact('payouts'));
    }

    public function complete($id)
    {
        $transfer = Transfer::findOrFail($id);
        $transfer->status = 'completed';
        $transfer->save();

        TransferEvent::create([
            'transfer_id' => $id,
            'event_type' => 'payout_completed',
            'description' => 'Payout completed by agent',
        ]);

        return back()->with('success', 'Payout completed successfully.');
    }
}
