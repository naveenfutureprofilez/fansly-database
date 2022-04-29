<?php
namespace App;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

/**
 * Class To send notifications to user
 * each notification is having a specific method
 * 
 * @method requestApproved - Notify for approval of notification
 */
class UserNotification {
    
    private $user;

    public function __construct(){
        $this->user = Auth::user();
    }

    /**
     * Request Approved by the Admin
     * @param \App\Models\CreatorRequest - $request Creator Request
     * @return void
     */
    public static function requestApproved($request){

        $admin = Auth::user();
        $msg = $admin->name.' has approved your creator request.';
        $notify = new Notification;
        $notify->type = 'request';
        $notify->notifiable_type = 'user';
        $notify->notifiable_id = $request->user_id;
        $notify->data = $msg;
        $notify->target_id = $request->id;
        $notify->from_id = $admin->id;
        $notify->save();  

    }

    /**
     * Request need to be edit
     * @param \App\Models\CreatorRequest - $request Creator Request
     * @return void
     */
    public static function needRequestUpdate($request){
        $admin = Auth::user();
        $msg = 'Please update your creator request. For more information please see creator request option.';
        $notify = new Notification;
        $notify->type = 'request';
        $notify->notifiable_type = 'user';
        $notify->notifiable_id = $request->user_id;
        $notify->data = $msg;
        $notify->target_id = $request->id;
        $notify->from_id = $admin->id;
        $notify->save();
    }

    /**
     * Someone Followed you
     * @param int $id Target user Id who has been followed
     * @return void
     */
    public static function followed($id){
        $user = Auth::user();
        $msg = $user->name.' is following you.';
        $notify = new Notification;
        $notify->type = 'follow';
        $notify->notifiable_type = 'user';
        $notify->notifiable_id = $id;
        $notify->data = $msg;
        $notify->target_id = $user->id;
        $notify->from_id = $user->id;
        $notify->save();
    }

    /**
     * Someone Commented On Post
     * @param \App\Models\Post $post Post on which user commented
     * @return void
     */
    public static function postComment($post){
        $user = Auth::user();
        $msg = $user->name.' commented on your post.';
        $notify = new Notification;
        $notify->type = 'post';
        $notify->notifiable_type = 'user';
        $notify->notifiable_id = $post->user_id;
        $notify->data = $msg;
        $notify->target_id = $post->id;
        $notify->from_id = $user->id;
        $notify->save();
    }

    /**
     * Tip received on Post
     * @param \App\Models\Post $post On which post
     * @param \App\Models\Tip $tip Tip Object
     * @return void
     */
    public static function tipOnPost($post, $tip){
        $user = Auth::user();
        $msg = $user->name.' has sent you tip of £'.$tip->amount.' on for one of your post.';
        $notify = new Notification;
        $notify->type = 'tip';
        $notify->notifiable_type = 'user';
        $notify->notifiable_id = $post->user_id;
        $notify->data = $msg;
        $notify->target_id = $post->id;
        $notify->from_id = $user->id;
        $notify->save();
    }

    /**
     * Tip received direct
     * @param \App\Models\Tip $tip Tip Object
     * @return void
     */
    public static function tipDirect($tip){
        $user = Auth::user();
        $msg = $user->name.' has sent you tip of £'.$tip->amount.'.';
        $notify = new Notification;
        $notify->type = 'tip';
        $notify->notifiable_type = 'user';
        $notify->notifiable_id = $tip->receiver_id;
        $notify->data = $msg;
        // $notify->target_id = $post->id;
        $notify->from_id = $user->id;
        $notify->save();
    }

}