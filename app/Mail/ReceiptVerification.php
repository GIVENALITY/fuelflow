<?php

namespace App\Mail;

use App\Models\Receipt;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReceiptVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $receipt;
    public $action;

    public function __construct(Receipt $receipt, $action)
    {
        $this->receipt = $receipt;
        $this->action = $action;
    }

    public function build()
    {
        $subject = $this->action === 'verified'
            ? '✅ Receipt Verified - #' . $this->receipt->receipt_number
            : '❌ Receipt Rejected - #' . $this->receipt->receipt_number;

        return $this->subject($subject)
            ->view('emails.receipt-verification')
            ->with([
                'receipt' => $this->receipt,
                'client' => $this->receipt->client,
                'fuelRequest' => $this->receipt->fuelRequest,
                'action' => $this->action,
            ]);
    }
}
