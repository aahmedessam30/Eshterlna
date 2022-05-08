<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name_ar', 'name_en', 'country_id', 'zip_code'];

    public function getNameAttribute()
    {
        return $this->{'name_' . App::getLocale()};
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function stores()
    {
        return $this->hasMany(Store::class);
    }
}
