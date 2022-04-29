<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostReport extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'post_id',
        'reason',
        'status',
    ];

    protected $casts = [
        'blocked_at' => 'datetime'
    ];

    public function reporter(){
        return $this->belongsTo(user::class, 'user_id');
    }

    public function post(){
        return $this->belongsTo(Post::class, 'post_id');
    }
}
