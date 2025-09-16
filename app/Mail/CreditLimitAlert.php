<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreditLimitAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $currentBalance;
    public $creditLimit;
    public $percentage;

    public function __construct(User $user, $currentBalance, $creditLimit, $percentage)
    {
        $this->user = $user;
        $this->currentBalance = $currentBalance;
        $this->creditLimit = $creditLimit;
        $this->percentage = $percentage;
    }

    public function build()
    {
        return $this->subject('⚠️ Credit Limit Alert - ' . $this->percentage . '% Used')
            ->view('emails.credit-limit-alert')
            ->with([
                'user' => $this->user,
                'currentBalance' => $this->currentBalance,
                'creditLimit' => $this->creditLimit,
                'percentage' => $this->percentage,
                'availableCredit' => $this->creditLimit - $this->currentBalance,
            ]);
    }
}
