<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;
use App\Models\CreatorPlan;
use App\Models\PostPayment;
use App\AdminNotification;
use App\Http\Requests\CreatorRequest;
use App\Http\Requests\CrMsgSetRequest;
use App\Http\Requests\ImageRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\WatermarkRequest;
use App\Models\AgeVerification;
use App\Models\CreatorRequest as CrRequest;
use App\Models\CreatorSetting;
use App\Models\Follower;
use App\Models\Post;
use App\Models\PostMedia;
use App\Models\Tip;
use App\Models\Watermark;
use App\Stripe;
use App\UserNotification;
use App\Yoti;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Validator;

class CreatorController extends Controller
{
    /**
     * Get All Posts Posted by the creator
     * Logged in Creatos
     * 
     * @param string $after Encypted last id of post
     * @return \Illuminate\Http\Response json response 
     */
    public function posts($after = null){
        $user = Auth::user();
        if($after){
            $posts = Post::where('user_id', $user->id)
            ->where('id', '<', decrypt($after))
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
        } else {
            $posts = Post::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
        }

        $postArray = [];
        $last = false;
        foreach($posts as $k => $p){
            $postArray[$k] = [
                'uid' => encrypt($p->id),
                'content' => $p->text_content,
                'likes' => $p->total_likes(),
                'comments' => $p->total_comments(),
                'tips' => $p->total_tips(),
                'status' => $p->post_status,
                'is_liked' => $p->isLiked(),
                'previews' => $p->previews->count(),
                'media' => $p->medias->count(),
                'earning' => $p->total_earnings,
                'auto_delete' => empty($p->delete_schedule) ? false : $p->delete_schedule->format('d-m-Y'),
                'auto_publish' => empty($p->publish_schedule) ? false : $p->publish_schedule->format('d-m-Y'),
                'posted_at' => $p->updated_at->diffForHumans(),
            ];

            if($p->is_conditional){

                $conditions = $p->conditions;
                if(!empty($conditions['subscription'])){
                    $subs = explode(',', $conditions['subscription']);
                    $plans = CreatorPlan::select('id','title', 'amount')->whereIn('id', $subs)->get();
                    $planArray = [];
                    foreach ($plans as $kl => $pl) {
                        $planArray[$kl] = [
                            'uid' => encrypt($pl->id),
                            'title' => $pl->title,
                            'amount' => $pl->amount
                        ];
                    }
                    $postArray[$k]['conditions']['plans'] = $planArray;
                }

                if(!empty($conditions['fix_price'])){
                    $postArray[$k]['conditions']['price'] = $conditions['fix_price'];
                }

            } else {
                $postArray[$k]['conditions'] = false;
            }
            $last = encrypt($p->id);
        }

        return response()->json([
            'posts' => $postArray,
            'last'  => $last
        ], 200);
    }

    /**
     * Get Acitve Posts Posted by the creator
     * Logged in Creatos
     * 
     * @param string $after Encypted last id of post
     * @return \Illuminate\Http\Response json response 
     */
    public function activePosts($after = null){
        $user = Auth::user();
        if($after){
            $posts = Post::where('user_id', $user->id)
            ->whereStatus(1)
            ->where('id', '<', decrypt($after))
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
        } else {
            $posts = Post::where('user_id', $user->id)
            ->whereStatus(1)
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
        }

        $postArray = [];
        $last = false;
        foreach($posts as $k => $p){
            $postArray[$k] = [
                'uid' => encrypt($p->id),
                'content' => $p->text_content,
                'likes' => $p->total_likes(),
                'comments' => $p->total_comments(),
                'tips' => $p->total_tips(),
                'is_liked' => $p->isLiked(),
                'previews' => $p->previews->count(),
                'media' => $p->medias->count(),
                'earning' => $p->total_earnings,
                'auto_delete' => empty($p->delete_schedule) ? false : $p->delete_schedule->format('d-m-Y'),
                'auto_publish' => empty($p->publish_schedule) ? false : $p->publish_schedule->format('d-m-Y'),
                'posted_at' => $p->updated_at->diffForHumans()
            ];

            if($p->is_conditional){

                $conditions = $p->conditions;
                if(!empty($conditions['subscription'])){
                    $subs = explode(',', $conditions['subscription']);
                    $plans = CreatorPlan::select('id','title', 'amount')->whereIn('id', $subs)->get();
                    $planArray = [];
                    foreach ($plans as $kl => $pl) {
                        $planArray[$kl] = [
                            'uid' => encrypt($pl->id),
                            'title' => $pl->title,
                            'amount' => $pl->amount
                        ];
                    }
                    $postArray[$k]['conditions']['plans'] = $planArray;
                }

                if(!empty($conditions['fix_price'])){
                    $postArray[$k]['conditions']['price'] = $conditions['fix_price'];
                }

            } else {
                $postArray[$k]['conditions'] = false;
            }
            $last = encrypt($p->id);
        }

        return response()->json([
            'posts' => $postArray,
            'last' => $last
        ], 200);
    }

    /**
     * Get Blocked Posts Posted by the creator
     * Logged in Creatos
     * 
     * @param string $after Encypted last id of post
     * @return \Illuminate\Http\Response json response 
     */
    public function blockedPosts($after = null){
        $user = Auth::user();
        if($after){
            $posts = Post::where('user_id', $user->id)
            ->whereStatus(4)
            ->where('id', '<', decrypt($after))
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
        } else {
            $posts = Post::where('user_id', $user->id)
            ->whereStatus(4)
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
        }

        $postArray = [];
        $last = false;
        foreach($posts as $k => $p){
            $postArray[$k] = [
                'uid' => encrypt($p->id),
                'content' => $p->text_content,
                'likes' => $p->total_likes(),
                'comments' => $p->total_comments(),
                'tips' => $p->total_tips(),
                'is_liked' => $p->isLiked(),
                'previews' => $p->previews->count(),
                'media' => $p->medias->count(),
                'earning' => $p->total_earnings,
                'posted_at' => $p->updated_at->diffForHumans()
            ];

            if($p->is_conditional){

                $conditions = $p->conditions;
                if(!empty($conditions['subscription'])){
                    $subs = explode(',', $conditions['subscription']);
                    $plans = CreatorPlan::select('id','title', 'amount')->whereIn('id', $subs)->get();
                    $planArray = [];
                    foreach ($plans as $kl => $pl) {
                        $planArray[$kl] = [
                            'uid' => encrypt($pl->id),
                            'title' => $pl->title,
                            'amount' => $pl->amount
                        ];
                    }
                    $postArray[$k]['conditions']['plans'] = $planArray;
                }

                if(!empty($conditions['fix_price'])){
                    $postArray[$k]['conditions']['price'] = $conditions['fix_price'];
                }

            } else {
                $postArray[$k]['conditions'] = false;
            }
            $last = encrypt($p->id);
        }

        return response()->json([
            'posts' => $postArray,
            'last' => $last
        ], 200);
    }

    /**
     * Get Schedule Posts Posted by the creator
     * Logged in Creatos
     * 
     * @param string $after Encypted last id of post
     * @return \Illuminate\Http\Response json response 
     */
    public function scheduledPosts($after = null){
        $user = Auth::user();
        if($after){
            $posts = Post::where('user_id', $user->id)
            ->whereStatus(5)
            ->where('id', '<', decrypt($after))
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
        } else {
            $posts = Post::where('user_id', $user->id)
            ->whereStatus(5)
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
        }

        $postArray = [];
        $last = false;
        foreach($posts as $k => $p){
            $postArray[$k] = [
                'uid' => encrypt($p->id),
                'content' => $p->text_content,
                'likes' => $p->total_likes(),
                'comments' => $p->total_comments(),
                'tips' => $p->total_tips(),
                'is_liked' => $p->isLiked(),
                'previews' => $p->previews->count(),
                'media' => $p->medias->count(),
                'earning' => $p->total_earnings,
                'auto_delete' => empty($p->delete_schedule) ? false : $p->delete_schedule->format('d-m-Y'),
                'auto_publish' => empty($p->publish_schedule) ? false : $p->publish_schedule->format('d-m-Y'),
                'posted_at' => $p->updated_at->diffForHumans()
            ];

            if($p->is_conditional){

                $conditions = $p->conditions;
                if(!empty($conditions['subscription'])){
                    $subs = explode(',', $conditions['subscription']);
                    $plans = CreatorPlan::select('id','title', 'amount')->whereIn('id', $subs)->get();
                    $planArray = [];
                    foreach ($plans as $kl => $pl) {
                        $planArray[$kl] = [
                            'uid' => encrypt($pl->id),
                            'title' => $pl->title,
                            'amount' => $pl->amount
                        ];
                    }
                    $postArray[$k]['conditions']['plans'] = $planArray;
                }

                if(!empty($conditions['fix_price'])){
                    $postArray[$k]['conditions']['price'] = $conditions['fix_price'];
                }

            } else {
                $postArray[$k]['conditions'] = false;
            }
            $last = encrypt($p->id);
        }

        return response()->json([
            'posts' => $postArray,
            'last' => $last
        ], 200);
    }

    /**
     * List Active Subscriptions by users
     * for logged in creator
     * 
     * @param string $after Timestamp of last entry
     * @return \Illuminate\Http\Response json response
     */
    public function activeSubscriptions($after = null){
        $user = Auth::user();
        if($after){

            $subs = Subscription::where('creator_id', $user->id)
            ->where('status',1)
            ->where('updated_at', '<', $after)
            ->latest('updated_at')
            ->take(1)
            ->get();

        } else {

            $subs = Subscription::where('creator_id', $user->id)
            ->where('status',1)
            ->latest('updated_at')
            ->take(10)
            ->get();

        }
        $last = false;
        $subsArr = [];
        foreach($subs as $k => $s){
            $subsArr[$k] = [
                'uid' => encrypt($s->id),
                'paid' => $s->amount_paid,
                'discount' => $s->discount,
                'month'   => $s->plan_duration,
                'start'   => $s->start->format('d M, Y'),
                'end'     => $s->end->format('d M, Y'),
                'status'  => $s->sub_status,
                'plan'    => [
                    'uid'   => encrypt($s->plan->id),
                    'title' => $s->plan->title,  
                ],
                'user' => [
                    'name' => $s->user->name,
                    'username' => $s->user->username,
                    'avatar' => $s->user->avatar,
                    'role'   => $s->user->role,
                    'is_pro'   => $s->user->is_pro,
                ]
            ];
            $last = $s->updated_at;
        }

        return response()->json([
            'subscriptions' => $subsArr,
            'total' => count($subsArr),
            'last'  => $last
        ], 200);
    }

    /**
     * List expired Subscriptions of users
     * for logged in creator
     * 
     * @param string $after Timestamp of last entry
     * @return \Illuminate\Http\Response
     */
    public function expiredSubscriptions($after = null){
        $user = Auth::user();
        if($after){

            $subs = Subscription::where('creator_id', $user->id)
            ->where('status',0)
            ->where('updated_at', '<', $after)
            ->latest('updated_at')
            ->take(10)
            ->get();

        } else {

            $subs = Subscription::where('creator_id', $user->id)
            ->where('status',0)
            ->latest('updated_at')
            ->take(10)
            ->get();

        }
        $last = false;
        $subsArr = [];
        foreach($subs as $k => $s){
            $subsArr[$k] = [
                'uid' => encrypt($s->id),
                'paid' => $s->amount_paid,
                'discount' => $s->discount,
                'month'   => $s->plan_duration,
                'start'   => $s->start->format('d M, Y'),
                'end'     => $s->end->format('d M, Y'),
                'status'  => $s->sub_status,
                'plan'    => [
                    'uid'   => encrypt($s->plan->id),
                    'title' => $s->plan->title,  
                ],
                'user' => [
                    'name' => $s->user->name,
                    'username' => $s->user->username,
                    'avatar' => $s->user->avatar,
                    'role'   => $s->user->role,
                    'is_pro'   => $s->user->is_pro,
                ]
            ];
        }

        return response()->json([
            'subscriptions' => $subsArr,
            'total' => count($subsArr),
            'last'  => $last
        ], 200);
    }

    /**
     * List Tips Received by the users
     * For logged in Creator
     * 
     * @param string $after Encrypted last Id
     * @return \Illuminate\Http\Response json response
     */
    public function tips($after = null){
        $user = Auth::user();
        $query = Tip::where('receiver_id', $user->id)
        ->orderBy('id', 'desc');
        if($after){
            $query->where('id', '<', decrypt($after));
        }

        $tips = $query->take(20)->get();
        $last = false;
        $tipsArr = [];
        foreach ($tips as $k => $t) {
            $tipsArr[$k] = [
                'uid' => encrypt($t->id),
                'amount' => $t->amount,
                'type'  => $t->tip_type,
                'via'   => encrypt($t->tip_via),
                'msg'   => $t->message,
                'sender' => [
                    'name' => $t->sender->name,
                    'username' => $t->sender->username,
                    'avatar' => $t->sender->avatar,
                    'role'   => $t->sender->role,
                    'is_pro'   => $t->sender->is_pro,
                ],
                'time' => $t->created_at->diffForHumans()
            ];
            $last = encrypt($t->id);
        }

        return response()->json([
            'tips' => $tips,
            'last' => $last
        ], 200);

    }

    /**
     * List Down Media uploaded
     * For logged in Creator
     * 
     * @param string $type image/video
     * @param string $after encrypted last items id
     * @return \Illuminate\Http\Response json response
     */
    public function media($type, $after = null){
        $user = Auth::user();
        $query = PostMedia::whereUserId($user->id)
        ->whereType($type)
        ->orderBy('id', 'desc');
        if($after){
            $query->where('id', '<', decrypt($after));
        }

        
        $media = $query->take(20)->get();

        $mediaArr = [];
        $last = false;

        foreach ($media as $k => $m) {
            $mediaArr[$k] = [
                'uid' => encrypt($m->id),
                'url' => Storage::url('public/post/media/'.$m->full_name),
                'added' => $m->created_at->diffForHumans()
            ];
            $last = encrypt($m->id);
        }

        return response()->json([
            'media' => $mediaArr,
            'last'  => $last
        ]);
    }

    /**
     * Get Watermark & Update with Post Request
     * 
     * @param \App\Http\Requests\WatermarkRequest $watermarkRequest Request validator
     * @return \Illuminate\Http\Response json response
     */
    public function creatorWatermark(WatermarkRequest $watermarkRequest){

        $user = User::find(Auth::user()->id);
        $wData = ['user_id' => $user->id];
        $watermark = Watermark::firstOrCreate($wData);

        if($watermarkRequest->isMethod('post') AND !empty($watermarkRequest->watermark)){

            $reqData = $watermarkRequest->only(['watermark']);
            
            $watermark->watermark = $reqData['watermark'];
            $watermark->save();

            return response()->json([
                'status' => true,
                'msg'    => 'Watermark has been updated.',
                'watermak' => $watermark->watermark
            ], 200);
        }

        if(empty($watermark->watermark)){
            $watermark->watermark = 'whoyouinto.com/'.$user->username;
            $watermark->save();
        }

        return response()->json([
            'watermark' => $watermark->watermark
        ], 200);
    }

    /**
     * Get and Update Settings
     * For Paid Message
     * 
     * @param \App\Http\Requests\CrMsgSetRequest $crMsgSetRequest
     * @return \Illuminate\Http\Response json response
     */
    public function msgSettings(CrMsgSetRequest $crMsgSetRequest){

        $user = User::findOrFail(Auth::user()->id);
        $setData = ['user_id' => $user->id];
        $setting = CreatorSetting::firstOrCreate($setData);
        if($setting->paid_msg === null){
            $setting->paid_msg = 0;
            $setting->save();
        }

        if($crMsgSetRequest->isMethod('POST')){
            $reqData = $crMsgSetRequest->only([
                'msg_setting',
                'amount'
            ]);

            $setting->paid_msg = $reqData['msg_setting'] ?? 0;
            $setting->paid_msg_amount = $reqData['amount'] ?? 1;
            $setting->save();

            return response()->json([
                'status' => true,
                'msg'    => 'Message settings have been updated.',
                'settings' => [
                    'msg_setting' => $setting->paid_msg,
                    'amount' => $setting->paid_msg_amount,
                ]
            ], 200);
        }

        return response()->json([
            'status' => true,
            'settings' => [
                'msg_setting' => $setting->paid_msg,
                'amount' => $setting->paid_msg_amount,
            ]
        ], 200);

    }

    /**
     * Check user is age verified or not
     * From YOTI data
     * 
     * @return \Illuminate\Http\Response json response
     */
    public function isAgeVerified(){
        $user = User::findOrFail(Auth::user()->id);
        if(empty($user->ageVerification->id)){
            $resp = [
                'status' => false,
                'age'    => false,
                'msg'    => 'Please verify your age first!'
            ];
        } else {
            if($user->ageVerification->age >= 18){

                $resp = [
                    'status' => true,
                    'age'    => true,
                    'msg'    => 'Age verified.'
                ];

            } else {
                $resp = [
                    'status' => true,
                    'age'    => false,
                    'year'   => $user->ageVerification->age,
                    'request'=> encrypt($user->ageVerification->id),
                    'msg'    => 'Age is less then 18. Please verify again'
                ];
            }
        }

        return response()->json($resp, 200);
    }

    /**
     * Generate Yoti Session Token for Age Verification
     * @return \Illuminate\Http\Response json response
     */
    public function getYotiSession(){
        $user = Auth::user();
        $reqData = [
            "type" => "OVER",
            "digital_id"=> [
                "allowed"=> true,
                "threshold"=> 18,
                "level"=> "PASSIVE"
            ],
            "doc_scan"=> [
                "allowed"=> true,
                "threshold"=> 18,
                "authenticity"=> "MANUAL",
                "level"=> "ACTIVE"
            ],
            "ttl" => 900,
            "reference_id" => Str::uuid(),
            "callback" => [
               "auto" => true,
               "url" => "https://whoyouinto.com/become_creator"
            ],
            "notification_url"=> "https://whoyouinto.com/webhook",
            "block_biometric_consent" => false,
            "cancel_url" => "https://whoyouinto.com/yotiPage"
        ];

        $req = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Yoti-Sdk-Id'  => '2c570867-8d4e-4903-a130-3c43068df377',
            'Cache-Control' => 'no-cache'
        ])->withToken('6AZLebqhMVkeDo8TVvLEAyT5wz8=')
        ->post('https://age.yoti.com/api/v1/sessions', $reqData);

        if($req->successful()){
            $token = $req->json('id');
            $resp = [
                'status' => true,
                'token'  => $token,
                'url'    => 'https://age.yoti.com?sessionId='.$token.'&sdkId=2c570867-8d4e-4903-a130-3c43068df377'
            ];
            // echo 'Token - '.$token. '<br>';
            // echo 'https://age.yoti.com?sessionId='.$token.'&sdkId=2c570867-8d4e-4903-a130-3c43068df377';
        } else {
            $resp = [
                'status' => true,
                'msg'    => 'error in Yoti session generation -'.$req->status()
            ];
        }
        
        return response()->json($resp, 200);
    }

    /**
     * Check Yoti Session Status
     * @param string $session Yoti Session id
     * @return \Illuminate\Http\Response json response
     */
    public function checkYotiSession($session){
        $user = Auth::user();
        $req = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Yoti-Sdk-Id'  => '2c570867-8d4e-4903-a130-3c43068df377',
            'Cache-Control' => 'no-cache'
        ])->withToken('6AZLebqhMVkeDo8TVvLEAyT5wz8=')
        ->get('https://age.yoti.com/api/v1/sessions/'.$session.'/result');

        if($req->successful()){
            if(empty($req->json('id'))){
                $resp = [
                    'status' => false,
                    'msg'  => $req->json('context'),
                ];
            } else {
                $status = $req->json('status');

                if($status == 'COMPLETE'){
                    $verify = new AgeVerification;
                    $verify->user_id = $user->id;
                    $verify->name = $user->name;
                    $verify->age = true;
                    $verify->verfied = true;
                    $verify->token = $req->json('id');
                    $verify->verified_via = $req->json('method');
                    $verify->save();
                    
                    $creator = User::find($user->id);
                    $creator->role = 1;
                    $creator->save();
                }

                $resp = [
                    'status' => true,
                    'verify'  => $status,
                ];
            }
        } else {
            $resp = [
                'status' => false,
                'msg'    => 'error in Yoti session check -'.$req->status()
            ];
        }

        return response()->json($resp, 200);
    }

    /**
     * Age Verification Request
     * Token received via YOTI
     * 
     * @param string $verify=false Verification if exits
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response json response
     */
    public function verifyAge(Request $request, $verify = false){
        
        $request->validate([
            'token' => ['required']
        ]);
        
        $data = $request->only(['token']);
        $user = User::find(Auth::user()->id);
        if($verify){

            $id = decrypt($verify);
            $verify = AgeVerification::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        } else {
            $verify = new AgeVerification;
        }

        $yoti = new Yoti;
        $yotiResp = $yoti->getProfile($data['token']);
        if($yotiResp['status']){
            $profile = $yotiResp['profile'];
            $yotiData = [
                'name' => $profile->getFullName()->getValue() ?? '',
                'nation' => $profile->getNationality()->getValue() ?? '',
                'gender' => $profile->getGender()->getValue() ?? '', 
                'address' => $profile->getPostalAddress()->getValue() ?? [],
                'age'    => $profile->findAgeOverVerification(18) ?? false
            ];

            $verify->user_id = $user->id;
            $verify->name = $yotiData['name'];
            $verify->age = $yotiData['age'];
            $verify->nation = $yotiData['nation'];
            $verify->address = $yotiData['address'];
            $verify->gender = $yotiData['gender'];
            $verify->verfied = true;
            $verify->token = $data['token'];
            // $verify->verified_via = 'yoti';
            $verify->save();

            $resp = [
                'status' => true,
                'verify' => false,
                'age'    => '',
                'profile' => $yotiData,
                'msg'    => 'Request Received'
            ];
        } else {
            $resp = $yotiResp;
        }
        

        return response()->json($resp, 200);
    }



}
