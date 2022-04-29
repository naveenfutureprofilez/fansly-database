<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessagePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message_id',
        'amount',
        'paid_via',
        'txn_id',
        'status'
    ];
}
