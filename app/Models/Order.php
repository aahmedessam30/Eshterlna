<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'store_id',
        'payment_method_id',
        'shipping_method_id',
        'status',
        'total_price',
        'total_discount',
        'total_tax',
        'total_shipping',
        'total_weight',
        'total_items'
    ];

    protected $with = ['items'];

    protected $dates = [
        'ordered_at', 'shipped_at', 'cancelled_at', 'completed_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'order_items')
            ->withPivot('quantity', 'price', 'discount', 'tax', 'shipping', 'weight')
            ->withTimestamps();
    }

    public function getStatusAttribute($value)
    {
        return $value == 0 ? 'Pending'
            : ($value == 1 ? 'Processing'
                : ($value == 2 ? 'On Delivery'
                    : ($value == 3 ? 'Delivered'
                        : 'Cancelled')));
    }
}
