<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PostMedia;
use App\Models\PostPreview;
class Post extends Model
{
    use HasFactory;

    /**
     * Status Texts For Posts
     * 0 - Pending
     * 1 - Published
     * 2 - Archived
     * 3 - Reported
     * 4 - Blocked
     * 5 - Scheduled
     */
    private $statuses = [
        'Pending',
        'Published',
        'Archived',
        'Reported',
        'Blocked',
        'Scheduled'
    ];

    protected $casts = [
        'conditions' => 'array',
        'publish_schedule' => 'datetime',
        'delete_schedule' => 'datetime',
    ];
    /**
     * Get Author of the Post
     * @return App\Models\User $author
     */
    public function author(){
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get Media Content of the Post
     * @return App\Models\PostMedia $media
     */
    public function medias(){
        return $this->hasMany(PostMedia::class, 'post_id');
    }

    /**
     * Get Preview Content of the Post
     * @return App\Models\PostPreview $preview
     */
    public function previews(){
        return $this->hasMany(PostPreview::class, 'post_id');
    }

    /**
     * Get Comment of the Post
     * @return App\Models\PostComment $comments
     */
    public function comments(){
        return $this->hasMany(PostComment::class, 'post_id')->orderBy('id','desc');
    }

    /**
     * Get Likes of the Post
     * @return App\Models\PostLike $comments
     */
    public function likes(){
        return $this->hasMany(PostLike::class, 'post_id')->where('is_like', 1)->orderBy('id','desc');
    }

    /**
     * Get Total Comments of the Post
     * @return int
     */
    public function total_comments(){
        return $this->comments()->count();
    }

    /**
     * Get Total likes of the Post
     * @return int
     */
    public function total_likes(){
        return $this->likes()->count();
    }

    /**
     * Get Tips of the Post
     * @return App\Models\Tip $comments
     */
    public function tips(){
        // $tips = Tip::where('tip_type', 'post')
        // ->where('tip_via', $this->id)
        // ->orderBy('id', 'desc')
        // ->get()
        // ->toArray();
        // return $tips;
        return $this->hasMany(Tip::class, 'tip_via')->where('tip_type', 'post');
    }

    /**
     * Get Total Tips of the Post
     * @return float
     */
    public function total_tips(){
        
        return $this->tips()->sum('amount');
    }

    /**
     * Get Total Earnings by a post
     * @return mixed
     */
    public function getTotalEarningsAttribute(){
        
        $tips = $this->total_tips();
        $purchase = PostPayment::where('post_id', $this->id)->sum('amount');

        return $tips + $purchase;
    }

    /**
     * Check Post Liked or not
     * @return bool
     */
    public function isLiked(){
        $like = PostLike::where('user_id', Auth::user()->id) //Auth::user()->id
        ->where('post_id', $this->id)
        ->where('is_like', 1)
        ->latest('updated_at')
        ->first();
        return empty($like->is_like) ? false : true;
    }


    /**
     * Get Status of the post in string
     * @return string status
     */
    public function getPostStatusAttribute(){
        return $this->statuses[$this->status];
    }

    /**
     * Get All Reports
     * @return App\Models\PostReport hasMany
     */
    public function reports(){
        return $this->hasMany(PostReport::class);
    }
}
