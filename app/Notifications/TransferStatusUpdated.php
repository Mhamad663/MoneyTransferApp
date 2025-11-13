<?php

namespace App\Notifications;

use App\Models\Transfer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class TransferStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $transfer;
    protected $message;

    public function __construct(Transfer $transfer, string $message)
    {
        $this->transfer = $transfer;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database']; // store only in database
    }

    public function toDatabase($notifiable)
    {
        return [
            'transfer_id' => $this->transfer->id,
            'reference'   => $this->transfer->reference,
            'method'      => $this->transfer->method,
            'status'      => $this->transfer->status,
            'amount'      => $this->transfer->amount_dst,
            'currency'    => $this->transfer->dst_currency,
            'message'     => $this->message,
        ];
    }
}
