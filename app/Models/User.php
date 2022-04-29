<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Follower;
use App\Models\Like;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'phone',
        'password',
        'bio',
        'pre_type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Check is the logged in user following
     * The target user
     * 
     * @param int $t Target Users Id
     * @return bool true if following else false
     */
    public function isFollowing($t){

        $exist = Follower::where('follower_id', $this->id)
        ->where('following_id', $t)
        ->count();

        return $exist ? true : false;
    }

    /**
     * Check is the logged in user Liking the profile
     * The target user
     * 
     * @param int $t Target Users Id
     * @return bool true if following else false
     */
    public function isLiking($t){

        $exist = Like::where('user_id', $this->id)
        ->where('likeable_id', $t)
        ->count();

        return $exist ? true : false;
    }

    /**
     * Check is the logged in user followed
     * By The target user
     * 
     * @param int $t Target Users Id
     * @return bool true if following else false
     */
    public function isFollowed($t){

        $exist = Follower::where('following_id', $this->id)
        ->where('follower_id', $t)
        ->count();

        return $exist ? true : false;
    }

    /**
     * Check is the logged in user Liked
     * By the the target user
     * 
     * @param int $t Target Users Id
     * @return bool true if following else false
     */
    public function isLiked($t){

        $exist = Like::where('likeable_id', $this->id)
        ->where('user_id', $t)
        ->count();

        return $exist ? true : false;
    }

    /**
     * Get Total Followers
     * 
     *@return int Count of followers
     */
    public function followers(){
        return Follower::where('following_id', $this->id)->count();
    }

    /**
     * Get Total Profile Likes
     * 
     *@return int Count of Likes
     */
    public function likes(){
        return Like::where('likeable_id', $this->id)->count();
    }

    /**
     * Get total Videos Posted By User
     * 
     * @param int $id User Id
     * @return int Count of Videos
     */
    public static function totalVideos($id){
        return PostMedia::where('user_id', $id)
        ->where('type', 'video')
        ->select('id')
        ->count();
    }

    /**
     * Get total Images Posted By User
     * 
     * @param int $id User Id
     * @return int Count of Images
     */
    public static function totalImages($id){
        return PostMedia::where('user_id', $id)
        ->where('type', 'image')
        ->select('id')
        ->count();
    }

    /**
     * Get total Posts Posted By User
     * 
     * @param int $id User Id
     * @return int Count of Posts
     */
    public static function totalPosts($id){
        return Post::where('user_id', $id)
        ->select('id')
        ->count();
    }

    /**
     * List of users Id who is being followed
     * By the logged in user
     * @return array $flArray 
     */
    public function usersFollowed(){
        $fl = Follower::select('id', 'following_id', 'follower_id')
        ->where('follower_id', $this->id)->get();
        $flArray = [];
        foreach($fl as $f){
            $flArray[] = $f->following_id;
        }
        return $flArray;
    }

    /**
     * List of users Id who is being subscribed
     * By the logged in user
     * @return array $subArray array if exist
     */
    public function usersSubscribed(){
        $sub = Subscription::select('id','user_id', 'creator_id')
        ->where('user_id', $this->id)
        ->get();
        $subArray = [];
        foreach($sub as $s){
            $subArray = $s->creator_id;
        }

        return $subArray;

    }


    /**
     * List Of Recent Published Posts
     * By the targeted user
     * 
     * @return \App\Models\Post
     */
    public function recent_posts(){
        return $this->hasMany(Post::class, 'user_id')
        ->where('status', 1)
        ->latest('updated_at')
        ->take(20);
    }

    /**
     * Get Creator Address
     * @return mixed Array|bool
     */
    public function getAddressAttribute(){
        $req = CreatorRequest::where('user_id', $this->id)
        ->where('status', 2)
        ->latest('updated_at')
        ->first();
        
        return empty($req->address) ? false : $req->address;
    }

    /**
     * Get Pro Subscription
     * @return mixed 
     */
    public function proSubscription(){
        return $this->hasOne(ProSubscription::class, 'user_id')->latestOfMany();
    }

    /**
     * Get Creator Settings
     * @return \App\Models\CreatorSetting
     */
    public function creatorSettings(){
        return $this->hasOne(CreatorSetting::class, 'user_id');
    }

    /**
     * Get Approved Creator Request Details
     * 
     * @return \App\Models\CreatorRequest
     */
    public function approved(){
        return $this->hasOne(CreatorRequest::class, 'user_id')
        ->where('status', 2);
    }

    /**
     * Get User Role String
     * @return string $type  User type
     */
    public function getRoleTypeAttribute(){
        $type = 'Unknown';
        if($this->role == 1){
            $type = 'Creator';
            if($this->is_pro == 1){
                $type .= ' Pro';
            }
        } else {
            $type = 'User';
        }
        
        return $type;
    }

    /**
     * Get Count of total posts
     * 
     * @return int 
     */
    public function getTotalPostsAttribute(){
        return Post::where('user_id', $this->id)
        ->select('id')
        ->count();
    }

    /**
     * Get Purchased Posts Array
     * 
     * @return array $resp array of ids
     */
    public function purchasedPosts(){
        $posts = PostPayment::where('user_id', $this->id)
        ->select('id', 'user_id', 'post_id')
        ->get();

        $postArr = [];
        foreach ($posts as $p) {
           $postArr[] = $p->post_id;
        }

        return $postArr;
    }

    /**
     * Get Age Verificaton 
     * If Exist
     * 
     * @return \App\Models\AgeVerification $ageVerification
     */
    public function ageVerification(){
        return $this->hasOne(AgeVerification::class, 'user_id');
    }
}
