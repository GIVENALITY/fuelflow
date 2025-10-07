<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class FuelRequest extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'client_id',
        'vehicle_id',
        'station_id',
        'fuel_type',
        'quantity_requested',
        'quantity_dispensed',
        'unit_price',
        'total_amount',
        'request_date',
        'preferred_date',
        'due_date',
        'status',
        'urgency_level',
        'special_instructions',
        'approved_by',
        'approved_at',
        'assigned_pumper_id',
        'dispensed_by',
        'dispensed_at',
        'receipt_id',
        'rejection_reason',
        'notes'
    ];

    protected $casts = [
        'quantity_requested' => 'decimal:2',
        'quantity_dispensed' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'request_date' => 'datetime',
        'preferred_date' => 'date',
        'due_date' => 'date',
        'approved_at' => 'datetime',
        'dispensed_at' => 'datetime'
    ];

    // Request Status
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_DISPENSED = 'dispensed';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Urgency Levels
    const URGENCY_STANDARD = 'standard';
    const URGENCY_PRIORITY = 'priority';
    const URGENCY_EMERGENCY = 'emergency';

    // Fuel Types
    const FUEL_DIESEL = 'diesel';
    const FUEL_PETROL = 'petrol';

    // Relationships
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function assignedPumper()
    {
        return $this->belongsTo(User::class, 'assigned_pumper_id');
    }

    public function dispensedBy()
    {
        return $this->belongsTo(User::class, 'dispensed_by');
    }

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeDispensed($query)
    {
        return $query->where('status', self::STATUS_DISPENSED);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('request_date', today());
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', today())
                    ->whereNotIn('status', [self::STATUS_COMPLETED, self::STATUS_CANCELLED]);
    }

    public function scopeByStation($query, $stationId)
    {
        return $query->where('station_id', $stationId);
    }

    public function scopeByClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeUrgent($query)
    {
        return $query->whereIn('urgency_level', [self::URGENCY_PRIORITY, self::URGENCY_EMERGENCY]);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_PENDING => 'bg-warning',
            self::STATUS_APPROVED => 'bg-info',
            self::STATUS_REJECTED => 'bg-danger',
            self::STATUS_IN_PROGRESS => 'bg-primary',
            self::STATUS_DISPENSED => 'bg-success',
            self::STATUS_COMPLETED => 'bg-success',
            self::STATUS_CANCELLED => 'bg-secondary'
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getStatusDisplayNameAttribute()
    {
        $statuses = [
            self::STATUS_PENDING => 'Pending Approval',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_DISPENSED => 'Dispensed',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled'
        ];

        return $statuses[$this->status] ?? 'Unknown';
    }

    public function getUrgencyBadgeAttribute()
    {
        $badges = [
            self::URGENCY_STANDARD => 'bg-secondary',
            self::URGENCY_PRIORITY => 'bg-warning',
            self::URGENCY_EMERGENCY => 'bg-danger'
        ];

        return $badges[$this->urgency_level] ?? 'bg-secondary';
    }

    public function getFormattedQuantityRequestedAttribute()
    {
        return number_format($this->quantity_requested, 2) . ' L';
    }

    public function getFormattedQuantityDispensedAttribute()
    {
        return $this->quantity_dispensed ? number_format($this->quantity_dispensed, 2) . ' L' : 'N/A';
    }

    public function getFormattedTotalAmountAttribute()
    {
        return '$' . number_format($this->total_amount, 2);
    }

    public function getIsOverdueAttribute()
    {
        return $this->due_date < today() && !in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_CANCELLED]);
    }

    public function getDaysOverdueAttribute()
    {
        if (!$this->is_overdue) return 0;
        return now()->diffInDays($this->due_date);
    }

    public function getIsUrgentAttribute()
    {
        return in_array($this->urgency_level, [self::URGENCY_PRIORITY, self::URGENCY_EMERGENCY]);
    }

    // Methods
    public function canBeApproved()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function canBeRejected()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function canBeDispensed()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function canBeCompleted()
    {
        return $this->status === self::STATUS_DISPENSED;
    }

    public function approve($approvedBy, $notes = null)
    {
        if (!$this->canBeApproved()) {
            throw new \Exception('Request cannot be approved in current status');
        }

        $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_by' => $approvedBy->id,
            'approved_at' => now(),
            'notes' => $notes
        ]);

        // Update client balance
        $this->client->updateBalance($this->total_amount, 'add');
        
        // Create notification
        $this->createNotification('request_approved', $this->client->user_id);
    }

    public function reject($rejectedBy, $reason)
    {
        if (!$this->canBeRejected()) {
            throw new \Exception('Request cannot be rejected in current status');
        }

        $this->update([
            'status' => self::STATUS_REJECTED,
            'approved_by' => $rejectedBy->id,
            'approved_at' => now(),
            'rejection_reason' => $reason
        ]);

        // Create notification
        $this->createNotification('request_rejected', $this->client->user_id);
    }

    public function assignPumper($pumper)
    {
        if (!$this->canBeDispensed()) {
            throw new \Exception('Request cannot be assigned in current status');
        }

        $this->update([
            'assigned_pumper_id' => $pumper->id,
            'status' => self::STATUS_IN_PROGRESS
        ]);

        // Create notification
        $this->createNotification('request_assigned', $pumper->id);
    }

    public function dispense($pumper, $quantityDispensed, $notes = null)
    {
        if (!$this->canBeDispensed()) {
            throw new \Exception('Request cannot be dispensed in current status');
        }

        $this->update([
            'status' => self::STATUS_DISPENSED,
            'dispensed_by' => $pumper->id,
            'dispensed_at' => now(),
            'quantity_dispensed' => $quantityDispensed,
            'notes' => $notes
        ]);

        // Update station fuel level
        $this->station->updateFuelLevel($this->fuel_type, $quantityDispensed, 'dispense');

        // Create notification for manager
        $this->createNotification('fuel_dispensed', $this->station->manager_id);
    }

    public function complete($receiptId = null)
    {
        if (!$this->canBeCompleted()) {
            throw new \Exception('Request cannot be completed in current status');
        }

        $this->update([
            'status' => self::STATUS_COMPLETED,
            'receipt_id' => $receiptId
        ]);

        // Create notification
        $this->createNotification('request_completed', $this->client->user_id);
    }

    public function cancel($cancelledBy, $reason = null)
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'rejection_reason' => $reason
        ]);

        // Refund client balance if already charged
        if ($this->status === self::STATUS_APPROVED) {
            $this->client->updateBalance($this->total_amount, 'subtract');
        }

        // Create notification
        $this->createNotification('request_cancelled', $this->client->user_id);
    }

    public function validateCreditLimit()
    {
        return $this->client->canRequestFuel($this->total_amount);
    }

    public function validateStationCapacity()
    {
        return $this->station->hasAvailableFuel($this->fuel_type, $this->quantity_requested);
    }

    public function createNotification($type, $userId)
    {
        return $this->notifications()->create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $this->getNotificationTitle($type),
            'message' => $this->getNotificationMessage($type),
            'data' => [
                'request_id' => $this->id,
                'client_id' => $this->client_id,
                'station_id' => $this->station_id
            ]
        ]);
    }

    private function getNotificationTitle($type)
    {
        $titles = [
            'request_approved' => 'Fuel Request Approved',
            'request_rejected' => 'Fuel Request Rejected',
            'request_assigned' => 'Fuel Request Assigned',
            'fuel_dispensed' => 'Fuel Dispensed',
            'request_completed' => 'Fuel Request Completed',
            'request_cancelled' => 'Fuel Request Cancelled'
        ];

        return $titles[$type] ?? 'Fuel Request Update';
    }

    private function getNotificationMessage($type)
    {
        $messages = [
            'request_approved' => "Your fuel request for {$this->vehicle->plate_number} has been approved.",
            'request_rejected' => "Your fuel request for {$this->vehicle->plate_number} has been rejected.",
            'request_assigned' => "You have been assigned to dispense fuel for request #{$this->id}.",
            'fuel_dispensed' => "Fuel has been dispensed for request #{$this->id}.",
            'request_completed' => "Your fuel request for {$this->vehicle->plate_number} has been completed.",
            'request_cancelled' => "Your fuel request for {$this->vehicle->plate_number} has been cancelled."
        ];

        return $messages[$type] ?? 'Your fuel request has been updated.';
    }
}
