<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'type', // Type of notification Post, Message, Requests
        'notifiable_type', // user | admin
        'notifiable_id', // Who will receive notification
        'data', // any Message
        'target_id', // Id of data to redirect
        'from_id', // Via user created notification
        'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime'
    ];

    public function from(){
        return $this->belongsTo(User::class, 'from_id');
    }

    /**
     * Mark as All read Notifications
     * For logged in or Admin User
     * @return void
     */
    public static function markAllRead(){
        $user = Auth::user();
        $user = User::find($user->id);
        if($user->isAdmin == 'Yes'){
            self::where('notifiable_type', 'admin')
            ->update(['read_at' => Carbon::now()]);
        } else {
            self::where('notifiable_type', 'user')
            ->where('notifiable_id', $user->id)
            ->update(['read_at' => Carbon::now()]);
        }
    }

    /**
     * Mark as All read Notifications
     * For logged in or Admin User
     * @return void
     */
    public static function deleteAll(){
        $user = Auth::user();
        $user = User::find($user->id);
        if($user->isAdmin == 'Yes'){
            self::where('notifiable_type', 'admin')
            ->delete();
        } else {
            self::where('notifiable_type', 'user')
            ->where('notifiable_id', $user->id)
            ->delete();
        }
    }

    
}
