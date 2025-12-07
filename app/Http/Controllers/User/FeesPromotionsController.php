<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\TransferService;
use App\Models\Promotion;

class FeesPromotionsController extends Controller
{
    public function index()
    {
        $services   = TransferService::where('active', true)
                        ->orderBy('method')->orderBy('name')->get();

        $promotions = Promotion::where('active', true)
                        ->orderBy('start_date','desc')->get();

        return view('user/fees_promotions', compact('services','promotions'));

    }
}
