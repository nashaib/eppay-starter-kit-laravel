<?php

// app/Models/Cart.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'product_id',
        'quantity',
        'price',
        'attributes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'attributes' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->price;
    }
}