<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent; // <-- ADD THIS
use App\Models\Transfer;
use App\Models\TransferEvent;
use Illuminate\Support\Facades\Auth;

class AgentDashboardController extends Controller
{
    public function index()
    {
        // Load agent data for dashboard 
        $agent = Agent::where('user_id', Auth::id())->firstOrFail();

        // Pending Transfers
        $pendingTransfers = Transfer::where('status', 'pending')
            ->with(['user', 'beneficiary'])
            ->get();

        // Completed Transfers
        $completedTransfers = Transfer::where('status', 'completed')->get();

        // Cash-out requests
        $cashOutRequests = Transfer::where('status', 'cashout_requested')->count();

        // Total revenue from fees
        $totalRevenue = Transfer::sum('fee');

        // Recent transactions
        $recentTransactions = Transfer::orderBy('created_at', 'desc')
            ->limit(5)
            ->with(['beneficiary'])
            ->get();

        // Notifications
        $notifications = TransferEvent::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('agent.dashboard', compact(
            'agent',                 
            'pendingTransfers',
            'completedTransfers',
            'cashOutRequests',
            'totalRevenue',
            'recentTransactions',
            'notifications'
        ));
    }
}
