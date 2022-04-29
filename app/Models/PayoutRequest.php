<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutRequest extends Model
{
    use HasFactory;

    private $statuses = [
        0 => 'Pending',
        1 => 'In-Process',
        2 => 'Success',
        3 => 'Rejected',
        4 => 'On Hold'
    ];

    protected $fillable = [
        'user_id',
        'amount',
        'wallet_txn_id',
        'payout_type',
        'status'
    ];


    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function txn(){
        return $this->hasOne(PayoutTranscation::class, 'payout_request_id')->latestOfMany();
    }

    public function getTxnStatusAttribute(){
        return $this->statuses[$this->status] ?? 'Undefined';
    }
}
