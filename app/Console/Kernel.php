<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run fuel price updates every first Wednesday of the month at 6:00 AM
        $schedule->command('fuel:update-prices')
                ->monthlyOn(1, '6:00') // First day of month at 6:00 AM
                ->when(function () {
                    // Only run on Wednesdays
                    return now()->isWednesday();
                })
                ->withoutOverlapping()
                ->runInBackground()
                ->emailOutputTo('admin@fuelflow.com'); // Optional: email results

        // Alternative: Run every Wednesday and check if it's the first Wednesday
        // $schedule->command('fuel:update-prices')
        //         ->weekly()
        //         ->wednesdays()
        //         ->at('6:00')
        //         ->withoutOverlapping()
        //         ->runInBackground();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
