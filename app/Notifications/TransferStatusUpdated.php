<?php

namespace App\Notifications;

use App\Models\Transfer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Http\Request;                     


class TransferStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(public Transfer $transfer) {}

    public function via($notifiable): array
    {
       
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message'      => sprintf(
                'Your transfer %s is now %s.',
                $this->transfer->reference,
                ucfirst($this->transfer->status)
            ),
            'status'       => $this->transfer->status,
            'amount'       => $this->transfer->amount_dst,
            'currency'     => $this->transfer->dst_currency,
            'reference'    => $this->transfer->reference,
            'transfer_id'  => $this->transfer->id,
        ];
    }
    public function updateStatus(Request $request, Transfer $transfer)
{
    $request->validate([
        'status' => 'required|in:pending,processing,completed,failed',
    ]);

    $transfer->status = $request->status;
    $transfer->save();

    // notify the owner of this transfer
    if ($transfer->user) {
        $transfer->user->notify(new TransferStatusUpdated($transfer));
    }

    return back()->with('success', 'Transfer status updated & user notified.');
}
}
