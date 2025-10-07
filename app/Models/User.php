<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'business_id',
        'station_id',
        'phone',
        'status',
        'is_active',
        'address',
        'profile_photo',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'onboarding_completed',
        'onboarding_completed_at',
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
        'onboarding_completed' => 'boolean',
        'onboarding_completed_at' => 'datetime',
    ];

    // User Roles
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_ADMIN = 'admin';
    const ROLE_STATION_MANAGER = 'station_manager';
    const ROLE_STATION_ATTENDANT = 'station_attendant';
    const ROLE_TREASURY = 'treasury';
    const ROLE_CLIENT = 'client';

    // User Status
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_SUSPENDED = 'suspended';

    // Relationships
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

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

    public function isStationAttendant()
    {
        return $this->role === self::ROLE_STATION_ATTENDANT;
    }

    public function isSuperAdmin()
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isFuelPumper()
    {
        return $this->role === self::ROLE_STATION_ATTENDANT;
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

    // Permission methods with business isolation
    public function canManageUsers()
    {
        if ($this->isSuperAdmin()) {
            return true; // Super admin can manage all users
        }
        
        if ($this->isAdmin() && $this->business_id) {
            return true; // Business admin can manage users in their business
        }
        
        if ($this->isStationManager() && $this->station_id) {
            return true; // Station manager can manage attendants at their station
        }
        
        return false;
    }

    public function canManageStations()
    {
        return $this->isSuperAdmin() || ($this->isAdmin() && $this->business_id);
    }

    public function canManagePricing()
    {
        return $this->isSuperAdmin() || ($this->isAdmin() && $this->business_id);
    }

    public function canApproveRequests()
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        if ($this->isAdmin() && $this->business_id) {
            return true; // Business admin can approve requests for their business
        }
        
        if ($this->isStationManager() && $this->station_id) {
            return true; // Station manager can approve requests for their station
        }
        
        return false;
    }

    public function canDispenseFuel()
    {
        return $this->isSuperAdmin() || 
               ($this->isAdmin() && $this->business_id) || 
               $this->isStationManager() || 
               $this->isStationAttendant();
    }

    public function canVerifyReceipts()
    {
        return $this->isSuperAdmin() || 
               ($this->isAdmin() && $this->business_id) || 
               $this->isTreasury();
    }

    public function canViewFinancialReports()
    {
        return $this->isSuperAdmin() || 
               ($this->isAdmin() && $this->business_id) || 
               $this->isTreasury();
    }

    // Business-specific methods
    public function canManageBusiness($businessId)
    {
        return $this->isSuperAdmin() || 
               ($this->isAdmin() && $this->business_id === $businessId);
    }

    public function canManageStation($stationId)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        if ($this->isAdmin() && $this->business_id) {
            // Business admin can manage stations in their business
            $station = Station::find($stationId);
            return $station && $station->business_id === $this->business_id;
        }
        
        if ($this->isStationManager()) {
            return $this->station_id === $stationId;
        }
        
        return false;
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
            self::ROLE_STATION_ATTENDANT => 'Station Attendant',
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
