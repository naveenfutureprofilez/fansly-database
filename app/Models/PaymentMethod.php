<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gateway',
        'p_key',
        'p_meta',
        'is_default'
    ];

    protected $casts = [
        'p_meta' => 'array'
    ];
}
