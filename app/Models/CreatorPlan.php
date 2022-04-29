<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PlanPromotion;
use Carbon\Carbon;

class CreatorPlan extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'title',
        'amount',
        'benefits',
        'month_2',
        'month_3',
        'month_6',
        'yearly',
        'status'
    ];

    protected $casts = [
        'benefits' => 'array',
        'month_2' => 'array',
        'month_3' => 'array',
        'month_6' => 'array',
        'yearly' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function promotions(){
        return $this->hasMany(PlanPromotion::class, 'plan_id');
    }

    /**
     * Return Recent Updated
     * Active Promotion
     * @return mixed Array of promotion or false
     */
    public function latestActivePromotion(){
        $promotion = PlanPromotion::where('plan_id', $this->id)
        ->whereStatus(1)
        ->where('avail_from' , '<=', Carbon::now())
        ->where('avail_to', '>=', Carbon::now())
        ->latest('updated_at')
        ->first();
        if($promotion){
            $start = new Carbon($promotion->avail_from);
            $end = new Carbon($promotion->avail_to);
            return [
                'uid' => encrypt($promotion->id),
                'prom_amount' => $promotion->prom_amount,
                'avail_from'  => $start->toDateString(),
                'avail_to'    => $end->toDateString() 
            ];
        } else {
            return false;
        }
    }
}
