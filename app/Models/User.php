<?php

namespace App\Models;

use App\Traits\BasicTrait;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, BasicTrait;

    protected $fillable = [
        'fname',
        'lname',
        'email',
        'phone',
        'username',
        'password',
        'image',
        'device_key',
        'address',
        'type',
        'status',
        'city_id',
        'country_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getNameAttribute()
    {
        return $this->fname . ' ' . $this->lname;
    }

    public function setPasswordAttribute($value)
    {
        if (Hash::needsRehash($value)) {
            return $this->attributes['password'] = Hash::make($value);
        }
    }

    public function setImageAttribute($value)
    {
        return $this->attributes['image'] = $this->setImage($value, 'images/users');
    }

    public function getImageAttribute($value)
    {
        return $this->getImage($value, 'images/users');
    }

    public function scopeMerchant($query)
    {
        return $query->where('type', 'merchant');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function stores()
    {
        return $this->hasMany(Store::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
