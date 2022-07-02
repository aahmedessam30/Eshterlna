<?php

namespace App\Models;

use App\Traits\BasicTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Category extends Model
{
    use HasFactory, BasicTrait;

    protected $fillable = [
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'image',
        'online',
        'category_id',
        'user_id',
    ];

    public function getNameAttribute()
    {
        return $this->{'name_' . App::getLocale()};
    }

    public function getDescriptionAttribute()
    {
        return $this->{'description_' . App::getLocale()};
    }

    public function setImageAttribute($value)
    {
        return $this->attributes['image'] = $this->setImage($value, 'images/categories');
    }

    public function getImageAttribute($value)
    {
        return $this->getImage($value, 'images/categories');
    }

    public function getOnlineAttribute($value)
    {
        return $value == 0
            ? (App::isLocale('ar') ? 'غير مفعل' : 'Not Active')
            : (App::isLocale('ar') ? 'مفعل' : 'Active');
    }

    public function getStatusAttribute()
    {
        return $this->category_id == 0
            ? (App::isLocale('ar') ? 'قسم رئيسى' : 'Main Category')
            : (App::isLocale('ar') ? 'قسم فرعى' : 'Sub Category');
    }

    public function scopeWhereOnline($query)
    {
        return $query->where('online', 1);
    }

    public function scopeAuth($query)
    {
        return $query->where('user_id', auth('api')->id());
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function subCategories()
    {
        return $this->hasMany(Category::class, 'category_id', 'id');
    }

    public function mainCategory()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
