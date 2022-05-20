<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = ['name_ar', 'name_en', 'price', 'user_id'];

    public function getNameAttribute()
    {
        return $this->{'name_' . App::getLocale()};
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
