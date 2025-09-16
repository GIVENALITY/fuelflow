<?php

namespace App\Mail;

use App\Models\FuelRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FuelRequestAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public $fuelRequest;
    public $pumper;

    public function __construct(FuelRequest $fuelRequest, User $pumper)
    {
        $this->fuelRequest = $fuelRequest;
        $this->pumper = $pumper;
    }

    public function build()
    {
        return $this->subject('🎯 New Fuel Request Assigned - #' . $this->fuelRequest->id)
            ->view('emails.fuel-request-assigned')
            ->with([
                'fuelRequest' => $this->fuelRequest,
                'client' => $this->fuelRequest->client,
                'vehicle' => $this->fuelRequest->vehicle,
                'station' => $this->fuelRequest->station,
                'pumper' => $this->pumper,
            ]);
    }
}
