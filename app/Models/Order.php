<?php

namespace App\Models;

use Carbon\Carbon;
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
        'total_items',
        'ordered_at',
        'shipped_at',
        'cancelled_at',
        'completed_at'
    ];

    protected $with = ['items'];

    protected $dates = [
        'ordered_at', 'shipped_at', 'cancelled_at', 'completed_at'
    ];

    public function getStatusAttribute($value)
    {
        return $value == 0 ? 'Pending'
            : ($value == 1 ? 'Processing'
                : ($value == 2 ? 'On Delivery'
                    : ($value == 3 ? 'Delivered'
                        : 'Cancelled')));
    }

    public function getOrderedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    public function getShippedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    public function getCancelledAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    public function getCompletedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    public function scopeAuth($query)
    {
        return $query->where('user_id', auth('api')->id());
    }

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
}
