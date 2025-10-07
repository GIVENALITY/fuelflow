<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'description',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

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

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Static methods for logging
    public static function log($action, $description = null, $model = null, $oldValues = null, $newValues = null)
    {
        $log = new self([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        if ($model) {
            $log->model_type = get_class($model);
            $log->model_id = $model->id;
        }

        if ($oldValues) {
            $log->old_values = $oldValues;
        }

        if ($newValues) {
            $log->new_values = $newValues;
        }

        $log->save();
        return $log;
    }

    // Accessors
    public function getActionDisplayAttribute()
    {
        $actions = [
            'client_registered' => 'Client Registration',
            'client_approved' => 'Client Approved',
            'client_rejected' => 'Client Rejected',
            'order_created' => 'Order Created',
            'order_approved' => 'Order Approved',
            'order_rejected' => 'Order Rejected',
            'order_completed' => 'Order Completed',
            'payment_submitted' => 'Payment Submitted',
            'payment_verified' => 'Payment Verified',
            'payment_rejected' => 'Payment Rejected',
            'user_created' => 'User Created',
            'user_updated' => 'User Updated',
            'station_created' => 'Station Created',
            'station_updated' => 'Station Updated',
        ];

        return $actions[$this->action] ?? ucwords(str_replace('_', ' ', $this->action));
    }

    public function getFormattedDescriptionAttribute()
    {
        if ($this->description) {
            return $this->description;
        }

        $userName = $this->user ? $this->user->name : 'System';
        
        switch ($this->action) {
            case 'client_registered':
                return "{$userName} registered a new client";
            case 'client_approved':
                return "{$userName} approved a client application";
            case 'client_rejected':
                return "{$userName} rejected a client application";
            case 'order_created':
                return "{$userName} created a new fuel order";
            case 'order_approved':
                return "{$userName} approved a fuel order";
            case 'order_rejected':
                return "{$userName} rejected a fuel order";
            case 'order_completed':
                return "{$userName} completed a fuel order";
            case 'payment_submitted':
                return "{$userName} submitted a payment";
            case 'payment_verified':
                return "{$userName} verified a payment";
            case 'payment_rejected':
                return "{$userName} rejected a payment";
            default:
                return "{$userName} performed action: {$this->action}";
        }
    }
}
