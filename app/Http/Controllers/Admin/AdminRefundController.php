<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use Illuminate\Http\Request;

class AdminRefundController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'open');

        $query = RefundRequest::with(['user', 'transfer'])
            ->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $requests = $query->paginate(20)->withQueryString();

        return view('admin.fraud.index', compact('requests', 'status'));
    }

    public function updateStatus(Request $request, RefundRequest $refund)
    {
        $data = $request->validate([
            'status' => 'required|in:open,in_review,resolved,rejected,flagged_fraud',
        ]);

        $refund->status = $data['status'];
        $refund->save();

        return back()->with('success', 'Refund request updated.');
    }
}
