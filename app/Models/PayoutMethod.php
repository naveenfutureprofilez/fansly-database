<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutMethod extends Model
{
    use HasFactory;

    protected $fillable  = [
        'user_id',
        'paypal',
        'bank'
    ];

    // protected $casts = [
    //     'bank' => 'array'
    // ];


    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
