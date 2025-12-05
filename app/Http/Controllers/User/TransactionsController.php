<?php

// app/Http/Controllers/User/TransactionsController.php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;   // <- PDF facade

class TransactionsController extends Controller
{
public function index()
{
    $items = Transfer::with(['service', 'beneficiary', 'refundRequest'])
        ->where('user_id', auth()->id())
        ->orderByDesc('created_at')
        ->paginate(15);

    return view('user.transactions.index', compact('items'));
}


    public function show(Transfer $transfer)
    {
        abort_unless((int)$transfer->user_id === (int)Auth::id(), 403);
        $transfer->load(['service','events' => fn($q)=>$q->orderBy('id')]);
        return view('user.transactions.show', ['t'=>$transfer]);
    }

    // Single receipt PDF
    public function pdfReceipt(Transfer $transfer)
    {
        abort_unless((int)$transfer->user_id === (int)Auth::id(), 403);
        $transfer->load(['service','events' => fn($q)=>$q->orderBy('id')]);

        $pdf = Pdf::loadView('user.transactions.pdf_receipt', ['t'=>$transfer]);
        return $pdf->download('receipt-'.$transfer->reference.'.pdf');
    }

    // Completed list PDF
    public function exportListPdf()
{
    $items = Transfer::with(['service','beneficiary'])    // +beneficiary
        ->where('user_id', Auth::id())
        ->where('status', 'completed')
        ->orderByDesc('id')
        ->get();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'user.transactions.pdf_list',
        ['items'=>$items, 'user'=>Auth::user()]
    );
    return $pdf->download('transactions-'.now()->format('Ymd-His').'.pdf');
}
}

