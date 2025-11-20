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
        // Load agent data for dashboard (map, info, etc.)
        $agent = Agent::where('user_id', Auth::id())->firstOrFail();

        // 1. Pending Transfers
        $pendingTransfers = Transfer::where('status', 'pending')
            ->with(['user', 'beneficiary'])
            ->get();

        // 2. Completed Transfers
        $completedTransfers = Transfer::where('status', 'completed')->get();

        // 3. Cash-out requests
        $cashOutRequests = Transfer::where('status', 'cashout_requested')->count();

        // 4. Total revenue from fees
        $totalRevenue = Transfer::sum('fee');

        // 5. Recent transactions
        $recentTransactions = Transfer::orderBy('created_at', 'desc')
            ->limit(5)
            ->with(['beneficiary'])
            ->get();

        // 6. Notifications
        $notifications = TransferEvent::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('agent.dashboard', compact(
            'agent',                 // <-- ADD THIS
            'pendingTransfers',
            'completedTransfers',
            'cashOutRequests',
            'totalRevenue',
            'recentTransactions',
            'notifications'
        ));
    }
}
