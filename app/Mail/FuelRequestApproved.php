<?php

namespace App\Mail;

use App\Models\FuelRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FuelRequestApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $fuelRequest;

    public function __construct(FuelRequest $fuelRequest)
    {
        $this->fuelRequest = $fuelRequest;
    }

    public function build()
    {
        return $this->subject('✅ Fuel Request Approved - ' . $this->fuelRequest->vehicle->plate_number)
            ->view('emails.fuel-request-approved')
            ->with([
                'fuelRequest' => $this->fuelRequest,
                'client' => $this->fuelRequest->client,
                'vehicle' => $this->fuelRequest->vehicle,
                'station' => $this->fuelRequest->station,
            ]);
    }
}
