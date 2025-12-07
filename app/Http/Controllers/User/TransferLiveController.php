<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transfer;
use Illuminate\Support\Facades\Auth;

class TransferLiveController extends Controller
{
    /* Page */
    public function index()
    {
        return view('user.transfers.live');
    }

    /* JSON for polling */
    public function data()
    {
        $rows = Transfer::with('service')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['processing','failed'])
            ->orderByDesc('id')
            ->limit(50)
            ->get()
            ->map(function ($t) {
                return [
                    'id'         => $t->id,
                    'reference'  => $t->reference,
                    'method'     => ucfirst($t->method),
                    'service'    => $t->service->name ?? '—',
                    'amount'     => strtoupper($t->dst_currency ?? $t->src_currency).' '.number_format($t->amount_dst ?? $t->amount_src, 2),
                    'status'     => ucfirst($t->status),
                    'created_at' => $t->created_at->format('Y-m-d H:i'),
                    'updated_at' => $t->updated_at->format('Y-m-d H:i'),
                    'show_url'   => route('user.transactions.show', $t),
                ];
            });

        return response()->json(['items' => $rows]);
    }
}