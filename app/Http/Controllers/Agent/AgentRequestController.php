<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use App\Models\TransferEvent;
use Illuminate\Http\Request;

class AgentRequestController extends Controller
{
    public function index()
    {
        // Only pending transfers
        $pendingTransfers = Transfer::where('status', 'pending')
            ->with(['user', 'beneficiary'])
            ->get();

        return view('agent.requests.index', compact('pendingTransfers'));
    }

    public function approve($id)
    {
        $transfer = Transfer::findOrFail($id);
        $transfer->status = 'completed';
        $transfer->save();

        TransferEvent::create([
            'transfer_id' => $id,
            'event_type' => 'approved',
            'description' => 'Transfer approved by agent',
        ]);

        return back()->with('success', 'Transfer approved successfully.');
    }

    public function decline($id)
    {
        $transfer = Transfer::findOrFail($id);
        $transfer->status = 'declined';
        $transfer->save();

        TransferEvent::create([
            'transfer_id' => $id,
            'event_type' => 'declined',
            'description' => 'Transfer declined by agent',
        ]);

        return back()->with('success', 'Transfer declined successfully.');
    }
}
