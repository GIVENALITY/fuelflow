<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'location_id',
        'phone',
        'email',
        'manager_id',
        'status',
        'capacity_diesel',
        'capacity_petrol',
        'current_diesel_level',
        'current_petrol_level',
        'operating_hours',
        'timezone'
    ];

    protected $casts = [
        'capacity_diesel' => 'decimal:2',
        'capacity_petrol' => 'decimal:2',
        'current_diesel_level' => 'decimal:2',
        'current_petrol_level' => 'decimal:2',
        'operating_hours' => 'array'
    ];

    // Station Status
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_MAINTENANCE = 'maintenance';
    const STATUS_CLOSED = 'closed';

    // Relationships
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function staff()
    {
        return $this->hasMany(User::class);
    }

    public function fuelRequests()
    {
        return $this->hasMany(FuelRequest::class);
    }

    public function fuelInventory()
    {
        return $this->hasMany(FuelInventory::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function fuelPrices()
    {
        return $this->hasMany(FuelPrice::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeOperational($query)
    {
        return $query->whereIn('status', [self::STATUS_ACTIVE, self::STATUS_MAINTENANCE]);
    }

    // Accessors
    public function getFullAddressAttribute()
    {
        return $this->location ? $this->location->full_address : 'No location assigned';
    }

    public function getDieselUtilizationAttribute()
    {
        if ($this->capacity_diesel <= 0) return 0;
        return ($this->current_diesel_level / $this->capacity_diesel) * 100;
    }

    public function getPetrolUtilizationAttribute()
    {
        if ($this->capacity_petrol <= 0) return 0;
        return ($this->current_petrol_level / $this->capacity_petrol) * 100;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_ACTIVE => 'bg-success',
            self::STATUS_INACTIVE => 'bg-secondary',
            self::STATUS_MAINTENANCE => 'bg-warning',
            self::STATUS_CLOSED => 'bg-danger'
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getStatusDisplayNameAttribute()
    {
        $statuses = [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_MAINTENANCE => 'Maintenance',
            self::STATUS_CLOSED => 'Closed'
        ];

        return $statuses[$this->status] ?? 'Unknown';
    }

    // Methods
    public function isOperational()
    {
        return in_array($this->status, [self::STATUS_ACTIVE, self::STATUS_MAINTENANCE]);
    }

    public function hasAvailableFuel($fuelType, $quantity)
    {
        $currentLevel = $this->getCurrentFuelLevel($fuelType);
        return $currentLevel >= $quantity;
    }

    public function getCurrentFuelLevel($fuelType)
    {
        return match(strtolower($fuelType)) {
            'diesel' => $this->current_diesel_level,
            'petrol' => $this->current_petrol_level,
            default => 0
        };
    }

    public function updateFuelLevel($fuelType, $quantity, $operation = 'dispense')
    {
        $currentLevel = $this->getCurrentFuelLevel($fuelType);
        
        if ($operation === 'dispense') {
            $newLevel = $currentLevel - $quantity;
        } else {
            $newLevel = $currentLevel + $quantity;
        }

        $newLevel = max(0, $newLevel); // Ensure non-negative

        if (strtolower($fuelType) === 'diesel') {
            $this->update(['current_diesel_level' => $newLevel]);
        } else {
            $this->update(['current_petrol_level' => $newLevel]);
        }
    }

    public function getLowStockThreshold($fuelType)
    {
        $capacity = strtolower($fuelType) === 'diesel' ? $this->capacity_diesel : $this->capacity_petrol;
        return $capacity * 0.2; // 20% threshold
    }

    public function isLowStock($fuelType)
    {
        $currentLevel = $this->getCurrentFuelLevel($fuelType);
        $threshold = $this->getLowStockThreshold($fuelType);
        return $currentLevel <= $threshold;
    }

    public function getTodayRequests()
    {
        return $this->fuelRequests()
            ->whereDate('created_at', today())
            ->get();
    }

    public function getPendingRequests()
    {
        return $this->fuelRequests()
            ->where('status', FuelRequest::STATUS_PENDING)
            ->get();
    }

    public function getActiveStaff()
    {
        return $this->staff()
            ->where('status', User::STATUS_ACTIVE)
            ->get();
    }
}
