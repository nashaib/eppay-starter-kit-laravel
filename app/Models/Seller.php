<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seller extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'shop_name',
        'shop_slug',
        'description',
        'logo',
        'banner',
        'phone',
        'address',
        'commission_rate',
        'status',
        'balance',
        'eppay_wallet_address',
        'settings',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'settings' => 'array',
        'commission_rate' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function getTotalSalesAttribute()
    {
        return $this->orders()
            ->where('payment_status', 'completed')
            ->sum('total_amount');
    }

    public function getTotalProductsAttribute()
    {
        return $this->products()->count();
    }

    public function getActiveProductsAttribute()
    {
        return $this->products()->where('status', 'active')->count();
    }
    public function calculatePayout($amount)
    {
        return $amount - ($amount * $this->commission_rate / 100);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }
}