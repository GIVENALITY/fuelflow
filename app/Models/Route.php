<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_location_id',
        'end_location_id',
        'total_distance',
        'estimated_duration',
        'status',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'total_distance' => 'decimal:2',
        'estimated_duration' => 'integer', // in minutes
        'is_active' => 'boolean',
    ];

    // Route Status
    const STATUS_DRAFT = 'draft';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_MAINTENANCE = 'maintenance';

    // Relationships
    public function startLocation()
    {
        return $this->belongsTo(Location::class, 'start_location_id');
    }

    public function endLocation()
    {
        return $this->belongsTo(Location::class, 'end_location_id');
    }

    public function routeStops()
    {
        return $this->hasMany(RouteStop::class)->orderBy('order');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'route_stops')
                    ->withPivot('order', 'estimated_time')
                    ->withTimestamps()
                    ->orderBy('route_stops.order');
    }

    public function fuelRequests()
    {
        return $this->hasMany(FuelRequest::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)->where('is_active', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Accessors
    public function getFormattedDurationAttribute()
    {
        if (!$this->estimated_duration) {
            return 'N/A';
        }

        $hours = floor($this->estimated_duration / 60);
        $minutes = $this->estimated_duration % 60;

        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }

        return $minutes . 'm';
    }

    public function getFormattedDistanceAttribute()
    {
        if (!$this->total_distance) {
            return 'N/A';
        }

        return number_format($this->total_distance, 1) . ' km';
    }

    public function getStopsCountAttribute()
    {
        return $this->routeStops()->count();
    }

    // Methods
    public function addLocation(Location $location, $order = null, $estimatedTime = null)
    {
        if ($order === null) {
            $order = $this->routeStops()->max('order') + 1;
        }

        return $this->routeStops()->create([
            'location_id' => $location->id,
            'order' => $order,
            'estimated_time' => $estimatedTime
        ]);
    }

    public function reorderStops($stopIds)
    {
        foreach ($stopIds as $index => $stopId) {
            $this->routeStops()->where('id', $stopId)->update(['order' => $index + 1]);
        }
    }
}
