<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Vat extends Model
{
    use HasFactory;

    protected $fillable = ['name_ar', 'name_en', 'value', 'user_id'];

    public function getNameAttribute()
    {
        return $this->{'name_' . App::getLocale()};
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
