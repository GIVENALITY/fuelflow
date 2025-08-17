<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'station_id',
        'phone',
        'status',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'two_factor_confirmed_at' => 'datetime',
    ];

    // User Roles
    const ROLE_ADMIN = 'admin';
    const ROLE_STATION_MANAGER = 'station_manager';
    const ROLE_FUEL_PUMPER = 'fuel_pumper';
    const ROLE_TREASURY = 'treasury';
    const ROLE_CLIENT = 'client';

    // User Status
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_SUSPENDED = 'suspended';

    // Relationships
    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public function fuelRequests()
    {
        return $this->hasMany(FuelRequest::class, 'assigned_pumper_id');
    }

    public function approvals()
    {
        return $this->hasMany(FuelRequest::class, 'approved_by');
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'uploaded_by');
    }

    // Role-based methods
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isStationManager()
    {
        return $this->role === self::ROLE_STATION_MANAGER;
    }

    public function isFuelPumper()
    {
        return $this->role === self::ROLE_FUEL_PUMPER;
    }

    public function isTreasury()
    {
        return $this->role === self::ROLE_TREASURY;
    }

    public function isClient()
    {
        return $this->role === self::ROLE_CLIENT;
    }

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    // Permission methods
    public function canManageUsers()
    {
        return $this->isAdmin();
    }

    public function canManageStations()
    {
        return $this->isAdmin();
    }

    public function canManagePricing()
    {
        return $this->isAdmin();
    }

    public function canApproveRequests()
    {
        return $this->isStationManager() || $this->isAdmin();
    }

    public function canDispenseFuel()
    {
        return $this->isFuelPumper() || $this->isStationManager();
    }

    public function canVerifyReceipts()
    {
        return $this->isTreasury() || $this->isAdmin();
    }

    public function canViewFinancialReports()
    {
        return $this->isTreasury() || $this->isAdmin();
    }

    // Station-specific methods
    public function getStationRequests()
    {
        if (!$this->station_id) {
            return collect();
        }

        return FuelRequest::where('station_id', $this->station_id);
    }

    public function getPendingApprovals()
    {
        if (!$this->canApproveRequests()) {
            return collect();
        }

        return $this->getStationRequests()
            ->where('status', FuelRequest::STATUS_PENDING)
            ->get();
    }

    public function getAssignedDispensing()
    {
        if (!$this->canDispenseFuel()) {
            return collect();
        }

        return FuelRequest::where('assigned_pumper_id', $this->id)
            ->where('status', FuelRequest::STATUS_APPROVED)
            ->get();
    }

    // Accessors
    public function getRoleDisplayNameAttribute()
    {
        $roles = [
            self::ROLE_ADMIN => 'System Administrator',
            self::ROLE_STATION_MANAGER => 'Station Manager',
            self::ROLE_FUEL_PUMPER => 'Fuel Pumper',
            self::ROLE_TREASURY => 'Treasury Team',
            self::ROLE_CLIENT => 'Corporate Client'
        ];

        return $roles[$this->role] ?? 'Unknown Role';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_ACTIVE => 'bg-success',
            self::STATUS_INACTIVE => 'bg-secondary',
            self::STATUS_SUSPENDED => 'bg-danger'
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }
}
