<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * List Recent notifications
     * Which are unread
     * @return \Illuminate\Http\Response - JSON
     */
    public function index($last = false){
        $user = Auth::user();
        $count = Notification::where('notifiable_id', $user->id)
        ->whereNull('read_at')
        ->count();
        if(!$last){
            $notifies = Notification::where('notifiable_id', $user->id)
            // ->whereNull('read_at')
            ->latest('created_at')
            ->take(10)
            ->get();
        } else {
            $notifies = Notification::where('notifiable_id', $user->id)
            ->where('id', '>', decrypt($last))
            // ->whereNull('read_at')
            ->latest('created_at')
            ->take(10)
            ->get();
        }

        $notifyArr = [];
        foreach($notifies as $k => $n){
            $notifyArr[] = [
                'uid' => encrypt($n->id),
                'text' => $n->data,
                'from' => [
                    'uid' => encrypt($n->from->id),
                    'name' => $n->from->name,
                    'username' => $n->from->username,
                    'avatar' => $n->from->avatar,
                    'role' => $n->from->role,
                    'is_pro' => $n->from->is_pro,
                ],
                'target' => empty($n->target_id) ?  false : encrypt($n->target_id),
                'type' => $n->type,
                'time' => $n->created_at->diffForHumans(),
                'read' => empty($n->read_at) ? false : $n->read_at->diffForHumans()
            ];
        }
        $last = count($notifyArr) ? $notifyArr[0]['uid'] : false;

        return response()->json([
            'status' => true,
            'unread' => $count,
            'notifications' => $notifyArr,
            'last'   => $last
        ], 200);
    }

    /**
     * Mark notification as read
     * @param mixed $id Encrypted Notification Id
     * @return \Illuminate\Http\Response - JSON 
     */
    public function markAsRead($id){
        $user = Auth::user();
        $notify = Notification::where('notifiable_id', $user->id)
        ->where('id', decrypt($id))
        ->whereNull('read_at')
        ->update(['read_at' => Carbon::now()]);

        return response()->json([
            'status' => true,
            'msg'    => 'Mark as read',
            'unread'  => Notification::where('notifiable_id', $user->id)->whereNull('read_at')->count(),
            'notify' => $id
        ]);
    }

    /**
     * Mark all notifications as read
     * @return \Illuminate\Http\Response - JSON 
     */
    public function markAllAsRead(){

        $notify = Notification::markAllRead();

        return response()->json([
            'status' => true,
            'msg'    => 'Marked All as read',
        ]);
    }

    /**
     * Delete all notifications
     * @return \Illuminate\Http\Response - JSON 
     */
    public function deleteAll(){

        $notify = Notification::deleteAll();

        return response()->json([
            'status' => true,
            'msg'    => 'All notifications has been deleted.',
        ]);
    }
}
