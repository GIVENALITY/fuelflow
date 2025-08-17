<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'station_id', 'fuel_type', 'price', 'effective_date', 'expiry_date', 'is_active', 'created_by'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'effective_date' => 'date',
        'expiry_date' => 'date',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByStation($query, $stationId)
    {
        return $query->where('station_id', $stationId);
    }

    public function scopeByFuelType($query, $fuelType)
    {
        return $query->where('fuel_type', $fuelType);
    }

    public function scopeCurrent($query)
    {
        return $query->where('is_active', true)
                    ->where('effective_date', '<=', now())
                    ->where(function($q) {
                        $q->whereNull('expiry_date')
                          ->orWhere('expiry_date', '>=', now());
                    });
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return 'TZS ' . number_format($this->price, 2);
    }

    public function getStatusDisplayAttribute()
    {
        if (!$this->is_active) {
            return 'Inactive';
        }
        
        if ($this->effective_date > now()) {
            return 'Future';
        }
        
        if ($this->expiry_date && $this->expiry_date < now()) {
            return 'Expired';
        }
        
        return 'Active';
    }

    // Methods
    public function activate()
    {
        // Deactivate other prices for the same station and fuel type
        self::where('station_id', $this->station_id)
            ->where('fuel_type', $this->fuel_type)
            ->where('id', '!=', $this->id)
            ->update(['is_active' => false]);
        
        $this->update(['is_active' => true]);
    }

    public function deactivate()
    {
        $this->update(['is_active' => false]);
    }

    public function isCurrent()
    {
        return $this->is_active && 
               $this->effective_date <= now() && 
               (!$this->expiry_date || $this->expiry_date >= now());
    }

    public static function getCurrentPrice($stationId, $fuelType)
    {
        return self::where('station_id', $stationId)
                   ->where('fuel_type', $fuelType)
                   ->where('is_active', true)
                   ->where('effective_date', '<=', now())
                   ->where(function($query) {
                       $query->whereNull('expiry_date')
                             ->orWhere('expiry_date', '>=', now());
                   })
                   ->first();
    }
}
