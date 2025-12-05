<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use Illuminate\Http\Request;
use App\Notifications\TransferStatusUpdated;
class AdminTransactionController extends Controller
{
    public function index(Request $request)
    {
        $scope  = $request->get('scope', 'all');
        $status = $request->get('status');
        $search = $request->get('search');

        $query = Transfer::with(['user', 'beneficiary']);

        // Filter: scope=today (when you click the dashboard card)
        if ($scope === 'today') {
            $query->whereDate('created_at', today());
        }

        // Filter: status
        if ($status) {
            $query->where('status', $status);
        }

        // Filter: search by reference / sender / beneficiary
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('beneficiary', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $transfers = $query
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.transactions.index', [
            'transfers' => $transfers,
            'scope'     => $scope,
            'status'    => $status,
            'search'    => $search,
        ]);
    }
    public function updateStatus(Request $request, Transfer $transfer)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $transfer->status = $request->status;
        $transfer->save();

        if ($transfer->user) {
            $transfer->user->notify(new TransferStatusUpdated($transfer));
        }

        return back()->with('success', 'Status updated.');
    }

}
