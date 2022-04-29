<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'txn_id',
        'type',
        'gateway',
        'p_key',
        'amount',
        'vat',
        'tax',
        'paid',
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



}
