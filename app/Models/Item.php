<?php

namespace App\Models;

use App\Traits\BasicTrait;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory, BasicTrait;

    protected $with = ['category', 'brand', 'merchant'];

    protected $fillable = [
        'name_ar',
        'name_en',
        'image',
        'description_ar',
        'description_en',
        'pay_price',
        'sale_price',
        'lowest_price',
        'discount',
        'code',
        'online',
        'category_id',
        'brand_id',
        'user_id',
    ];

    public function __construct()
    {
        if (getSettings()->size == 1) {
            array_push($this->with, 'sizes');
        }

        if (getSettings()->color == 1) {
            array_push($this->with, 'colors');
        }

        if (getSettings()->store == 1) {
            array_push($this->with, 'stores');
        }
    }

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
        return $this->attributes['image'] = $this->setImage($value, 'images/items');
    }

    public function getImageAttribute($value)
    {
        return $this->getImage($value, 'images/items');
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'item_colors')->withTimestamps();
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'item_sizes')->withTimestamps();
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Favourite::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class)->withTimestamps()->withPivot('quantity');
    }

    public function merchant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
