<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'approval_step_id',
        'fuel_request_id',
        'approver_id',
        'status',
        'comments',
        'approved_at',
        'rejected_at'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // Relationships
    public function approvalStep()
    {
        return $this->belongsTo(ApprovalStep::class);
    }

    public function fuelRequest()
    {
        return $this->belongsTo(FuelRequest::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    // Methods
    public function approve($approver, $comments = null)
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'approver_id' => $approver->id,
            'comments' => $comments,
            'approved_at' => now()
        ]);

        // Move to next step or complete approval
        $this->approvalStep->approvalChain->moveToNextStep($this->fuelRequest);
    }

    public function reject($approver, $comments = null)
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'approver_id' => $approver->id,
            'comments' => $comments,
            'rejected_at' => now()
        ]);

        // Mark fuel request as rejected
        $this->fuelRequest->update([
            'status' => FuelRequest::STATUS_REJECTED,
            'current_approval_step_id' => null
        ]);
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function getStatusBadge()
    {
        $badges = [
            self::STATUS_PENDING => 'bg-warning',
            self::STATUS_APPROVED => 'bg-success',
            self::STATUS_REJECTED => 'bg-danger'
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getStatusText()
    {
        $texts = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected'
        ];

        return $texts[$this->status] ?? 'Unknown';
    }
}
