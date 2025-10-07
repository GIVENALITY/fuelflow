<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class Client extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'user_id',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'credit_limit',
        'current_balance',
        'payment_terms',
        'status',
        'registration_status',
        'account_manager_id',
        'preferred_stations',
        'special_instructions',
        'tax_id',
        'business_license',
        'contract_start_date',
        'contract_end_date',
        'tin_document_path',
        'brela_certificate_path',
        'business_license_path',
        'director_id_path',
        'contract_sent',
        'contract_sent_at',
        'signed_contract_path',
        'contract_signed_at',
        'approved_by',
        'approved_at',
        'approval_notes'
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'payment_terms' => 'integer',
        'preferred_stations' => 'array',
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
        'contract_sent' => 'boolean',
        'contract_sent_at' => 'datetime',
        'contract_signed_at' => 'datetime',
        'approved_at' => 'datetime'
    ];

    // Client Status
    const STATUS_ACTIVE = 'active';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_CREDIT_HOLD = 'credit_hold';
    const STATUS_PAYMENT_REVIEW = 'payment_review';
    const STATUS_INACTIVE = 'inactive';

    // Registration Status
    const REGISTRATION_STATUS_PENDING = 'pending';
    const REGISTRATION_STATUS_APPROVED = 'approved';
    const REGISTRATION_STATUS_REJECTED = 'rejected';
    const REGISTRATION_STATUS_ACTIVE = 'active';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accountManager()
    {
        return $this->belongsTo(User::class, 'account_manager_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function fuelRequests()
    {
        return $this->hasMany(FuelRequest::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function creditHistory()
    {
        return $this->hasMany(CreditHistory::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeOverdue($query)
    {
        return $query->where('current_balance', '>', 0)
                    ->whereHas('fuelRequests', function($q) {
                        $q->where('due_date', '<', now())
                          ->where('status', '!=', 'paid');
                    });
    }

    public function scopeCreditLimitBreached($query)
    {
        return $query->where('current_balance', '>', 'credit_limit');
    }

    // Accessors
    public function getAvailableCreditAttribute()
    {
        return max(0, $this->credit_limit - $this->current_balance);
    }

    public function getCreditUtilizationAttribute()
    {
        if ($this->credit_limit <= 0) return 0;
        return ($this->current_balance / $this->credit_limit) * 100;
    }

    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->city}, {$this->state} {$this->zip_code}";
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_ACTIVE => 'bg-success',
            self::STATUS_SUSPENDED => 'bg-warning',
            self::STATUS_CREDIT_HOLD => 'bg-danger',
            self::STATUS_PAYMENT_REVIEW => 'bg-info',
            self::STATUS_INACTIVE => 'bg-secondary'
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getStatusDisplayNameAttribute()
    {
        $statuses = [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_SUSPENDED => 'Suspended',
            self::STATUS_CREDIT_HOLD => 'Credit Hold',
            self::STATUS_PAYMENT_REVIEW => 'Payment Review',
            self::STATUS_INACTIVE => 'Inactive'
        ];

        return $statuses[$this->status] ?? 'Unknown';
    }

    public function getOverdueAmountAttribute()
    {
        return $this->fuelRequests()
            ->where('due_date', '<', now())
            ->where('status', '!=', 'paid')
            ->sum('amount');
    }

    public function getDaysOverdueAttribute()
    {
        $oldestOverdue = $this->fuelRequests()
            ->where('due_date', '<', now())
            ->where('status', '!=', 'paid')
            ->orderBy('due_date')
            ->first();

        if (!$oldestOverdue) return 0;

        return now()->diffInDays($oldestOverdue->due_date);
    }

    // Methods
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isOverdue()
    {
        return $this->days_overdue > 0;
    }

    public function hasCreditLimitBreached()
    {
        return $this->current_balance > $this->credit_limit;
    }

    public function canRequestFuel($amount)
    {
        if (!$this->isActive()) {
            return false;
        }

        if ($this->hasCreditLimitBreached()) {
            return false;
        }

        return ($this->current_balance + $amount) <= $this->credit_limit;
    }

    public function updateBalance($amount, $operation = 'add')
    {
        $currentBalance = $this->current_balance;
        
        if ($operation === 'add') {
            $newBalance = $currentBalance + $amount;
        } else {
            $newBalance = $currentBalance - $amount;
        }

        $this->update(['current_balance' => max(0, $newBalance)]);
    }

    public function addCreditHistory($action, $amount, $description = null)
    {
        return $this->creditHistory()->create([
            'action' => $action,
            'amount' => $amount,
            'description' => $description,
            'previous_balance' => $this->current_balance,
            'new_balance' => $this->current_balance + $amount
        ]);
    }

    public function getPaymentTermsDays()
    {
        return $this->payment_terms ?? 30; // Default 30 days
    }

    public function getPreferredStationsList()
    {
        if (!$this->preferred_stations) {
            return collect();
        }

        return Station::whereIn('id', $this->preferred_stations)->get();
    }

    public function getRecentActivity($limit = 10)
    {
        return $this->fuelRequests()
            ->with(['station', 'vehicle'])
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getFleetSummary()
    {
        return [
            'total_vehicles' => $this->vehicles()->count(),
            'active_vehicles' => $this->vehicles()->where('status', 'active')->count(),
            'total_requests' => $this->fuelRequests()->count(),
            'pending_requests' => $this->fuelRequests()->where('status', 'pending')->count(),
            'monthly_consumption' => $this->fuelRequests()
                ->whereMonth('created_at', now()->month)
                ->sum('quantity')
        ];
    }

    public function sendCreditLimitAlert($percentage = 80)
    {
        if ($this->credit_utilization >= $percentage) {
            // Send notification to account manager and client
            // Implementation depends on notification system
        }
    }

    public function autoSuspendIfOverdue($daysThreshold = 30)
    {
        if ($this->days_overdue >= $daysThreshold) {
            $this->update(['status' => self::STATUS_SUSPENDED]);
        }
    }
}
