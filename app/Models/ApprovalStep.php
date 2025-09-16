<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'approval_chain_id',
        'name',
        'description',
        'order',
        'approver_type',
        'approver_id',
        'approver_role',
        'is_required',
        'timeout_hours',
        'conditions'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'conditions' => 'array'
    ];

    // Relationships
    public function approvalChain()
    {
        return $this->belongsTo(ApprovalChain::class);
    }

    public function approver()
    {
        if ($this->approver_type === 'user') {
            return $this->belongsTo(User::class, 'approver_id');
        }

        return null;
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    // Methods
    public function canApprove($user, $fuelRequest)
    {
        // Check if user is the designated approver
        if ($this->approver_type === 'user' && $this->approver_id === $user->id) {
            return true;
        }

        // Check if user has the required role
        if ($this->approver_type === 'role' && $user->role === $this->approver_role) {
            return true;
        }

        // Check if user is station manager and this is a station-specific step
        if (
            $this->approver_type === 'station_manager' &&
            $user->isStationManager() &&
            $user->station_id === $fuelRequest->station_id
        ) {
            return true;
        }

        // Check if user is admin
        if ($this->approver_type === 'admin' && $user->isAdmin()) {
            return true;
        }

        return false;
    }

    public function getApprovers()
    {
        if ($this->approver_type === 'user') {
            return collect([$this->approver]);
        }

        if ($this->approver_type === 'role') {
            return User::where('role', $this->approver_role)->get();
        }

        if ($this->approver_type === 'station_manager') {
            return User::where('role', User::ROLE_STATION_MANAGER)->get();
        }

        if ($this->approver_type === 'admin') {
            return User::where('role', User::ROLE_ADMIN)->get();
        }

        return collect();
    }

    public function isOverdue($fuelRequest)
    {
        if (!$this->timeout_hours) {
            return false;
        }

        $approval = $this->approvals()
            ->where('fuel_request_id', $fuelRequest->id)
            ->first();

        if (!$approval) {
            return $fuelRequest->created_at->addHours($this->timeout_hours)->isPast();
        }

        return false;
    }

    public function getStatus($fuelRequest)
    {
        $approval = $this->approvals()
            ->where('fuel_request_id', $fuelRequest->id)
            ->first();

        if ($approval) {
            return $approval->status;
        }

        if ($this->isOverdue($fuelRequest)) {
            return 'overdue';
        }

        return 'pending';
    }

    public function getStatusBadge($fuelRequest)
    {
        $status = $this->getStatus($fuelRequest);

        $badges = [
            'pending' => 'bg-warning',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            'overdue' => 'bg-danger'
        ];

        return $badges[$status] ?? 'bg-secondary';
    }

    public function getStatusText($fuelRequest)
    {
        $status = $this->getStatus($fuelRequest);

        $texts = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'overdue' => 'Overdue'
        ];

        return $texts[$status] ?? 'Unknown';
    }
}
