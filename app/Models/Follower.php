<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'following_id',
        'follower_id',
        'accepted_at'
    ];

    protected $casts = [
        'accepted_at'=> 'datetime'
    ];
}
