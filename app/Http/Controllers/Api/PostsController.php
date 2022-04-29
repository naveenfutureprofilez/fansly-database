<?php

namespace App\Http\Controllers\Api;

use App\AdminNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\PostPurchaseRequest;
use App\Http\Requests\PostRequest;
use App\Http\Requests\ReportRequest;
use App\Models\CreatorPlan;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostLike;
use App\Models\PostMedia;
use App\Models\PostPayment;
use App\Models\PostPreview;
use App\Models\PostReport;
use App\Models\Subscription;
use App\Models\TempFile;
use App\Models\Transaction;
use App\Models\User;
use App\UserNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
/**
 * Responsible for handle content Posts
 */
class PostsController extends Controller
{

    /**
     * Create A Post 
     * @param \App\Http\Requests\PostRequest $postRequest Post data Request
     * @return \Illiminate\Http\Response JSON
     */
    public function createPost(PostRequest $postRequest){
        
        $data = $postRequest->only([
            'content',
            'media',
            'preview',
            'schedule',
            'delete',
            'condition'
        ]);

        if(empty($data['content']) AND empty($data['media'])){
            throw ValidationException::withMessages([
                'content' => ['Post text content or Media required.']
            ]);
        }
        $user = Auth::user();
        $user_id = $user->id;
        $post = new Post;
        $post->user_id = $user_id;
        $post->text_content = $data['content'];
        $post->publish_schedule = $data['schedule'] ?? NULL;
        $post->delete_schedule = $data['delete'] ?? NULL;
        $post->is_conditional = !empty($data['condition']);
        $post->conditions = !empty($data['condition']) ? $data['condition'] : [];
        // $post->status = 1;
        if(!empty($data['schedule'])){
            $post->status = 5;
        } else {
            $post->status = 1;
        }
        $post->save();

        if(!empty($data['media'])){
            $media = explode(',',$data['media']);
            foreach($media as $m){
                $tmp = TempFile::where('uid', $m)
                ->orWhere('local_name', $m)
                ->first();
                if($tmp){
                    $mData = [
                        'user_id' => $user_id,
                        'post_id' => $post->id,
                        'uid'     => $tmp->uid,
                        'mime'    => $tmp->mime,
                        'type'    => $tmp->type,
                        'size'    => $tmp->size,
                        'ext'     => $tmp->ext,
                        'size'    => $tmp->size,
                    ];
                    if(PostMedia::create($mData)){
                        $tmpFile = $tmp->uid.'.'.$tmp->ext;
                        Storage::move('public/temp/'.$tmpFile, 'public/post/media/'.$tmpFile);
                        $tmp->delete(); 
                    }
                }
            }
        }

        if(!empty($data['preview'])){
            $previews = explode(',',$data['preview']);
            foreach($previews as $p){
                $tmp = TempFile::where('uid', $p)
                ->orWhere('local_name', $p)
                ->first();

                $preview = [
                    'user_id' => $user_id,
                    'post_id' => $post->id,
                    'uid'     => $tmp->uid,
                    'mime'    => $tmp->mime,
                    'type'    => $tmp->type,
                    'size'    => $tmp->size,
                    'ext'     => $tmp->ext,
                    'size'    => $tmp->size,
                ];
                if(PostPreview::create($preview)){
                    $tmpFile = $tmp->uid.'.'.$tmp->ext;
                    Storage::move('public/temp/'.$tmpFile, 'public/post/preview/'.$tmpFile);
                    $tmp->delete();
                }
            }
        }

        return response()->json([
            'status' => true,
            'msg'    => 'Post Created. It will be live soon.',
            'post'   => encrypt($post->id),
            // 'media'  => $data['media'] ?? ''
        ], 200);

    }


    public function getPosts($type, $after = null){
        $user = Auth::user();
        $user = User::find($user->id);
        if($type == 'all'){
            $query = Post::where('status', 1);
            if($after){
                $query->where('id', '<', decrypt($after));
            }
            $posts = $query->orderBy('id', 'desc')->take(10)->get();
        } else if($type == 'subscribed'){

            $subs = $user->usersSubscribed();

            if(empty($subs)){
                return response()->json([
                    'status' => true,
                    'msg'    => 'No posts found',
                    'posts'  => []
                ], 200);
            }

            $query = Post::where('status', 1)
            ->whereIn('user_id', $subs);
            if($after){
                $query->where('id', '<', decrypt($after));
            }
            $posts = $query->orderBy('id', 'desc')->take(10)->get();
        }
        
        $last = $after;
        if(!$posts->isEmpty()){
            $postsArray = [];
            foreach ($posts as $k => $p) {
                $postsArray[$k] = [
                    'uid' => encrypt($p->id),
                    'content' => $p->text_content,
                    'user'    => [
                        'id'  => $p->author->id,
                        'uid' => encrypt($p->author->id),
                        'name' => $p->author->name,
                        'username' => $p->author->username,
                        'role'     => $p->author->role,
                        'is_pro'     => $p->author->is_pro,
                        'avatar'   => $p->author->avatar,
                        'banner'   => $p->author->banner,
                        'likes'     => $p->author->likes(),
                        'followers' => $p->author->followers(),
                        'videos'    => User::totalVideos($p->author->id),
                        'images'    => User::totalImages($p->author->id),
                        'is_follow' => $user->isFollowing($p->author->id)
                    ],
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
                        $subCount = Subscription::whereIn('plan_id', $subs)
                        ->where('user_id', $user->id)
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
                if(!$p->is_conditional OR $p->author->id == $user->id OR $allow)
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
                $last = encrypt($p->id);
            }
            return response()->json([
                'last' => $last,
                'posts' => $postsArray
            ], 200);
        } else {
            return response()->json([
                'status' => true,
                'msg'    => 'No more posts',
                'posts'  => []
            ], 200);
        }

    }

    /**
     * Like a Post
     * @param string $id Encrypted Post Id
     * @return \Illiminate\Http\Response JSON
     */
    public function likePost($id){
        $post = Post::find(decrypt($id));
        $likeData = [
            'user_id' => Auth::user()->id,
            'post_id' => $post->id
        ];
        $like = PostLike::firstOrCreate($likeData);
        $like->is_like = empty($like->is_like) ? 1 : 0;
        $like->save();
        return response()->json([
            'status' => true,
            'msg'    => $like->is_like ? 'Liked' : 'Disliked',
            'like'   => $like->is_like ? true : false,
            'total'  => $post->total_likes(),
        ], 200);
    }

    /**
     * List Latest 10 or more Comments of a Post
     * First will load recent 10 comments
     * if Last id exist then it will load 10 more after it
     * @param string $id Enrypted Post Id
     * @param int|null $last Last Comment Id
     * @return \Illiminate\Http\Response Comments JSON
     */
    public function postComments($id, $last = null){

        $post = Post::find(decrypt($id));
        $comments = PostComment::where('post_id', $post->id)
        ->latest('created_at');
        if($last){
            $comments->where('id', '<', $last);
        }
        $comments = $comments->take(10)->get();
        $commentsArray = [];
        foreach($comments as $k => $c){
            $commentsArray[$k] = [
                'uid' => encrypt($c->id),
                // 'post_uid' => encrypt($post->id),
                'comment'  => $c->comment,
                'time'     => $c->created_at->diffForHumans(),
                'user'     => [
                    'uid'       => encrypt($c->commenter->id),
                    'name'      => $c->commenter->name,
                    'username'  => $c->commenter->username,
                    'avatar'    => $c->commenter->avatar,
                    'role'    => $c->commenter->role,
                    'is_pro'    => $c->commenter->is_pro
                ]
            ];
            $last = $c->id;
        }

        return response()->json([
            'status' => true,
            'msg'    => 'Comments Loaded',
            'post'   => encrypt($post->id),
            'comments' => $commentsArray,
            'last'   => $last
        ]);

    }


    /**
     * Comment on a Post
     * @param string $id Encrypted Post Id
     * @param App\Http\Requests\CommentRequest $commentRequest
     * @return \Illiminate\Http\Response JSON
     */
    public function comment($id, CommentRequest $commentRequest){
        $data = $commentRequest->safe()->only([
            'comment'
        ]);
        $post = Post::find(decrypt($id));
        $user = Auth::user();
        
        $data['user_id'] = $user->id;
        $data['post_id'] = $post->id;

        $comment = PostComment::create($data);
        UserNotification::postComment($post);

        return response()->json([
            'status' => true,
            'msg'    => 'Comment posted.',
            'comment'=> encrypt($comment->id),
            'total'  => $post->total_comments(),
        ], 200);

    }

    /**
     * View Post Details
     * @param string $id Encrypted Id of the Post
     * @return \Illiminate\Http\Response JSON
     */
    public function viewPost($id){
        $user = User::find(Auth::user()->id);
        // $user = User::find(31);
        $p = Post::findOrFail(decrypt($id));
        $postsArray = [
            'uid' => encrypt($p->id),
            'content' => $p->text_content,
            'user'    => [
                'id'  => $p->author->id,
                'uid' => encrypt($p->author->id),
                'name' => $p->author->name,
                'username' => $p->author->username,
                'role'     => $p->author->role,
                'is_pro'     => $p->author->is_pro,
                'avatar'   => $p->author->avatar,
                'banner'   => $p->author->banner,
                'likes'     => $p->author->likes(),
                'followers' => $p->author->followers(),
                'videos'    => User::totalVideos($p->author->id),
                'images'    => User::totalImages($p->author->id),
                'is_follow' => $user->isFollowing($p->author->id)
            ],
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
                $subCount = Subscription::whereIn('plan_id', $subs)
                ->where('user_id', $user->id)
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
                    $postsArray['plans'] = $planArray;
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
                    $postsArray['price'] = $conditions['fix_price'];
                }
            }
        }
        $m = [];
        if(!$p->is_conditional OR $p->author->id == $user->id OR $allow)
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
            $postsArray['media_total'] = $p->medias->count();
        } else{
            $allow = true;
        }
        $postsArray['media_allow'] = $allow;
        $postsArray['media'] = $m;
        $pr = [];
        foreach($p->previews as $mp){
            $tmpFile = $mp->uid.'.'.$mp->ext;
            $pr[] = [
                'type' => $mp->type,
                'url'  => Storage::url('public/post/preview/'.$tmpFile)
            ];
        }
        $postsArray['preview'] = $pr;

        return response()->json([
            'post' => $postsArray
        ], 200);
    }

    /**
     * Report A Post
     * @param string $id Encrypted id of Post
     * @param \Illumintae\Http\Request $request Post request
     * @return \Illiminate\Http\Response JSON
     */
    public function reportPost($id, ReportRequest $reportRequest){

        $user = User::findOrFail(Auth::user()->id); //
        $post = Post::findOrFail(decrypt($id));

        $data = $reportRequest->only([
            'reason',
            'explain'
        ]);

        $pData = [
            'user_id' => $user->id,
            'post_id' => $post->id
        ];

        $report = PostReport::firstOrCreate($pData);
        $report->reason = $data['reason'];
        $report->explains = $data['explain'] ?? '';
        $report->save();

        $post->reported = 1;
        $post->save();

        AdminNotification::postReported($report);
        
        return response()->json([
            'status' => true,
            'msg'    => 'Post report Success'
        ]);
    }

    /**
     * Pay for a post's content
     * While Unlocking a post
     * @param string $post Post's encrypted Id
     * @return Illuminate\Http\Response json response
     */
    public function postPurchase($post, PostPurchaseRequest $postPurchaseRequest){
        $post = Post::findOrFail(decrypt($post));
        $reqData = $postPurchaseRequest->only(['amount']);

        $user = User::find(Auth::user()->id);
        $creator = User::find($post->user_id);

        if($user->id == $creator->id){
            return response()->json([
                'status' => false,
                'msg'    => 'You are the author of this post so can not purchase this post'
            ], 200); 
        }

        if($post->is_conditional AND !empty($post->conditions['fix_price'])){

            if($user->balance >= $post->conditions['fix_price']){

                $trans = new Transaction();
                $trans->payer_id = $user->id;
                $trans->receiver_id = $creator->id;
                $trans->type = 'post-purchase';
                $trans->txn_id = strtoupper(Str::uuid());
                $trans->amount = $post->conditions['fix_price'];
                $trans->status = 1;
                $trans->txn_type = 0;
                $trans->save();

                $pay = new PostPayment;
                $pay->user_id = $user->id;
                $pay->post_id = $post->id;
                $pay->amount = $post->conditions['fix_price'];
                $pay->status = 1;
                $pay->paid_via = 'wallet';
                $pay->txn_id = $trans->txn_id;
                $pay->save();

                $user->balance -= $post->conditions['fix_price'];
                $user->save();
                $creator->balance += $post->conditions['fix_price'];
                $creator->save();
                $resp = [
                    'status' => true,
                    'msg'    => 'Success!'
                ];
            } else {
                $resp = [
                    'status' => false,
                    'wallet' => false,
                    'msg'    => 'Insufficient balance. Please add some amount in wallet.'
                ];  
            }

        } else {
            $resp = [
                'status' => false,
                'wallet' => true,
                'msg'    => 'Invalid Transactions.'
            ];
        }

        return response()->json($resp, 200);
    }

    /**
     * Delete a Post
     * Creator ACTION for delete his/her post
     * 
     * @param string $post Encrypted Id of the post
     * @return \Illuminate\Http\Response json response
     */
    public function deletePost($post){
        $user = Auth::user();
        $post = Post::where('id', decrypt($post))
        ->where('user_id', $user->id)
        ->first();
        if($post){

            PostLike::where('post_id', $post->id)->delete();
            PostComment::where('post_id', $post->id)->delete();
            PostPayment::where('post_id', $post->id)->delete();
            PostMedia::where('post_id', $post->id)->delete();
            PostPreview::where('post_id', $post->id)->delete();
            PostReport::where('post_id', $post->id)->delete();
            $post->delete();

            $resp = [
                'status' => true,
                'msg'    => 'Post has been deleted successfully.'
            ];

        } else {
            $resp = [
                'status' => false,
                'msg'    => 'Post not found!'
            ];
        }
        return response()->json($resp, 200);
    }

    /**
     * Archive a post
     * Creator archive a published post
     * 
     * @param string $post Encrypted Id of the post
     * @return \Illuminate\Http\Response json response
     */
    public function archivePost($post){
        $user = Auth::user();
        $post = Post::where('id', decrypt($post))
        ->where('user_id', $user->id)
        ->first();
        if($post){

            $post->status = 2;
            $post->save();

            $resp = [
                'status' => true,
                'msg'    => 'Post has been archived successfully.'
            ];

        } else {
            $resp = [
                'status' => false,
                'msg'    => 'Post not found!'
            ];
        }
        return response()->json($resp, 200);
    }


    public function getPostStats($id){
        $post = Post::where('id', $id)
        ->first();

        return response()->json([
            'likes' => $post->total_likes(),
            'comments' => $post->total_comments(),
            'tips'  => $post->total_tips(),
            'post'  => encrypt($post->id)
        ], 200);
    }
}
