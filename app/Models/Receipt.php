<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'fuel_request_id', 'client_id', 'station_id', 'uploaded_by', 'verified_by',
        'amount', 'quantity', 'fuel_type', 'receipt_number', 'file_path',
        'status', 'verification_notes', 'verified_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'quantity' => 'decimal:2',
        'verified_at' => 'datetime'
    ];

    // Constants
    const STATUS_PENDING = 'pending';
    const STATUS_VERIFIED = 'verified';
    const STATUS_REJECTED = 'rejected';

    // Relationships
    public function fuelRequest()
    {
        return $this->belongsTo(FuelRequest::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeVerified($query)
    {
        return $query->where('status', self::STATUS_VERIFIED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeByClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeByStation($query, $stationId)
    {
        return $query->where('station_id', $stationId);
    }

    public function scopeByUploader($query, $userId)
    {
        return $query->where('uploaded_by', $userId);
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return 'TZS ' . number_format($this->amount, 2);
    }

    public function getFormattedQuantityAttribute()
    {
        return number_format($this->quantity, 2) . ' L';
    }

    public function getStatusDisplayAttribute()
    {
        switch ($this->status) {
            case self::STATUS_PENDING:
                return 'Pending';
            case self::STATUS_VERIFIED:
                return 'Verified';
            case self::STATUS_REJECTED:
                return 'Rejected';
            default:
                return ucfirst($this->status);
        }
    }

    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case self::STATUS_PENDING:
                return 'bg-gradient-warning';
            case self::STATUS_VERIFIED:
                return 'bg-gradient-success';
            case self::STATUS_REJECTED:
                return 'bg-gradient-danger';
            default:
                return 'bg-gradient-info';
        }
    }

    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    // Methods
    public function verify($verifiedBy, $notes = null)
    {
        $this->update([
            'status' => self::STATUS_VERIFIED,
            'verified_by' => $verifiedBy,
            'verification_notes' => $notes,
            'verified_at' => now()
        ]);
    }

    public function reject($verifiedBy, $notes = null)
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'verified_by' => $verifiedBy,
            'verification_notes' => $notes,
            'verified_at' => now()
        ]);
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isVerified()
    {
        return $this->status === self::STATUS_VERIFIED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function canBeVerified()
    {
        return $this->isPending();
    }

    public function canBeRejected()
    {
        return $this->isPending();
    }
}
