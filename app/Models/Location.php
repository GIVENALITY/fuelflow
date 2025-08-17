<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'country',
        'latitude',
        'longitude',
        'description',
        'type', // depot, station, client_location, etc.
        'status',
        'contact_person',
        'phone',
        'email'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Location Types
    const TYPE_DEPOT = 'depot';
    const TYPE_STATION = 'station';
    const TYPE_CLIENT = 'client_location';
    const TYPE_OTHER = 'other';

    // Status
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    // Relationships
    public function routeStops()
    {
        return $this->hasMany(RouteStop::class);
    }

    public function routes()
    {
        return $this->belongsToMany(Route::class, 'route_stops')
                    ->withPivot('order', 'estimated_time')
                    ->withTimestamps();
    }

    public function fuelRequests()
    {
        return $this->hasMany(FuelRequest::class, 'pickup_location_id');
    }

    public function deliveries()
    {
        return $this->hasMany(FuelRequest::class, 'delivery_location_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Accessors
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->country
        ]);
        
        return implode(', ', $parts);
    }

    public function getCoordinatesAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return $this->latitude . ', ' . $this->longitude;
        }
        return null;
    }
}
