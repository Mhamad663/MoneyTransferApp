<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Transfer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Beneficiary;
use App\Notifications\TransferCompletedNotification;


class DashboardController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $wallet = $user->wallet; 
         $beneficiariesCount = Beneficiary::where('user_id', $user->id)->count(); 

        
        $rates = Cache::remember('exchange_rates', now()->addMinutes(30), function() {
            $response = Http::get('https://api.exchangerate.host/latest', [
                'base'    => 'EUR',
                'symbols' => 'USD,LBP'
            ]);

            if ($response->ok()) {
                return $response->json()['rates'] ?? [];
            }
            return [];
        });

        
        $balance          = $wallet?->balance ?? 0;
        $totalTransfers   = Transfer::where('user_id', $user->id)->count();
        $pendingTransfers = Transfer::where('user_id', $user->id)
                                    ->whereIn('status', ['processing','pending'])
                                    ->count();
        $avgRating        = round(Review::where('context','service')->avg('score') ?? 0, 2);

        
        $alerts = Transfer::where('user_id', $user->id)
                        ->whereIn('status', ['processing','failed'])
                        ->latest()
                        ->take(5)
                        ->get();

        
        $labels  = [];
        $spend   = [];
        $receive = [];

        $start = Carbon::now()->startOfMonth()->subMonths(5);
        for ($i = 0; $i < 6; $i++) {
            $month = (clone $start)->addMonths($i);
            $labels[] = $month->format('M Y');

            $out = Transfer::where('user_id', $user->id)
                    ->where('status', 'completed')
                    ->whereBetween('created_at', [
                        $month->copy()->startOfMonth(),
                        $month->copy()->endOfMonth()
                    ])
                    ->sum('amount_dst');

            $in = 0;
            if ($wallet) {
                $in = Transfer::where('status', 'completed')
                        ->where('method', 'wallet')
                        ->where('destination', $wallet->wallet_id)
                        ->whereBetween('created_at', [
                            $month->copy()->startOfMonth(),
                            $month->copy()->endOfMonth()
                        ])
                        ->sum('amount_dst');
            }

            $spend[]   = round($out, 2);
            $receive[] = round($in, 2);
        }


       

        
        $unreadCount  = $user->unreadNotifications()->count();
        $latestNotifs = $user->notifications()->latest()->limit(6)->get();

        
        $recent = Transfer::where('user_id', $user->id)
                        ->latest()
                        ->take(8)
                        ->get();

            
        $myRating = Review::where('user_id', $user->id)
                        ->where('context', 'service')
                        ->first();

            
       return view('user.dashboard', compact(
    'wallet',
    'balance',
    'totalTransfers',
    'pendingTransfers',
    'avgRating',
    'alerts',
    'labels',
    'spend',
    'receive',
    'recent',
    'myRating',
    'unreadCount',
    'latestNotifs',
    'rates',
    'beneficiariesCount'
))

->with('ratesFetchedAt', now());

    }

    private function fetchRatesWithFallback(): array
{
        
    return Cache::remember('exchange_rates_fallback', now()->addMinutes(30), function () {
            
        try {
            $r = Http::timeout(10)->retry(2, 300)->withHeaders([
                'User-Agent' => 'MasrefDashboard/1.0'
            ])->get('https://open.er-api.com/v6/latest/EUR');

            if ($r->ok() && isset($r['rates']['USD'])) {
                $eurUsd = (float)$r['rates']['USD'];    
                $lbp = $r['rates']['LBP'] ?? null;      
                return [
                    'provider' => 'er-api',
                    'eur_usd'  => $eurUsd,
                    
                    'usd_lbp'  => $lbp ? (float)($lbp / $eurUsd) : null,
                ];
            }
        } catch (\Throwable $e) {
                
        }

            
        try {
            $r = Http::timeout(10)->retry(2, 300)->withHeaders([
                'User-Agent' => 'MasrefDashboard/1.0'
            ])->get('https://api.exchangerate.host/latest', [
                'base'    => 'EUR',
                'symbols' => 'USD,LBP'
            ]);

            if ($r->ok() && isset($r['rates']['USD'])) {
                $eurUsd = (float)$r['rates']['USD'];
                $eurLbp = $r['rates']['LBP'] ?? null;
                return [
                    'provider' => 'exchangerate.host',
                    'eur_usd'  => $eurUsd,
                    'usd_lbp'  => $eurLbp ? (float)($eurLbp / $eurUsd) : null,
                ];
            }
        } catch (\Throwable $e) {
            
        }

            
        try {
            $r = Http::timeout(10)->retry(2, 300)->withHeaders([
                'User-Agent' => 'MasrefDashboard/1.0'
            ])->get('https://api.frankfurter.app/latest', [
                'base'    => 'EUR',
                'symbols' => 'USD'
            ]);

            if ($r->ok() && isset($r['rates']['USD'])) {
                return [
                    'provider' => 'frankfurter',
                    'eur_usd'  => (float)$r['rates']['USD'],
                    'usd_lbp'  => null, 
                ];
            }
        } catch (\Throwable $e) {
                
        }

        
        return [
            'provider' => null,
            'eur_usd'  => null,
            'usd_lbp'  => null,
        ];
    });
}

public function getRates()
{
    $data = $this->fetchRatesWithFallback();

    if (!$data['eur_usd'] && !$data['usd_lbp']) {
        return response()->json([
            'success' => false,
            'message' => 'No provider responded'
        ], 502);
    }

    return response()->json([
        'success' => true,
        'provider'=> $data['provider'],
        'rates'   => [
            'EUR_USD' => $data['eur_usd'],
            'USD_LBP' => $data['usd_lbp'],
        ],
        'updated' => now()->format('H:i'),
    ]);
}



}
