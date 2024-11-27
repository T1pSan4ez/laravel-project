<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory;
    protected $fillable = ['hall_id', 'row', 'number', 'price', 'type'];

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    public function sessionSlots()
    {
        return $this->hasMany(SessionSlot::class, 'slot_id');
    }
}
