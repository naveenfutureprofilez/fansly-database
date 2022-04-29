<?php
namespace App;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

/**
 * Class To send notifications to Admin
 * each notification is having a specific method
 * 
 * @method requestApproved - Notify for approval of notification
 */
class AdminNotification {
    
    private $user;

    public function __construct(){
        $this->user = Auth::user();
    }

    /**
     * Request Submitted by the user
     * @param \App\Models\CreatorRequest - $request Creator Request
     * @return void
     */
    public static function requestReceived($request){

        $user = Auth::user();
        $msg = $user->name.' has sent a request for Creator Role.';
        $notify = new Notification;
        $notify->type = 'request';
        $notify->notifiable_type = 'admin';
        $notify->notifiable_id = 1;
        $notify->data = $msg;
        $notify->target_id = $request->id;
        $notify->from_id = $user->id;
        $notify->save();  

    }

    /**
     * Request edited
     * @param \App\Models\CreatorRequest - $request Creator Request
     * @return void
     */
    public static function requestUpdated($request){
        $user = Auth::user();
        $msg = $user->name.' has updated the request for Creator Role.';
        $notify = new Notification;
        $notify->type = 'request';
        $notify->notifiable_type = 'admin';
        $notify->notifiable_id = 1;
        $notify->data = $msg;
        $notify->target_id = $request->id;
        $notify->from_id = $user->id;
        $notify->save();
    }

    /**
     * Someone reported the post
     * @param \App\Models\PostReport $report Post report
     * @return void
     */
    public static function postReported($report){
        $user = Auth::user();
        $msg = $user->name.' has reported a post.';
        $notify = new Notification;
        $notify->type = 'report';
        $notify->notifiable_type = 'admin';
        $notify->notifiable_id = 1;
        $notify->data = $msg;
        $notify->target_id = $report->post_id;
        $notify->from_id = $user->id;
        $notify->save();
    }

}