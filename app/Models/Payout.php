<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'amount',
        'commission_amount',
        'transaction_id',
        'notes',
        'status',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}