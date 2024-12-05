<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'poster', 'description', 'duration', 'age_rating', 'release_date'];

    public function cinemas()
    {
        return $this->belongsToMany(Cinema::class, 'cinema_movie');
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_movie');
    }

    public function comments()
    {
        return $this->belongsToMany(Comment::class, 'movie_comments');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
