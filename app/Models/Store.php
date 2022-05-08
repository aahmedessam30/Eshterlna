<?php

namespace App\Models;

use App\Traits\BasicTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Store extends Model
{
    use HasFactory, BasicTrait;

    protected $fillable = [
        'name_ar',
        'name_en',
        'image',
        'phone',
        'email',
        'address_ar',
        'address_en',
        'lat',
        'lng',
        'online',
        'city_id'
    ];

    public function getNameAttribute()
    {
        return $this->{'name_' . App::getLocale()};
    }

    public function getAddressAttribute()
    {
        return $this->{'address_' . App::getLocale()};
    }

    public function setImageAttribute($value)
    {
        return $this->attributes['image'] = $this->setImage($value, 'images/stores');
    }

    public function getImageAttribute($value)
    {
        return $this->getImage($value, 'images/stores');
    }

    public function getOnlineAttribute($value)
    {
        return $value == 0
            ? (App::isLocale('ar') ? 'غير مفعل' : 'Not Active')
            : (App::isLocale('ar') ? 'مفعل' : 'Active');
    }

    public function scopeWhereOnline($query)
    {
        return $query->where('online', 1);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function country()
    {
        return $this->hasOneThrough(Country::class, City::class);
    }

    public function categories()
    {
        return $this->hasManyThrough(Category::class, Item::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class)->withTimestamps()->withPivot('quantity');
    }

    public function merchant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
