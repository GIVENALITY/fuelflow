<?php

namespace App\Console\Commands;

use App\Models\FuelPrice;
use App\Models\Station;
use App\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateFuelPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fuel:update-prices 
                            {--date= : Specific date to update prices (Y-m-d format)}
                            {--dry-run : Show what would be updated without making changes}
                            {--force : Force update even if not first Wednesday}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update fuel prices for all stations. Typically runs on first Wednesday of each month.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->option('date') ? Carbon::parse($this->option('date')) : Carbon::now();
        $isDryRun = $this->option('dry-run');
        $force = $this->option('force');

        // Check if it's the first Wednesday of the month (unless forced)
        if (!$force && !$this->isFirstWednesday($date)) {
            $this->error('This command should only run on the first Wednesday of the month.');
            $this->info('Use --force to override this check.');
            return 1;
        }

        $this->info("Fuel Price Update Process");
        $this->info("Date: " . $date->format('Y-m-d'));
        $this->info("Mode: " . ($isDryRun ? 'DRY RUN' : 'LIVE UPDATE'));
        $this->line('');

        // Get all active stations
        $stations = Station::where('status', 'active')->get();
        $this->info("Found {$stations->count()} active stations");

        $updatedCount = 0;
        $errors = [];

        foreach ($stations as $station) {
            $this->line("Processing station: {$station->name} ({$station->code})");

            // Get current prices for this station
            $currentPrices = FuelPrice::where('station_id', $station->id)
                ->where('is_active', true)
                ->get();

            foreach ($currentPrices as $currentPrice) {
                $newPrice = $this->calculateNewPrice($currentPrice, $date);
                
                if ($newPrice !== null) {
                    $this->line("  {$currentPrice->fuel_type}: {$currentPrice->price} → {$newPrice}");
                    
                    if (!$isDryRun) {
                        try {
                            DB::transaction(function () use ($currentPrice, $newPrice, $date, $station) {
                                // Deactivate current price
                                $currentPrice->update([
                                    'is_active' => false,
                                    'expiry_date' => $date->subDay()
                                ]);

                                // Create new price record
                                FuelPrice::create([
                                    'station_id' => $currentPrice->station_id,
                                    'fuel_type' => $currentPrice->fuel_type,
                                    'price' => $newPrice,
                                    'effective_date' => $date,
                                    'is_active' => true,
                                    'created_by' => 1, // System user
                                    'notes' => 'Automatic price update - First Wednesday of month'
                                ]);

                                // Create notification for station manager
                                if ($station->manager_id) {
                                    Notification::create([
                                        'user_id' => $station->manager_id,
                                        'title' => 'Fuel Price Updated',
                                        'message' => "Fuel price for {$currentPrice->fuel_type} at {$station->name} has been updated to TZS " . number_format($newPrice, 2),
                                        'type' => 'price_update',
                                        'read_at' => null
                                    ]);
                                }
                            });
                            
                            $updatedCount++;
                        } catch (\Exception $e) {
                            $errors[] = "Error updating {$station->name} - {$currentPrice->fuel_type}: " . $e->getMessage();
                        }
                    }
                } else {
                    $this->line("  {$currentPrice->fuel_type}: No price change needed");
                }
            }
        }

        $this->line('');
        $this->info("Summary:");
        $this->info("- Stations processed: {$stations->count()}");
        $this->info("- Prices updated: {$updatedCount}");
        
        if (!empty($errors)) {
            $this->error("Errors encountered:");
            foreach ($errors as $error) {
                $this->error("  - {$error}");
            }
        }

        if ($isDryRun) {
            $this->warn("This was a dry run. No changes were made.");
            $this->info("Run without --dry-run to apply changes.");
        } else {
            $this->info("Price update completed successfully!");
        }

        return 0;
    }

    /**
     * Check if the given date is the first Wednesday of the month
     */
    private function isFirstWednesday(Carbon $date): bool
    {
        // Check if it's Wednesday
        if ($date->dayOfWeek !== Carbon::WEDNESDAY) {
            return false;
        }

        // Check if it's the first Wednesday (day of month <= 7)
        return $date->day <= 7;
    }

    /**
     * Calculate new price based on various factors
     * This is where you'd implement your pricing logic
     */
    private function calculateNewPrice(FuelPrice $currentPrice, Carbon $date): ?float
    {
        // Example pricing logic - you can customize this based on your requirements
        
        // Get the current price
        $currentAmount = $currentPrice->price;
        
        // Example: Increase by 2% every month (you can modify this logic)
        $increasePercentage = 0.02; // 2%
        
        // Calculate new price
        $newPrice = $currentAmount * (1 + $increasePercentage);
        
        // Round to 2 decimal places
        $newPrice = round($newPrice, 2);
        
        // Only update if there's a significant change (more than 1 TZS)
        if (abs($newPrice - $currentAmount) >= 1) {
            return $newPrice;
        }
        
        return null; // No change needed
    }
}
