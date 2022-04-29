<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tip extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'receiver_id',
        'amount',
        'message',
        'tip_type',
        'tip_via',
        'txn_id',
        'status'
    ];

    public function sender(){
        return $this->belongsTo(user::class, 'user_id');
    }

    public function receiver(){
        return $this->belongsTo(user::class, 'receiver_id');
    }

    public function post(){
        return $this->belongsTo(Post::class, 'tip_via');
    }

    public function message(){
        return $this->belongsTo(Message::class, 'tip_via');
    }
}
