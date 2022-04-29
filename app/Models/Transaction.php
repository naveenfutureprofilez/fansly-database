<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'payer_id',
        'receiver_id',
        'txn_id',
        'type',
        'amount',
        'status',
        'remark',
    ];

    private $statuses = [
        0 => 'Pending',
        1 => 'Success',
        2 => 'Invalid',
        3 => 'Processing',
        4 => 'Failed'
    ];

    /**
     * Return txn status as txn_status
     * attribute
     * @return string
     */
    public function getTxnStatusAttribute(){
        return $this->statuses[$this->status] ?? 'Unknown';
    }

    /**
     * Payer
     */
    public function payer(){
        return $this->belongsTo(User::class, 'payer_id');
    }

    /**
     * Receiver
     */
    public function receiver(){
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
