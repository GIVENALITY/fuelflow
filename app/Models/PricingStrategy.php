<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PricingStrategy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'strategy_type', // percentage, fixed_amount, market_based, etc.
        'parameters', // JSON field for strategy-specific parameters
        'is_active',
        'effective_from',
        'effective_until'
    ];

    protected $casts = [
        'parameters' => 'array',
        'is_active' => 'boolean',
        'effective_from' => 'date',
        'effective_until' => 'date'
    ];

    // Strategy Types
    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_FIXED_AMOUNT = 'fixed_amount';
    const TYPE_MARKET_BASED = 'market_based';
    const TYPE_INFLATION_ADJUSTED = 'inflation_adjusted';

    /**
     * Calculate new price based on strategy
     */
    public function calculateNewPrice(float $currentPrice, Carbon $date = null): ?float
    {
        if (!$this->is_active || !$this->isEffective($date)) {
            return null;
        }

        $date = $date ?? Carbon::now();

        switch ($this->strategy_type) {
            case self::TYPE_PERCENTAGE:
                return $this->calculatePercentageIncrease($currentPrice);
            
            case self::TYPE_FIXED_AMOUNT:
                return $this->calculateFixedAmountIncrease($currentPrice);
            
            case self::TYPE_MARKET_BASED:
                return $this->calculateMarketBasedPrice($currentPrice, $date);
            
            case self::TYPE_INFLATION_ADJUSTED:
                return $this->calculateInflationAdjustedPrice($currentPrice, $date);
            
            default:
                return null;
        }
    }

    /**
     * Check if strategy is effective for the given date
     */
    public function isEffective(Carbon $date = null): bool
    {
        $date = $date ?? Carbon::now();
        
        if ($this->effective_from && $date < $this->effective_from) {
            return false;
        }
        
        if ($this->effective_until && $date > $this->effective_until) {
            return false;
        }
        
        return true;
    }

    /**
     * Calculate percentage-based increase
     */
    private function calculatePercentageIncrease(float $currentPrice): ?float
    {
        $percentage = $this->parameters['percentage'] ?? 0;
        
        if ($percentage == 0) {
            return null;
        }

        $newPrice = $currentPrice * (1 + ($percentage / 100));
        $newPrice = round($newPrice, 2);

        // Check minimum change threshold
        $minChange = $this->parameters['min_change'] ?? 1;
        if (abs($newPrice - $currentPrice) < $minChange) {
            return null;
        }

        return $newPrice;
    }

    /**
     * Calculate fixed amount increase
     */
    private function calculateFixedAmountIncrease(float $currentPrice): ?float
    {
        $amount = $this->parameters['amount'] ?? 0;
        
        if ($amount == 0) {
            return null;
        }

        $newPrice = $currentPrice + $amount;
        $newPrice = round($newPrice, 2);

        // Ensure price doesn't go negative
        if ($newPrice <= 0) {
            return null;
        }

        return $newPrice;
    }

    /**
     * Calculate market-based price (placeholder for external API integration)
     */
    private function calculateMarketBasedPrice(float $currentPrice, Carbon $date): ?float
    {
        // This would integrate with external market data APIs
        // For now, return null to indicate no change
        return null;
    }

    /**
     * Calculate inflation-adjusted price
     */
    private function calculateInflationAdjustedPrice(float $currentPrice, Carbon $date): ?float
    {
        $inflationRate = $this->parameters['inflation_rate'] ?? 0;
        $baseDate = $this->parameters['base_date'] ?? null;
        
        if (!$baseDate || $inflationRate == 0) {
            return null;
        }

        $baseDate = Carbon::parse($baseDate);
        $monthsDiff = $date->diffInMonths($baseDate);
        
        $newPrice = $currentPrice * pow(1 + ($inflationRate / 100), $monthsDiff);
        $newPrice = round($newPrice, 2);

        return $newPrice;
    }

    /**
     * Get active strategy for a specific date
     */
    public static function getActiveStrategy(Carbon $date = null): ?self
    {
        $date = $date ?? Carbon::now();
        
        return self::where('is_active', true)
            ->where(function($query) use ($date) {
                $query->whereNull('effective_from')
                      ->orWhere('effective_from', '<=', $date);
            })
            ->where(function($query) use ($date) {
                $query->whereNull('effective_until')
                      ->orWhere('effective_until', '>=', $date);
            })
            ->orderBy('effective_from', 'desc')
            ->first();
    }

    /**
     * Create a default percentage-based strategy
     */
    public static function createDefaultStrategy(): self
    {
        return self::create([
            'name' => 'Default Monthly Increase',
            'description' => 'Standard 2% monthly increase for fuel prices',
            'strategy_type' => self::TYPE_PERCENTAGE,
            'parameters' => [
                'percentage' => 2.0,
                'min_change' => 1.0
            ],
            'is_active' => true,
            'effective_from' => Carbon::now()
        ]);
    }
}
