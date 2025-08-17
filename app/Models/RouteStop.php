<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteStop extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'location_id',
        'order',
        'estimated_time', // time from previous stop in minutes
        'notes'
    ];

    protected $casts = [
        'estimated_time' => 'integer',
    ];

    // Relationships
    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // Accessors
    public function getFormattedEstimatedTimeAttribute()
    {
        if (!$this->estimated_time) {
            return 'N/A';
        }

        $hours = floor($this->estimated_time / 60);
        $minutes = $this->estimated_time % 60;

        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }

        return $minutes . 'm';
    }

    // Scopes
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Methods
    public function getNextStop()
    {
        return $this->route->routeStops()
                          ->where('order', '>', $this->order)
                          ->orderBy('order')
                          ->first();
    }

    public function getPreviousStop()
    {
        return $this->route->routeStops()
                          ->where('order', '<', $this->order)
                          ->orderBy('order', 'desc')
                          ->first();
    }
}
