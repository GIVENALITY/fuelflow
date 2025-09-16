<?php

namespace App\Mail;

use App\Models\FuelRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FuelRequestRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $fuelRequest;
    public $reason;

    public function __construct(FuelRequest $fuelRequest, $reason)
    {
        $this->fuelRequest = $fuelRequest;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('❌ Fuel Request Rejected - ' . $this->fuelRequest->vehicle->plate_number)
            ->view('emails.fuel-request-rejected')
            ->with([
                'fuelRequest' => $this->fuelRequest,
                'client' => $this->fuelRequest->client,
                'vehicle' => $this->fuelRequest->vehicle,
                'station' => $this->fuelRequest->station,
                'reason' => $this->reason,
            ]);
    }
}
