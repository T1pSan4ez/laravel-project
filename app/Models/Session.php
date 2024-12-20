<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'hall_id',
        'start_time',
        'end_time',
        'technical_break',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    public function slots()
    {
        return $this->hasManyThrough(Slot::class, Hall::class, 'id', 'hall_id', 'hall_id', 'id');
    }

    public function sessionSlots()
    {
        return $this->hasMany(SessionSlot::class, 'session_id');
    }
}
