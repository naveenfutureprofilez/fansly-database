<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutTranscation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'txn_id',
        'payout_type',
        'payout_details',
        'status'
    ];
}
