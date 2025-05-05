<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'tx_ref',
        'total_amount',
        'status',
        'order_items',
        'payment_method',
        'shipping_address',
        'customer_email',
        'customer_name',
        'customer_phone',
        'notes',
        'paid_at',
        'checkout_url',
        'product_id',
        'quantity'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'order_items' => 'array',
    ];

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Helper methods
    public function isPaid()
    {
        return $this->paid_at !== null;
    }

    public function getFormattedStatus()
    {
        return ucfirst($this->status);
    }
}
