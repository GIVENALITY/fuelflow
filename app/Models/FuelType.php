<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'current_price',
        'unit',
        'status'
    ];

    protected $casts = [
        'current_price' => 'decimal:2'
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

    public function inventory()
    {
        return $this->hasOne(FuelInventory::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'active');
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->current_price, 2) . ' per ' . $this->unit;
    }

    public function getAvailableQuantityAttribute()
    {
        return $this->inventory ? $this->inventory->quantity : 0;
    }

    // Methods
    public function updatePrice($newPrice)
    {
        $this->update(['current_price' => $newPrice]);
    }

    public function isInStock()
    {
        return $this->available_quantity > 0;
    }

    public function getLowStockThreshold()
    {
        return $this->inventory ? $this->inventory->low_stock_threshold : 100;
    }

    public function isLowStock()
    {
        return $this->available_quantity <= $this->getLowStockThreshold();
    }
}
