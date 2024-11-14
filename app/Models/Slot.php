<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory;
    protected $fillable = ['hall_id', 'row', 'number', 'price', 'type', 'is_available'];

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }
}
