<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'users'
    ];


    /**
     * Get Conversation Id of user
     * With target User
     * 
     * @param int $target Target User Id
     * @return \App\Models\Conversation
     */
    public static function getConversation($target){
        $user = Auth::user();
        $user_id = $user->id;
        $conversation = self::whereRaw("FIND_IN_SET(".$user_id.", users) AND FIND_IN_SET(".$target.", users)")
        ->first();
        
        if(empty($conversation)){
            $conversation = new self;
            $conversation->users = $user_id .",".$target;
            $conversation->save();
        }
        
        return $conversation;
    }

    /**
     * Get latest message of the conversation
     * @return \App\Models\Message $message
     */
    public function latest(){
        return $this->hasOne(Message::class, 'conversation_id')->latestOfMany();
    }
}
