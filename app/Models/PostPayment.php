<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'amount',
        'status',
        'paid_via',
        'txn_id',
    ];

    public function payer(){
        return $this->belongsTo(user::class, 'user_id');
    }

    public function post(){
        return $this->belongsTo(Post::class, 'post_id');
    }
}
