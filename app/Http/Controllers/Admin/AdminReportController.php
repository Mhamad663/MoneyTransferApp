<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        // Date range filters 
        $from = $request->input('from_date', now()->subDays(30)->toDateString());
        $to   = $request->input('to_date', now()->toDateString());

        // Base query
        $base = Transfer::whereBetween(DB::raw('DATE(created_at)'), [$from, $to]);

        // Global KPIs
        $kpis = [
            'total_transfers' => (clone $base)->count(),
            'total_volume'    => (clone $base)->sum('amount_src'),
            'avg_amount'      => (clone $base)->avg('amount_src'),
            'completed'       => (clone $base)->where('status', 'completed')->count(),
            'failed'          => (clone $base)->where('status', 'failed')->count(),
        ];

        // Volume per currency
        $byCurrency = (clone $base)
            ->select('src_currency', DB::raw('COUNT(*) as cnt'), DB::raw('SUM(amount_src) as volume'))
            ->groupBy('src_currency')
            ->orderByDesc('volume')
            ->get();

        // Volume per method (wallet/bank…)
        $byMethod = (clone $base)
            ->select('method', DB::raw('COUNT(*) as cnt'), DB::raw('SUM(amount_src) as volume'))
            ->groupBy('method')
            ->orderByDesc('volume')
            ->get();

        return view('admin.reports.index', [
            'from'       => $from,
            'to'         => $to,
            'kpis'       => $kpis,
            'byCurrency' => $byCurrency,
            'byMethod'   => $byMethod,
        ]);
    }
}
