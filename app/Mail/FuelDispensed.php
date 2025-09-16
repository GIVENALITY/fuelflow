<?php

namespace App\Mail;

use App\Models\FuelRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FuelDispensed extends Mailable
{
    use Queueable, SerializesModels;

    public $fuelRequest;

    public function __construct(FuelRequest $fuelRequest)
    {
        $this->fuelRequest = $fuelRequest;
    }

    public function build()
    {
        return $this->subject('⛽ Fuel Dispensed - ' . $this->fuelRequest->vehicle->plate_number)
            ->view('emails.fuel-dispensed')
            ->with([
                'fuelRequest' => $this->fuelRequest,
                'client' => $this->fuelRequest->client,
                'vehicle' => $this->fuelRequest->vehicle,
                'station' => $this->fuelRequest->station,
            ]);
    }
}
