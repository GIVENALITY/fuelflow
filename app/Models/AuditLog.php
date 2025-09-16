<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'description'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array'
    ];

    // Action types
    const ACTION_CREATED = 'created';
    const ACTION_UPDATED = 'updated';
    const ACTION_DELETED = 'deleted';
    const ACTION_VIEWED = 'viewed';
    const ACTION_APPROVED = 'approved';
    const ACTION_REJECTED = 'rejected';
    const ACTION_DISPENSED = 'dispensed';
    const ACTION_VERIFIED = 'verified';
    const ACTION_LOGIN = 'login';
    const ACTION_LOGOUT = 'logout';
    const ACTION_EXPORTED = 'exported';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function model()
    {
        return $this->morphTo('model', 'model_type', 'model_id');
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByModel($query, $modelType, $modelId = null)
    {
        $query = $query->where('model_type', $modelType);

        if ($modelId) {
            $query->where('model_id', $modelId);
        }

        return $query;
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }

    // Methods
    public static function log($action, $model = null, $oldValues = null, $newValues = null, $description = null)
    {
        $user = auth()->user();

        return self::create([
            'user_id' => $user ? $user->id : null,
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'description' => $description
        ]);
    }

    public function getActionBadgeAttribute()
    {
        $badges = [
            self::ACTION_CREATED => 'bg-success',
            self::ACTION_UPDATED => 'bg-info',
            self::ACTION_DELETED => 'bg-danger',
            self::ACTION_VIEWED => 'bg-secondary',
            self::ACTION_APPROVED => 'bg-success',
            self::ACTION_REJECTED => 'bg-danger',
            self::ACTION_DISPENSED => 'bg-primary',
            self::ACTION_VERIFIED => 'bg-success',
            self::ACTION_LOGIN => 'bg-info',
            self::ACTION_LOGOUT => 'bg-warning',
            self::ACTION_EXPORTED => 'bg-dark'
        ];

        return $badges[$this->action] ?? 'bg-secondary';
    }

    public function getActionDisplayNameAttribute()
    {
        $names = [
            self::ACTION_CREATED => 'Created',
            self::ACTION_UPDATED => 'Updated',
            self::ACTION_DELETED => 'Deleted',
            self::ACTION_VIEWED => 'Viewed',
            self::ACTION_APPROVED => 'Approved',
            self::ACTION_REJECTED => 'Rejected',
            self::ACTION_DISPENSED => 'Dispensed',
            self::ACTION_VERIFIED => 'Verified',
            self::ACTION_LOGIN => 'Login',
            self::ACTION_LOGOUT => 'Logout',
            self::ACTION_EXPORTED => 'Exported'
        ];

        return $names[$this->action] ?? ucfirst($this->action);
    }

    public function getModelDisplayNameAttribute()
    {
        if (!$this->model_type) {
            return 'System';
        }

        $modelName = class_basename($this->model_type);

        $names = [
            'FuelRequest' => 'Fuel Request',
            'Client' => 'Client',
            'Station' => 'Station',
            'Vehicle' => 'Vehicle',
            'User' => 'User',
            'Payment' => 'Payment',
            'Receipt' => 'Receipt'
        ];

        return $names[$modelName] ?? $modelName;
    }

    public function getChangesAttribute()
    {
        if (!$this->old_values || !$this->new_values) {
            return [];
        }

        $changes = [];

        foreach ($this->new_values as $key => $newValue) {
            $oldValue = $this->old_values[$key] ?? null;

            if ($oldValue !== $newValue) {
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue
                ];
            }
        }

        return $changes;
    }

    public function hasChanges()
    {
        return !empty($this->changes);
    }
}
