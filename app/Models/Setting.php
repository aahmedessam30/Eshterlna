<?php

namespace App\Models;

use App\Traits\BasicTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Setting extends Model
{
    use HasFactory, BasicTrait;

    public $timestamps = false;

    protected $fillable = [
        'currency_ar',
        'currency_en',
        'about_ar',
        'about_en',
        'terms_ar',
        'terms_en',
        'theme',
        'image',
        'color',
        'size',
        'store',
        'delivery',
        'payment',
        'user_id',
    ];

    public function setImageAttribute($value)
    {
        $this->attributes['image'] = $this->setImage($value, 'images/settings');
    }

    public function getImageAttribute($value)
    {
        return $this->getImage($value, 'images/settings');
    }

    public function getAboutAttribute()
    {
        return $this->{'about_' . App::getLocale()};
    }

    public function getTermsAttribute()
    {
        return $this->{'terms_' . App::getLocale()};
    }

    public function getCurrencyAttribute()
    {
        return $this->{'currency_' . App::getLocale()};
    }
}
