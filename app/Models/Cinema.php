<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cinema extends Model
{
    use HasFactory;
    protected $fillable = ['city_id', 'name', 'address',];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'cinema_movie');
    }

    public function halls()
    {
        return $this->hasMany(Hall::class);
    }
}
