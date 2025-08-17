<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'status',
        'credit_limit',
        'payment_terms'
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeOverdue($query)
    {
        return $query->whereHas('bills', function($q) {
            $q->where('due_date', '<', today())
              ->where('status', '!=', 'paid');
        });
    }

    // Accessors
    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->city}, {$this->state} {$this->zip_code}";
    }

    public function getTotalOutstandingAttribute()
    {
        return $this->bills()
            ->where('status', '!=', 'paid')
            ->sum('amount');
    }

    public function getOverdueAmountAttribute()
    {
        return $this->bills()
            ->where('due_date', '<', today())
            ->where('status', '!=', 'paid')
            ->sum('amount');
    }

    public function getPaymentHistoryAttribute()
    {
        return $this->payments()
            ->latest()
            ->take(10)
            ->get();
    }

    // Methods
    public function isOverdue()
    {
        return $this->bills()
            ->where('due_date', '<', today())
            ->where('status', '!=', 'paid')
            ->exists();
    }

    public function canExceedCreditLimit($amount)
    {
        $currentOutstanding = $this->total_outstanding;
        return ($currentOutstanding + $amount) <= $this->credit_limit;
    }

    public function getPaymentTermsDays()
    {
        return $this->payment_terms ?? 30; // Default 30 days
    }
}
