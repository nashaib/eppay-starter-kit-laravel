<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

   protected $fillable = [
    'name',
    'email',
    'password',
    'phone',
    'address',
    'city',
    'state',
    'country',
    'postal_code',
    'date_of_birth',
    'avatar',
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function wishlistedProducts()
    {
        return $this->belongsToMany(Product::class, 'wishlists');
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]);
        
        return implode(', ', $parts);
    }

    public function getTotalOrdersAttribute()
    {
        return $this->orders()->count();
    }

    public function getTotalSpentAttribute()
    {
        return $this->orders()
            ->where('payment_status', 'completed')
            ->sum('total_amount');
    }

    public function hasWishlisted($productId)
    {
        return $this->wishlist()->where('product_id', $productId)->exists();
    }

    public function getCartItemsCountAttribute()
    {
        return $this->cart()->sum('quantity');
    }

    public function getCartTotalAttribute()
    {
        return $this->cart()->with('product')->get()->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }
}