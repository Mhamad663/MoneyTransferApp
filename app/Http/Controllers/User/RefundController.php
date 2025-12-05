<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use App\Models\Transfer;
use App\Models\TransferEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefundController extends Controller
{
    // List my requests
    public function index()
    {
        $requests = RefundRequest::with('transfer')
            ->where('user_id', Auth::id())
            ->latest()->paginate(12);

        return view('user.refunds.index', compact('requests'));
    }

    // Open form
    public function create(Transfer $transfer)
    {
        abort_unless($transfer->user_id === Auth::id(), 403);

        // You can restrict to only completed/processing & within 30 days:
        // abort_if($transfer->created_at->lt(now()->subDays(30)), 422, 'Request window expired.');

        abort_if(RefundRequest::where('transfer_id',$transfer->id)->exists(),
            422, 'A request already exists for this transfer.');

        return view('user.refunds.create', compact('transfer'));
    }

    // Submit request
    public function store(Request $r, Transfer $transfer)
    {
        abort_unless($transfer->user_id === Auth::id(), 403);
        abort_if(RefundRequest::where('transfer_id',$transfer->id)->exists(),
            422, 'A request already exists for this transfer.');

        $data = $r->validate([
            'kind'   => 'required|in:refund,dispute',
            'reason' => 'nullable|string|max:120',
            'details'=> 'nullable|string|max:2000',
        ]);

        $req = RefundRequest::create([
            'user_id'     => Auth::id(),
            'transfer_id' => $transfer->id,
            'kind'        => $data['kind'],
            'reason'      => $data['reason'] ?? null,
            'details'     => $data['details'] ?? null,
            'status'      => 'open',
        ]);

        // Log an event on the transfer timeline
        TransferEvent::create([
            'transfer_id' => $transfer->id,
            'event'       => 'refund_requested',
            'meta'        => strtoupper($req->kind) . ' — ' . ($req->reason ?? 'No reason'),
        ]);

        // (Optional) notify the user and/or backoffice channel
        // Auth::user()->notify(new \App\Notifications\RefundOpened($req));

        return redirect()->route('user.refunds.index')
            ->with('success','Your request was submitted. We’ll review it shortly.');
    }
}
