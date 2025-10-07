<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSubmittedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Payment Submission - ' . $this->payment->client->company_name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new payment has been submitted for review.')
            ->line('**Payment Details:**')
            ->line('- Client: ' . $this->payment->client->company_name)
            ->line('- Amount: TZS ' . number_format($this->payment->amount, 2))
            ->line('- Bank: ' . $this->payment->bank_name)
            ->line('- Payment Date: ' . $this->payment->payment_date->format('M d, Y'))
            ->action('Review Payment', route('payments.show', $this->payment))
            ->line('Please review the payment and verify the proof of payment document.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'payment_submitted',
            'payment_id' => $this->payment->id,
            'client_name' => $this->payment->client->company_name,
            'amount' => $this->payment->amount,
            'bank_name' => $this->payment->bank_name,
            'payment_date' => $this->payment->payment_date,
            'message' => 'New payment submitted by ' . $this->payment->client->company_name . ' for TZS ' . number_format($this->payment->amount, 2),
        ];
    }
}
