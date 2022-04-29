<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Message extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'from_id',
        'to_id',
        'message',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'conditions' => 'array'
    ];

    public function from(){
        return $this->belongsTo(User::class, 'from_id');
    }

    public function to(){
        return $this->belongsTo(User::class, 'to_id');
    }

    /**
     * Get Media file if any
     * 
     * @return \App\Models\MessageMedia
     */
    public function media(){
        return $this->hasOne(MessageMedia::class, 'message_id');
    }

    /**
     * Check if the user has purchased its media
     * 
     * @param int $t Target User Id
     * @return bool 
     */
    public function isPurchased($t){
        $paid = MessagePayment::where('user_id', $t)
        ->where('message_id', $this->id)
        ->count();

        return !empty($paid);
    }
}
