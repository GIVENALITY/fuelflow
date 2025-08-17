<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'fuel_type_id',
        'quantity',
        'unit_price',
        'amount',
        'delivery_date',
        'due_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'due_date' => 'date',
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'amount' => 'decimal:2'
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

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function scopeDueToday($query)
    {
        return $query->where('due_date', today());
    }

    public function scopeOverdueBills($query)
    {
        return $query->where('due_date', '<', today())
                    ->where('status', '!=', 'paid');
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return '$' . number_format($this->amount, 2);
    }

    public function getFormattedQuantityAttribute()
    {
        return number_format($this->quantity, 2) . ' L';
    }

    public function getDaysOverdueAttribute()
    {
        if ($this->status === 'paid') {
            return 0;
        }
        
        return max(0, now()->diffInDays($this->due_date, false));
    }

    public function getIsOverdueAttribute()
    {
        return $this->due_date < today() && $this->status !== 'paid';
    }

    // Mutators
    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = $value;
    }

    // Methods
    public function markAsPaid()
    {
        $this->update(['status' => 'paid']);
    }

    public function markAsOverdue()
    {
        if ($this->due_date < today() && $this->status !== 'paid') {
            $this->update(['status' => 'overdue']);
        }
    }

    public function calculateLateFees()
    {
        if ($this->is_overdue) {
            $daysOverdue = $this->days_overdue;
            $lateFeeRate = 0.05; // 5% per month
            $monthlyRate = $lateFeeRate / 30; // Daily rate
            
            return $this->amount * $monthlyRate * $daysOverdue;
        }
        
        return 0;
    }
}
