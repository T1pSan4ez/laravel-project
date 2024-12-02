<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_ids',
        'slot_ids',
        'total_amount',
    ];

    protected $casts = [
        'product_ids' => 'array',
        'slot_ids' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
