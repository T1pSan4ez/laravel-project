<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'slot_id',
        'status',
    ];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }
}
