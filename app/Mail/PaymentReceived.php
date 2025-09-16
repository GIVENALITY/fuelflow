<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function build()
    {
        return $this->subject('💰 Payment Received - $' . number_format($this->payment->amount, 2))
            ->view('emails.payment-received')
            ->with([
                'payment' => $this->payment,
                'client' => $this->payment->client,
                'receipt' => $this->payment->receipt,
            ]);
    }
}
