<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProSubscription extends Model
{
    use HasFactory;

    private $statuses = [
        0 => 'Expired',
        1 => 'Active'
    ];
    
    protected $fillable = [
        'user_id',
        'amount',
        'amount_paid',
        'vat',
        'tax',
        'start',
        'end',
        'auto_renew',
        'via',
        'method'
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get Subscription Status String
     */
    public function getSubStatusAttribute(){
        return $this->statuses[$this->status] ?? 'Unknown';
    }
}
