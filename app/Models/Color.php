<?php

namespace App\Models;

use App\Traits\BasicTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Color extends Model
{
    use HasFactory, BasicTrait;

    protected $fillable = ['name_ar', 'name_en', 'image', 'colorHash', 'online'];

    public function scopeWhereOnline($query)
    {
        return $query->where('online', 1);
    }

    public function getNameAttribute()
    {
        return $this->{'name_' . App::getLocale()};
    }

    public function setImageAttribute($value)
    {
        return $this->attributes['image'] = $this->setImage($value, 'images/colors');
    }

    public function getImageAttribute($value)
    {
        return $this->getImage($value, 'images/colors');
    }

    public function getOnlineAttribute($value)
    {
        return $value == 0
            ? (App::isLocale('ar') ? 'غير مفعل' : 'Not Active')
            : (App::isLocale('ar') ? 'مفعل' : 'Active');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_colors');
    }
}
