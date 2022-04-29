<?php
namespace App\Http\Controllers\Api;

use App\AdminNotification;
use App\Http\Requests\CreatorRequest;
use App\Http\Requests\ImageRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use App\Models\Subscription;
use App\Models\CreatorPlan;
use App\Models\PostPayment;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SearchCreatorRequest;
use App\Http\Requests\UpdateEmailRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateUsernameRequest;
use App\Http\Requests\UsernameRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use App\Models\CreatorRequest as CrRequest;
use App\Models\CreatorSetting;
use App\Models\Follower;
use App\Models\PostMedia;
use App\Stripe;
use App\UserNotification;
use Carbon\Carbon;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Storage;
class UsersController extends BaseController {

    public function checkUsername(UsernameRequest $usernameRequest){
        $data = $usernameRequest->safe()->only(['username']);
        return response()->json([
            'status' => true,
            'msg'    => 'Valid user name',
            'username'=> $data['username']
        ], 200);
    }

    public function login(LoginRequest $loginRequest)
    {
        // $request->validate([
        //     'email' => ['required|min:6'],
        //     'password' => ['required|min:6']
        // ]);

        $data = $loginRequest->only('email', 'password');
        $data['isAdmin'] = 'No';
        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
            $data['username'] = $data['email'];
            unset($data['email']);
        }
        if (Auth::attempt($data)) {
            $user = Auth::user();
            $userData = [
                'uid' => encrypt($user->id),
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => $user->avatar,
                'banner' => $user->banner,
                'is_pro' => $user->is_pro,
                'role' => $user->role
            ];
            return response()->json($userData, 200);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.']
        ]);
    }

    public function logout(Request $request){
        $request->validate([
            'action' => ['required']
        ]);
        $data = $request->only(['action']);
        if(!empty($data['action']) AND $data['action'] == 'logout'){
            Auth::guard('web')->logout();
            return response()->json([
                'status' => true,
                'msg'    => 'Logout Successfully'
            ], 200);
        }
        return response()->json([
            'status' => false,
            'msg'    => 'Invalid request'
        ], 200);
    }

    public function register(RegisterRequest $registerRequest){
        $data = $registerRequest->safe()->only([
            // 'first_name',
            // 'last_name',
            'name',
            'username',
            'email',
            'password',
            'phone',
            'pre_type'
        ]);
        // $data['name'] = $data['first_name'] . ' ' .$data['last_name'];
        // unset($data['first_name']);
        // unset($data['last_name']);
        // unset($data['password_'])

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        if($user){
            $stripData = [
                'name' => $user->name,
                'email' => $user->email,
            ];
            $stripe = new Stripe;
            $stripeResp = $stripe->createCustomer($stripData);
            if($stripeResp['status']){
                $user->stripe_id = $stripeResp['customer']->id;
                $user->save();
            }
            $resp = [
                'status' => true,
                'msg'    => 'Registation Success!'
            ];
        } else {
            $resp = [
                'status' => false,
                'msg'    => 'Registation Failed!'
            ];
        }

        return response()->json($resp, 200);

    }

    /**
     * Request for upgrade to creator
     * @param \App\Http\Requests\CreatorRequest $creatorRequest
     * @return \Illuminate\Http\Response JSON
     */
    public function creatorRequest(CreatorRequest $creatorRequest){

        $user = Auth::user();
        $user_id = $user->id;
        // $user_id = 31;

        $reqData = $creatorRequest->safe()->only([
            'address',
            'social',
            'verify_img',
            'id_type',
            'id_no',
            'id_expire',
            'id_expiry'
        ]);

        $vImage = $creatorRequest->file('verify_img');
        if($vImage){
            $ext = $vImage->extension();
            $fileName = 'verify_31_'.time().'.'.$ext;
            $vImage->storeAs('public/verify/', $fileName);
            $reqData['verify_img'] = $fileName;
        }

        $reqData['user_id'] = $user_id;
        $req = CrRequest::Create($reqData);
        
        AdminNotification::requestReceived($req);

        return response()->json([
            'status'    => true,
            'msg'       => 'We have received the request. We will get back to you soon.',
            'data'      => encrypt($req->id)
        ], 200);
    }

    /**
     * Update the exising creator Request
     * @param string $id Request Id
     * @param \App\Http\Requests\CreatorRequest $creatorRequest
     * @return \Illuminate\Http\Response JSON
     */
    public function creatorRequestUpdate($id, CreatorRequest $creatorRequest){

        $user = Auth::user();
        $user_id = $user->id;
        // $user_id = 31;

        $reqData = $creatorRequest->safe()->only([
            'address',
            'social',
            'verify_img',
            'id_type',
            'id_no',
            'id_expire',
            'id_expiry'
        ]);

        $vImage = $creatorRequest->file('verify_img') ?? null;
        if($vImage){
            $ext = $vImage->extension();
            $fileName = 'verify_31_'.time().'.'.$ext;
            $vImage->storeAs('public/verify/', $fileName);
            $reqData['verify_img'] = $fileName;
        }

        $req = CrRequest::findOrFail($id);
        $reqData['status'] = 0;
        CrRequest::where('id', $req->id)
        ->where('user_id', $user_id)
        ->update($reqData);
        // $req = CrRequest::Create($reqData);
        
        AdminNotification::requestUpdated($req);

        return response()->json([
            'status'    => true,
            'msg'       => 'We have received the request. We will get back to you soon.',
            'data'      => encrypt($req->id)
        ], 200);
    }

    /**
     * Update Profile Image
     * @param \App\Http\Requests\ImageRequest $imageRequest Request for Image Validation
     * @return \Illuminate\Http\Response JSON
     */
    public function updateProfileImage(ImageRequest $imageRequest){
        
        $user = Auth::user();
        $user_id = $user->id;
        $user = User::find($user_id);
        $img = $imageRequest->file('up_img');
        $ext = $img->extension();
        $fileName = 'profile_'.$user_id.'_'.time().'.'.$ext;
        $img->storeAs('public/avatar/', $fileName);
        $user->avatar = $fileName;
        $user->save();
        return response()->json([
            'status'    => true,
            'msg'       => 'Profile Picture Updated Successfully.',
            'img'       => asset('public/storage/avatar/'.$user->avatar)
        ], 200);
    }

    /**
     * Update Profile Banner
     * @param \App\Http\Requests\ImageRequest $imageRequest Request for Image Validation
     * @return \Illuminate\Http\Response JSON
     */
    public function updateBannerImage(ImageRequest $imageRequest){
        
        $user = Auth::user();
        $user_id = $user->id;
        $user = User::find($user_id);
        $img = $imageRequest->file('up_img');
        $ext = $img->extension();
        $fileName = 'banner_'.$user_id.'_'.time().'.'.$ext;
        $img->storeAs('public/banner/', $fileName);
        $user->banner = $fileName;
        $user->save();
        return response()->json([
            'status'    => true,
            'msg'       => 'Profile Banner Updated Successfully.',
            'img'       => asset('public/storage/banner/'.$user->avatar)
        ], 200);
    }

    /**
     * Update Profile data- Bio, name
     * @param \App\Http\Requests\ProfileUpdateRequest $profileUpdateRequest Request profile data
     * @return \Illuminate\Http\Response JSON
     */
    public function updateProfileData(ProfileUpdateRequest $profileUpdateRequest){
        $user = Auth::user();
        $user_id = $user->id;
        $user = User::find($user_id);

        $data = $profileUpdateRequest->only([
            'name',
            'phone',
            'bio'
        ]);

        $user->name = $data['name'];
        $user->phone = $data['phone'];
        $user->bio = $data['bio'];
        $user->save();

        return response()->json([
            'status' => true,
            'msg'    => 'Your data has been updated successfully.',
            'user'   => [
                'name' => $user->name,
                'phone'=> $user->phone,
                'bio'  => $user->bio,
            ]
        ], 200);
    }

    public function pendingRequests(){
        $user = Auth::user();
        $user_id = $user->id;
        // $user_id = 31;
        $pending = CrRequest::where('user_id', $user_id)
        ->whereIn('status',[0,1])
        ->orderBy('id', 'desc')
        ->first();
        // $pending = $pending;
        return response()->json([
            'status' => true,
            'exist'  => empty($pending['id']) ?  0 : 1,
            'requests'=> $pending
        ], 200);
    }


    /**
     * Get Any Users Profile Detail
     * 
     * @param int $id Id of target User
     * @return mixed json data of user profile
     */
    public function userProfile($id){
        $user = User::findOrFail($id);
        return response()->json([
            'status' => true,
            'msg'    => 'Okay',
            'profile'=> $user,
            'likes'  => $user->likes(),
            'followers' => $user->followers(),
            'videos' => User::totalVideos($user->id),
            'images' => User::totalImages($user->id)
        ], 200);
    }

    /**
     * Get Any Users basic Data
     * Name, username, banner, avatar
     * Likes, Followers, Videos, Images
     * @param int $id Encryppted Id of target User
     * @return mixed json data of user profile
     */
    public function userProfileData($id){
        $user = User::findOrFail(decrypt($id));
        return response()->json([
            'uid'    => encrypt($user->id),
            'name'   => $user->name,
            'username'=> $user->username,
            'avatar'=> $user->avatar,
            'banner'=> $user->banner,
            'role'  => $user->role,
            'is_pro'  => $user->is_pro,
            'likes'  => $user->likes(),
            'followers' => $user->followers(),
            'videos' => User::totalVideos($user->id),
            'images' => User::totalImages($user->id)
        ], 200);
    }

    /**
     * Follow Someone
     * @param string $id Id of target user
     * @return \Illuminate\Http\Response JSON
     */
    public function follow($id){
        $user = Auth::user();
        $followData = [
            'follower_id' => $user->id,
            'following_id' => decrypt($id),
        ];

        $follow = Follower::firstOrCreate($followData);
        $follow->updated_at = Carbon::now();
        $follow->save();

        UserNotification::followed($follow->following_id);

        return response()->json([
            'status' => true,
            'msg'    => 'Success!'
        ]);
    }

    /**
     * unfollow Someone
     * @param string $id Id of target user
     * @return \Illuminate\Http\Response JSON
     */
    public function unfollow($id){
        // $id = decrypt($id);
        $user = Auth::user();
        $follow = Follower::where('follower_id', $user->id)
        ->where('following_id', decrypt($id))
        ->first();
        $follow->delete();

        return response()->json([
            'status' => true,
            'msg'    => 'Unfollowed!'
        ]);
    }

    /**
     * Get Creators to follow
     * 
     */
    public function usersToFollow(){
        $user = User::find(Auth::user()->id);
        $exist = $user->usersFollowed();
        $exist[] = $user->id;
        $needs = User::whereNotIn('id', $exist)
        ->where('role', 1)
        ->latest('updated_at')
        ->take(20)
        ->get();
        $needArray = [];
        foreach ($needs as $k => $n) {
            $needArray[$k] = [
                'uid' => encrypt($n->id),
                'name' => $n->name,
                'username' => $n->username,
                'avatar' => $n->avatar,
                'banner' => $n->banner,
                'role' => $n->role,
                'is_pro' => $n->is_pro
            ];
        }

        return response()->json([
            'users' => $needArray
        ], 200);
    }

    /**
     * User View Page Details
     * 
     * @param string $id Encrypted user Id
     * @return \Illiminate\Http\Response JSON
     */
    public function viewUser($id){
        $user = User::find(Auth::user()->id);
        // $user = User::find(31);
        $u = User::findOrFail(decrypt($id));
        $userData = [
            'uid'    => encrypt($u->id),
            'name'   => $u->name,
            'username'=> $u->username,
            'avatar'=> $u->avatar,
            'banner'=> $u->banner,
            'bio'=> $u->bio,
            'role'  => $u->role,
            'address' => $u->address,
            'is_pro'  => $u->is_pro,
            'likes'  => $u->likes(),
            'followers' => $u->followers(),
            'videos' => User::totalVideos($u->id),
            'images' => User::totalImages($u->id),
            'is_follow' => $user->isFollowing($u->id),
            'is_like' => $user->isLiking($u->id)
        ];
        $postsArray = [];
        foreach ($u->recent_posts as $k => $p) {
            $postsArray[$k] = [
                'uid' => encrypt($p->id),
                'content' => $p->text_content,
                'likes' => $p->total_likes(),
                'comments' => $p->total_comments(),
                'tips' => $p->total_tips(),
                'is_liked' => $p->isLiked(),
                'posted_at' => $p->updated_at->diffForHumans(),
            ];
            $allow = false;
            if($p->is_conditional){

                $conditions = $p->conditions;
                
                if(!empty($conditions['subscription'])){
                    $subs = explode(',', $conditions['subscription']);
                    $subCount = Subscription::whereIn('id', $subs)
                    ->where('status', 1)
                    ->count();
                    if($subCount){
                        $allow = true;
                    }
                    else{

                        $plans = CreatorPlan::select('id','title', 'amount')->whereIn('id', $subs)->get();
                        $planArray = [];
                        foreach ($plans as $kl => $pl) {
                            $planArray[$kl] = [
                                'uid' => encrypt($pl->id),
                                'title' => $pl->title,
                                'amount' => $pl->amount
                            ];
                        }
                        $postsArray[$k]['plans'] = $planArray;
                    }
                }

                if(!empty($conditions['fix_price'])){
                    $paid = PostPayment::where('user_id', $user->id)
                    ->where('post_id', $p->id)
                    ->where('status', 1)
                    ->count();
                    if($paid){
                        $allow = true;
                    }
                    else {
                        $postsArray[$k]['price'] = $conditions['fix_price'];
                    }
                }
            }
            $m = [];
            if(!$p->is_conditional)
            {
                foreach($p->medias as $md){
                    $tmpFile = $md->uid.'.'.$md->ext;
                    $m[] = [
                        'type' => $md->type,
                        'url'  => Storage::url('public/post/media/'.$tmpFile)
                    ];
                }
                $allow = true;
            } else if($p->is_conditional AND !$allow) {
                $postsArray[$k]['media_total'] = $p->medias->count();
            } else{
                $allow = true;
            }
            $postsArray[$k]['media_allow'] = $allow;
            $postsArray[$k]['media'] = $m;
            $pr = [];
            foreach($p->previews as $mp){
                $tmpFile = $mp->uid.'.'.$mp->ext;
                $pr[] = [
                    'type' => $mp->type,
                    'url'  => Storage::url('public/post/preview/'.$tmpFile)
                ];
            }
            $postsArray[$k]['preview'] = $pr;
            $last = $p->id;
        }
        $userData['posts'] = $postsArray;
        $userData['posts_last'] = $last ?? 0;

        return response()->json($userData, 200);
    }

    /**
     * User View Page Details
     * 
     * @param string $id Encrypted user Id
     * @return \Illiminate\Http\Response JSON
     */
    public function viewUserByUsername($username){
        $user = User::find(Auth::user()->id);
        // $user = User::find(31);
        // $u = User::findOrFail(decrypt($username));
        $u = User::where('username', $username)->first();
        if(empty($u->id)){
            abort(404, 'Username does not exist!');
            exit;
        }

        $msg_paid = false;
        $amount = 0;
        if($u->role == 1){
            $setting = CreatorSetting::where('user_id', $u->id)->first();
            $msg_paid = $setting->paid_msg ?? false;
            $amount = $setting->paid_msg_amount?? 0;
        }

        $userData = [
            'uid'    => encrypt($u->id),
            'name'   => $u->name,
            'username'=> $u->username,
            'avatar'=> $u->avatar,
            'banner'=> $u->banner,
            'bio'=> $u->bio,
            'role'  => $u->role,
            'address' => $u->address,
            'is_pro'  => $u->is_pro,
            'likes'  => $u->likes(),
            'followers' => $u->followers(),
            'videos' => User::totalVideos($u->id),
            'images' => User::totalImages($u->id),
            'is_follow' => $user->isFollowing($u->id),
            'is_like' => $user->isLiking($u->id),
            'paid'  => $msg_paid,
            'amount'  => $amount,
        ];
        $postsArray = [];
        foreach ($u->recent_posts as $k => $p) {
            $postsArray[$k] = [
                'uid' => encrypt($p->id),
                'content' => $p->text_content,
                'likes' => $p->total_likes(),
                'comments' => $p->total_comments(),
                'tips' => $p->total_tips(),
                'is_liked' => $p->isLiked(),
                'posted_at' => $p->updated_at->diffForHumans(),
            ];
            $allow = false;
            if($p->is_conditional){

                $conditions = $p->conditions;
                
                if(!empty($conditions['subscription'])){
                    $subs = explode(',', $conditions['subscription']);
                    $subCount = Subscription::whereIn('id', $subs)
                    ->where('status', 1)
                    ->count();
                    if($subCount){
                        $allow = true;
                    }
                    else{

                        $plans = CreatorPlan::select('id','title', 'amount')->whereIn('id', $subs)->get();
                        $planArray = [];
                        foreach ($plans as $kl => $pl) {
                            $planArray[$kl] = [
                                'uid' => encrypt($pl->id),
                                'title' => $pl->title,
                                'amount' => $pl->amount
                            ];
                        }
                        $postsArray[$k]['plans'] = $planArray;
                    }
                }

                if(!empty($conditions['fix_price'])){
                    $paid = PostPayment::where('user_id', $user->id)
                    ->where('post_id', $p->id)
                    ->where('status', 1)
                    ->count();
                    if($paid){
                        $allow = true;
                    }
                    else {
                        $postsArray[$k]['price'] = $conditions['fix_price'];
                    }
                }
            }
            $m = [];
            if(!$p->is_conditional)
            {
                foreach($p->medias as $md){
                    $tmpFile = $md->uid.'.'.$md->ext;
                    $m[] = [
                        'type' => $md->type,
                        'url'  => Storage::url('public/post/media/'.$tmpFile)
                    ];
                }
                $allow = true;
            } else if($p->is_conditional AND !$allow) {
                $postsArray[$k]['media_total'] = $p->medias->count();
            } else{
                $allow = true;
            }
            $postsArray[$k]['media_allow'] = $allow;
            $postsArray[$k]['media'] = $m;
            $pr = [];
            foreach($p->previews as $mp){
                $tmpFile = $mp->uid.'.'.$mp->ext;
                $pr[] = [
                    'type' => $mp->type,
                    'url'  => Storage::url('public/post/preview/'.$tmpFile)
                ];
            }
            $postsArray[$k]['preview'] = $pr;
            $last = $p->id;
        }
        $userData['posts'] = $postsArray;
        $userData['posts_last'] = $last ?? 0;

        return response()->json($userData, 200);
    }

    /**
     * Return Wallet Balance of the user
     */
    public function walletBalance(){
        $user = User::findOrFail(Auth::user()->id);
        return response()->json([
            'balance' => $user->balance
        ], 200);
    }

    /**
     * Change Username - Logged In user
     * 
     * @param \App\Http\Requests\UpdateUsernameRequest $updateUsernameRequest
     * @return \Illiminate\Http\Response JSON
     */
    public function updateUsername(UpdateUsernameRequest $updateUsernameRequest){
        $user = User::find(Auth::user()->id);
        $reqData = $updateUsernameRequest->only(['username']);

        $user->username = $reqData['username'];
        $user->save();

        return response()->json([
            'status' => true,
            'msg'    => 'Username has been updated successfully',
            'uname'  => $user->username
        ]);
    }

    /**
     * Change Email - Logged In user
     * 
     * @param \App\Http\Requests\UpdateEmailRequest $updateEmailRequest
     * @return \Illiminate\Http\Response JSON
     */
    public function updateEmail(UpdateEmailRequest $updateEmailRequest){
        $user = User::find(Auth::user()->id);
        $reqData = $updateEmailRequest->only(['email']);

        $user->email = $reqData['email'];
        $user->save();

        return response()->json([
            'status' => true,
            'msg'    => 'EMail has been updated successfully',
            'email'  => $user->email
        ]);
    }

    /**
     * Change Password - Logged In user
     * 
     * @param \App\Http\Requests\UpdatePasswordRequest $updatePasswordRequest
     * @return \Illiminate\Http\Response JSON
     */
    public function updatePassword(UpdatePasswordRequest $updatePasswordRequest){
        $user = User::find(Auth::user()->id);
        $reqData = $updatePasswordRequest->only(['password']);

        $user->password = Hash::make($reqData['password']);
        $user->save();

        return response()->json([
            'status' => true,
            'msg'    => 'Password has been updated successfully.'
        ]);
    }

    /**
     * Get Notifications Notify Status
     * 
     * @return \Illuminate\Http\Response json response
     */
    public function notifyStatus(){
        $user = User::find(Auth::user()->id);
        return response()->json([
            'notify' => $user->notify
        ], 200);
    }

    /**
     * Enable Disable Notifications
     * Logged in user
     * 
     * @param bool $status enable - true, disable false
     * @return \Illuminate\Http\Response json response
     */
    public function notify($status){
        $user = User::find(Auth::user()->id);
        $user->notify = $status;
        $user->save();

        return response()->json([
            'status' => true,
            'msg'    => 'Notifications have been '.($status ? 'enabled.' : 'disabled.')
        ], 200);
    }

    public function updateStripeCustom(){
        $users = User::where('isAdmin', 'No')
        ->whereNull('stripe_id')->get();
        $props = [];
        $stripe = new Stripe;
        foreach($users as $k => $u){
            $data = [
                'name' => $u->name,
                'email' => $u->email,
            ];
            $props[$k] = [
                'name' => $u->name,
                'email' => $u->email,
            ];
            $resp = $stripe->createCustomer($data);
            if($resp['status']){
                $customer = $resp['customer'];
                $u->stripe_id = $customer->id;
                $u->save();
                $props[$k]['stripe'] = $u->stripe_id;
            } else {
                $props[$k]['error'] = $resp['msg'];
            }
        }

        return response()->json([
            'props' => $props
        ], 200);
    }

    
    /**
     * Search Creators using username
     * 
     * @param \App\Http\Requests\SearchCreatorRequest $searchCreatorRequest
     * @return \Illuminate\Http\Response json response
     */
    public function searchCreator(SearchCreatorRequest $searchCreatorRequest){
        $user = Auth::user();
        $reqData = $searchCreatorRequest->safe()->only(['search']);
        $creators = User::where('role', 1)
        ->where('id', '!=', $user->id)
        ->whereLike('username', $reqData['search'])
        ->take(10)
        ->orderBy('id', 'desc')
        ->get();

        $crArr = [];
        foreach ($creators as $k => $c) {
            $crArr[$k] = [
                'uid'   => encrypt($c->id),
                'name'  => $c->name,
                'username'  => $c->username,
                'role'  => $c->role,
                'is_pro'  => $c->is_pro,
                'avatar'  => $c->avatar,
                'banner'  => $c->banner,
            ];
        }

        return response()->json([
            'users' => $crArr
        ], 200);
    }


    /**
     * Search Users in message system for send message
     * with message settings
     * 
     * @param \App\Http\Requests\SearchCreatorRequest
     * @return \Illuminate\Http\Response json response
     */
    public function searchUsers(SearchCreatorRequest $searchCreatorRequest){
        $user = Auth::user();
        $reqData = $searchCreatorRequest->safe()->only(['search']);
        $creators = User::where('id', '!=', $user->id)
        ->whereLike('username', $reqData['search'])
        ->take(10)
        ->orderBy('id', 'desc')
        ->get();

        $crArr = [];
        foreach ($creators as $k => $c) {

            $paid = false;
            $amount = false;
            if($c->role == 1){
                $setting = CreatorSetting::where('user_id', $c->id)->first();
                $paid = $setting->paid_msg ?? false;
                $amount = $setting->paid_msg_amount ?? 0;
            }

            $crArr[$k] = [
                'uid'   => encrypt($c->id),
                'name'  => $c->name,
                'username'  => $c->username,
                'role'  => $c->role,
                'is_pro'  => $c->is_pro,
                'avatar'  => $c->avatar,
                'banner'  => $c->banner,
                'paid'  => $paid,
                'amount'  => $amount,
            ];
        }

        return response()->json([
            'users' => $crArr
        ], 200);
    }


    /**
     * Get Purchased media for
     * logged in user
     * 
     * @param string $type Video / Image
     * @param mixed $after = false
     * @return \Illuminate\Http\Response json response
     */
    public function purchasedMedia($type, $after = false){

        $user = User::find(Auth::user()->id);
        $posts = $user->purchasedPosts();
        $mediaArr = [];
        $last = false;
        if(count($posts)){

            $query = PostMedia::whereIn('post_id', $posts)
            ->where('type', $type);

            if($after){
                $query->where('id', '<', decrypt($after));
            }

            $medias = $query->orderBy('id', 'desc')->take(20)->get();

            foreach($medias as $k => $m){
                $mediaArr[$k] = [
                    'uid' => encrypt($m->id),
                    'url' => Storage::url('public/post/media/'.$m->full_name),
                    'added' => $m->created_at->diffForHumans()
                ];
                $last = encrypt($m->id);
            }
        }

        return response()->json([
            'media' => $mediaArr,
            'type' => $type,
            'last' => $last
        ]);
    }

}