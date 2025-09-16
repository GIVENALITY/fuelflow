<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ApiToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'token',
        'last_used_at',
        'expires_at'
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    // Methods
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isActive()
    {
        return !$this->isExpired();
    }

    public function updateLastUsed()
    {
        $this->update(['last_used_at' => now()]);
    }

    public function extendExpiry($days = 30)
    {
        $this->update(['expires_at' => now()->addDays($days)]);
    }

    public function revoke()
    {
        $this->update(['expires_at' => now()]);
    }
}
