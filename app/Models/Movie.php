<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'duration', 'age_rating', 'genre', 'release_date'];

    public function cinemas()
    {
        return $this->belongsToMany(Cinema::class, 'cinema_movie');
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}
