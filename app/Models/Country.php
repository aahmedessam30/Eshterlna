<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name_ar', 'name_en', 'zip_code'];

    public function getNameAttribute()
    {
        return $this->{'name_' . App::getLocale()};
    }

    public function stores()
    {
        return $this->hasManyThrough(Store::class, City::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
