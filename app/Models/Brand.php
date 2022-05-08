<?php

namespace App\Models;

use App\Traits\BasicTrait;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory, BasicTrait;

    protected $fillable = [
        'name_ar',
        'name_en',
        'image',
        'description_ar',
        'description_en',
        'online',
        'code'
    ];

    public function getNameAttribute()
    {
        return $this->{'name_' . app()->getLocale()};
    }

    public function getDescriptionAttribute()
    {
        return $this->{'description_' . app()->getLocale()};
    }

    public function setImageAttribute($value)
    {
        return $this->attributes['image'] = $this->setImage($value, 'images/brands');
    }

    public function getImageAttribute($value)
    {
        return $this->getImage($value, 'images/brands');
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

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
