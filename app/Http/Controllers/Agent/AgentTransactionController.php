<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TransferStatusUpdated;
class AgentTransactionController extends Controller
{
    public function index()
    {
        $agent = Auth::user()->agent ?? null;

        $transactions = Transfer::with(['user', 'beneficiary'])
            // if you have agent_id on transfers, uncomment this to restrict:
            // ->where('agent_id', $agent->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('agent.transactions.index', compact('transactions'));
    }

    public function updateStatus(Request $request, Transfer $transfer)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $transfer->status = $request->status;
        $transfer->save();

        // notify transfer owner
        if ($transfer->user) {
            $transfer->user->notify(new TransferStatusUpdated($transfer));
        }

        return back()->with('success', 'Transaction updated successfully.');
    }
}
