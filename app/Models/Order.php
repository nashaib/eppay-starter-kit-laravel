<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'seller_id',
        'customer_email',
        'customer_name',
        'total_amount',
        'subtotal',
        'shipping_cost',
        'tax_amount',
        'commission_amount',
        'payment_id',
        'status',
        'payment_status',
        'items',
        'shipping_method',
        'shipping_address',
        'billing_address',
        'tracking_number',
        'notes',
    ];

    protected $casts = [
        'items' => 'array',
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'total_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function calculateCommission($rate = null)
    {
        $rate = $rate ?? ($this->seller ? $this->seller->commission_rate : 10);
        return ($this->subtotal * $rate) / 100;
    }

    public function getSellerAmountAttribute()
    {
        return $this->subtotal - $this->commission_amount;
    }

    public function scopeCompleted($query)
    {
        return $query->where('payment_status', 'completed');
    }

    public function scopeBySeller($query, $sellerId)
    {
        return $query->where('seller_id', $sellerId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function canBeReviewed()
    {
        return $this->payment_status === 'completed' && 
               $this->status === 'completed' &&
               $this->created_at->diffInDays(now()) <= 30;
    }
}