<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    use HasFactory;
    protected $fillable = ['cinema_id', 'name'];


    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }

    public function slots()
    {
        return $this->hasMany(Slot::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class, 'hall_id');
    }
}
