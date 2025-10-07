<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class Business extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'business_code',
        'registration_number',
        'email',
        'phone',
        'address',
        'contact_person',
        'status',
        'contract_signed',
        'contract_uploaded_at',
        'approved_at',
        'approved_by',
        'notes'
    ];

    protected $casts = [
        'contract_signed' => 'boolean',
        'contract_uploaded_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    // Business Status
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_INACTIVE = 'inactive';

    // Relationships
    public function admin()
    {
        return $this->hasOne(User::class, 'business_id')->where('role', User::ROLE_ADMIN);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function stations()
    {
        return $this->hasMany(Station::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function admin()
    {
        return $this->hasOne(User::class)->where('role', User::ROLE_ADMIN);
    }

    public function fuelRequests()
    {
        return $this->hasManyThrough(FuelRequest::class, Station::class, 'business_id', 'station_id');
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, FuelRequest::class, 'station_id', 'fuel_request_id')
            ->join('stations', 'fuel_requests.station_id', '=', 'stations.id')
            ->where('stations.business_id', $this->id);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    // Business Logic Methods
    public function isActive()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function canCreateStations()
    {
        return $this->isActive() && $this->contract_signed;
    }

    public function canManageUsers()
    {
        return $this->isActive();
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_PENDING => 'bg-warning',
            self::STATUS_APPROVED => 'bg-success',
            self::STATUS_SUSPENDED => 'bg-danger',
            self::STATUS_INACTIVE => 'bg-secondary'
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getStatusDisplayNameAttribute()
    {
        $statuses = [
            self::STATUS_PENDING => 'Pending Approval',
            self::STATUS_APPROVED => 'Active',
            self::STATUS_SUSPENDED => 'Suspended',
            self::STATUS_INACTIVE => 'Inactive'
        ];

        return $statuses[$this->status] ?? 'Unknown';
    }

    // Statistics Methods
    public function getTotalStations()
    {
        return $this->stations()->count();
    }

    public function getTotalClients()
    {
        return $this->clients()->count();
    }

    public function getTotalUsers()
    {
        return $this->users()->count();
    }

    public function getMonthlyFuelSales()
    {
        return $this->fuelRequests()
            ->where('status', FuelRequest::STATUS_DISPENSED)
            ->whereMonth('dispensed_at', now()->month)
            ->whereYear('dispensed_at', now()->year)
            ->sum('quantity_dispensed');
    }

    public function getTotalRevenue()
    {
        return $this->payments()
            ->where('status', 'completed')
            ->sum('amount');
    }
}
