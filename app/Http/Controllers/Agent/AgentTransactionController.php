<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Transfer;

class AgentTransactionController extends Controller
{
    public function index()
    {
        // load with user + beneficiary
        $transactions = Transfer::with(['user', 'beneficiary'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('agent.transactions.index', compact('transactions'));
    }
}
