<?php

namespace App\Mail;

use App\Models\Station;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LowStockAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $station;
    public $fuelType;
    public $currentLevel;
    public $threshold;

    public function __construct(Station $station, $fuelType, $currentLevel, $threshold)
    {
        $this->station = $station;
        $this->fuelType = $fuelType;
        $this->currentLevel = $currentLevel;
        $this->threshold = $threshold;
    }

    public function build()
    {
        return $this->subject('⚠️ Low Stock Alert - ' . $this->station->name)
            ->view('emails.low-stock-alert')
            ->with([
                'station' => $this->station,
                'fuelType' => $this->fuelType,
                'currentLevel' => $this->currentLevel,
                'threshold' => $this->threshold,
                'percentage' => round(($this->currentLevel / $this->station->getCurrentFuelLevel($this->fuelType)) * 100, 1),
            ]);
    }
}
