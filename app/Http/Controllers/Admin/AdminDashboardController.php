<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransferService;
use App\Models\ExchangeRate;
use App\Models\User;
use App\Models\Agent;
use App\Models\Beneficiary;
use App\Models\RefundRequest;
use App\Models\Transfer;class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'     => User::count(),
            'pending_agents'  => Agent::where('is_active', 0)->count(),
            'today_transfers' => Transfer::whereDate('created_at', today())->count(),
            'fraud_alerts'    => RefundRequest::where('status', 'flagged_fraud')->count() ?? 0,
            'currencies'      => ExchangeRate::distinct('from_currency')->count('from_currency'),
            'fee_tiers'       => TransferService::count(),
            'open_tickets'    => 0,
        ];

        // Chart data
        $startDate = now()->subDays(6)->startOfDay();

        $raw = Transfer::selectRaw('DATE(created_at) as d, COUNT(*) as total')
            ->where('created_at', '>=', $startDate)
            ->groupBy('d')
            ->orderBy('d')
            ->pluck('total', 'd');   

        $labels = [];
        $data   = [];

        for ($i = 0; $i < 7; $i++) {
            $day = $startDate->copy()->addDays($i);
            $key = $day->toDateString();          

            $labels[] = $day->format('d M');     
            $data[]   = $raw[$key] ?? 0;
        }

        $chart = [
            'labels' => $labels,
            'data'   => $data,
        ];

        $recentTransfers = Transfer::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($t) {
                $t->sender_name = $t->user->name ?? 'N/A';
                $t->amount      = $t->amount_src;
                $t->currency    = $t->src_currency;
                return $t;
            });

        return view('admin.dashboard', compact('stats', 'recentTransfers', 'chart'));
    }

    public function overview()
    {
        $stats = [
            'total_users'     => User::count(),
            'total_agents'    => Agent::count(),
            'active_agents'   => Agent::where('is_active', 1)->count(),
            'pending_agents'  => Agent::where('is_active', 0)->count(),
            'total_transfers' => Transfer::count(),
        ];

        $latestUsers    = User::latest()->take(10)->get();
        $latestAgents   = Agent::latest()->take(10)->get();

        return view('admin.overview.index', compact('stats', 'latestUsers', 'latestAgents'));
    }
}

