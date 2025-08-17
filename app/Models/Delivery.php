<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'fuel_type_id',
        'quantity',
        'delivery_date',
        'delivery_time',
        'address',
        'status',
        'driver_id',
        'vehicle_id',
        'notes'
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'delivery_time' => 'datetime',
        'quantity' => 'decimal:2'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function fuelType()
    {
        return $this->belongsTo(FuelType::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function bill()
    {
        return $this->hasOne(Bill::class);
    }

    // Scopes
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('delivery_date', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('delivery_date', '>=', today());
    }

    // Accessors
    public function getFormattedQuantityAttribute()
    {
        return number_format($this->quantity, 2) . ' L';
    }

    public function getFormattedDeliveryDateTimeAttribute()
    {
        return $this->delivery_date->format('M d, Y') . ' at ' . 
               ($this->delivery_time ? $this->delivery_time->format('h:i A') : 'TBD');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'scheduled' => 'bg-info',
            'in_progress' => 'bg-warning',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger'
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    // Methods
    public function startDelivery()
    {
        $this->update(['status' => 'in_progress']);
    }

    public function completeDelivery()
    {
        $this->update(['status' => 'completed']);
        
        // Create bill for this delivery
        $this->createBill();
    }

    public function cancelDelivery()
    {
        $this->update(['status' => 'cancelled']);
    }

    public function createBill()
    {
        if (!$this->bill) {
            $dueDate = now()->addDays($this->customer->getPaymentTermsDays());
            
            return Bill::create([
                'customer_id' => $this->customer_id,
                'fuel_type_id' => $this->fuel_type_id,
                'quantity' => $this->quantity,
                'unit_price' => $this->fuelType->current_price,
                'amount' => $this->quantity * $this->fuelType->current_price,
                'delivery_date' => $this->delivery_date,
                'due_date' => $dueDate,
                'status' => 'pending',
                'notes' => "Delivery #{$this->id}"
            ]);
        }
    }

    public function isOverdue()
    {
        return $this->delivery_date < today() && $this->status === 'scheduled';
    }

    public function canBeStarted()
    {
        return $this->status === 'scheduled' && $this->delivery_date <= today();
    }

    public function canBeCompleted()
    {
        return $this->status === 'in_progress';
    }
}
