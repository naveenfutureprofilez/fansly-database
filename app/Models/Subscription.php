<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CreatorPlan;
use App\Models\User;

class Subscription extends Model
{
    use HasFactory;

    public $timestamps = true;

    private $statuses = [
        0 => 'Expired',
        1 => 'Active'
    ];

    protected $fillable = [
        'user_id',
        'creator_id',
        'plan_id',
        'plan_duration',
        'amount',
        'amount_paid',
        'discount',
        'auto_renew',
        'auto_renew_discount',
        'start',
        'end',
        'status',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end'   => 'datetime',
    ];


    /**
     * User who have subscribed
     * @return \App\Models\User
     */
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Creator who have been subscribed
     * @return \App\Models\User
     */
    public function creator(){
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Plan which has been subscribed
     * @return \App\Models\CreatorPlan
     */
    public function plan(){
        return $this->belongsTo(CreatorPlan::class, 'plan_id');
    }

    /**
     * Get Plans Status String
     */
    public function getSubStatusAttribute(){
        return $this->statuses[$this->status] ?? 'Unknown';
    }
}
