<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'plate_number',
        'vehicle_type',
        'make',
        'model',
        'year',
        'fuel_type',
        'tank_capacity',
        'current_mileage',
        'driver_name',
        'driver_phone',
        'status',
        'registration_expiry',
        'insurance_expiry',
        'last_service_date',
        'next_service_date',
        'notes'
    ];

    protected $casts = [
        'tank_capacity' => 'decimal:2',
        'current_mileage' => 'integer',
        'registration_expiry' => 'date',
        'insurance_expiry' => 'date',
        'last_service_date' => 'date',
        'next_service_date' => 'date'
    ];

    // Vehicle Status
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_MAINTENANCE = 'maintenance';
    const STATUS_RETIRED = 'retired';

    // Vehicle Types
    const TYPE_TRUCK = 'truck';
    const TYPE_VAN = 'van';
    const TYPE_CAR = 'car';
    const TYPE_BUS = 'bus';
    const TYPE_MOTORCYCLE = 'motorcycle';

    // Fuel Types
    const FUEL_DIESEL = 'diesel';
    const FUEL_PETROL = 'petrol';
    const FUEL_HYBRID = 'hybrid';
    const FUEL_ELECTRIC = 'electric';

    // Relationships
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function fuelRequests()
    {
        return $this->hasMany(FuelRequest::class);
    }

    public function fuelHistory()
    {
        return $this->hasMany(FuelHistory::class);
    }

    public function maintenanceRecords()
    {
        return $this->hasMany(MaintenanceRecord::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeByFuelType($query, $fuelType)
    {
        return $query->where('fuel_type', $fuelType);
    }

    public function scopeByClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where(function($q) use ($days) {
            $q->where('registration_expiry', '<=', now()->addDays($days))
              ->orWhere('insurance_expiry', '<=', now()->addDays($days));
        });
    }

    public function scopeDueForService($query, $days = 7)
    {
        return $query->where('next_service_date', '<=', now()->addDays($days));
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_ACTIVE => 'bg-success',
            self::STATUS_INACTIVE => 'bg-secondary',
            self::STATUS_MAINTENANCE => 'bg-warning',
            self::STATUS_RETIRED => 'bg-danger'
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getStatusDisplayNameAttribute()
    {
        $statuses = [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_MAINTENANCE => 'Maintenance',
            self::STATUS_RETIRED => 'Retired'
        ];

        return $statuses[$this->status] ?? 'Unknown';
    }

    public function getVehicleTypeDisplayNameAttribute()
    {
        $types = [
            self::TYPE_TRUCK => 'Truck',
            self::TYPE_VAN => 'Van',
            self::TYPE_CAR => 'Car',
            self::TYPE_BUS => 'Bus',
            self::TYPE_MOTORCYCLE => 'Motorcycle'
        ];

        return $types[$this->vehicle_type] ?? 'Unknown';
    }

    public function getFuelTypeDisplayNameAttribute()
    {
        $types = [
            self::FUEL_DIESEL => 'Diesel',
            self::FUEL_PETROL => 'Petrol',
            self::FUEL_HYBRID => 'Hybrid',
            self::FUEL_ELECTRIC => 'Electric'
        ];

        return $types[$this->fuel_type] ?? 'Unknown';
    }

    public function getFullDescriptionAttribute()
    {
        return "{$this->year} {$this->make} {$this->model} ({$this->plate_number})";
    }

    public function getFormattedTankCapacityAttribute()
    {
        return number_format($this->tank_capacity, 2) . ' L';
    }

    public function getFormattedCurrentMileageAttribute()
    {
        return number_format($this->current_mileage) . ' km';
    }

    public function getIsExpiringSoonAttribute()
    {
        $days = 30;
        return $this->registration_expiry <= now()->addDays($days) || 
               $this->insurance_expiry <= now()->addDays($days);
    }

    public function getIsDueForServiceAttribute()
    {
        return $this->next_service_date <= now()->addDays(7);
    }

    public function getMonthlyFuelConsumptionAttribute()
    {
        return $this->fuelRequests()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('quantity_dispensed');
    }

    public function getAverageFuelConsumptionAttribute()
    {
        $requests = $this->fuelRequests()
            ->whereNotNull('quantity_dispensed')
            ->where('created_at', '>=', now()->subMonths(3))
            ->get();

        if ($requests->isEmpty()) return 0;

        return $requests->avg('quantity_dispensed');
    }

    // Methods
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function canRequestFuel()
    {
        return $this->isActive() && $this->fuel_type !== self::FUEL_ELECTRIC;
    }

    public function getFuelEfficiency()
    {
        $requests = $this->fuelRequests()
            ->whereNotNull('quantity_dispensed')
            ->where('created_at', '>=', now()->subMonths(3))
            ->get();

        if ($requests->isEmpty()) return null;

        $totalFuel = $requests->sum('quantity_dispensed');
        $totalMileage = $requests->sum('current_mileage');

        if ($totalMileage <= 0) return null;

        return $totalMileage / $totalFuel; // km/L
    }

    public function getLastFuelDate()
    {
        return $this->fuelRequests()
            ->whereNotNull('dispensed_at')
            ->latest('dispensed_at')
            ->first();
    }

    public function getFuelHistory($limit = 10)
    {
        return $this->fuelRequests()
            ->whereNotNull('dispensed_at')
            ->with(['station', 'dispensedBy'])
            ->latest('dispensed_at')
            ->take($limit)
            ->get();
    }

    public function updateMileage($newMileage)
    {
        if ($newMileage > $this->current_mileage) {
            $this->update(['current_mileage' => $newMileage]);
        }
    }

    public function scheduleService($nextServiceDate)
    {
        $this->update([
            'last_service_date' => now(),
            'next_service_date' => $nextServiceDate
        ]);
    }

    public function markForMaintenance()
    {
        $this->update(['status' => self::STATUS_MAINTENANCE]);
    }

    public function activate()
    {
        $this->update(['status' => self::STATUS_ACTIVE]);
    }

    public function deactivate()
    {
        $this->update(['status' => self::STATUS_INACTIVE]);
    }

    public function retire()
    {
        $this->update(['status' => self::STATUS_RETIRED]);
    }

    public function getMaintenanceAlerts()
    {
        $alerts = [];

        if ($this->is_expiring_soon) {
            $alerts[] = 'Registration or insurance expiring soon';
        }

        if ($this->is_due_for_service) {
            $alerts[] = 'Due for service';
        }

        if ($this->status === self::STATUS_MAINTENANCE) {
            $alerts[] = 'Currently in maintenance';
        }

        return $alerts;
    }
}
