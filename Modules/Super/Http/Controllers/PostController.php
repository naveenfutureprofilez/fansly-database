<?php

namespace Modules\Super\Http\Controllers;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostComment;
use App\Models\PostPayment;
use App\Models\PostMedia;
use App\Models\PostPreview;
use App\Models\PostReport;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $posts = Post::latest('created_at')
        ->take(50)
        ->get();
        return view('super::posts.list', [
            'title' => 'Recent Posts',
            'posts' => $posts
        ]);
    }

    /**
     * Admin View the post
     * 
     * @param \App\Models\Post $post
     * @return Renderable
     */
    public function view(Post $post){

        $comments = PostComment::where('post_id', $post->id)
        ->latest('created_at')
        ->take(20)
        ->get();

        $payments = PostPayment::where('post_id', $post->id)
        ->whereStatus(1)
        ->orderBy('id', 'desc')
        ->take(20)
        ->get();

        return view('super::posts.view', [
            'title' => 'View Post',
            'post' => $post,
            'comments' => $comments,
            'payments' => $payments
        ]);
    }

    /**
     * List all Archived Posts
     * @return Renderable
     */
    public function archive()
    {
        $posts = Post::where('status', 2)
        ->orderBy('id', 'DESC')
        ->take(50)
        ->get();
        return view('super::posts.archive', [
            'title' => 'Archived Posts',
            'posts' => $posts
        ]);
    }

    /**
     * List all Reported Posts
     * @return Renderable
     */
    public function reported()
    {
        $posts = Post::where('reported', 1)
        ->orderBy('id', 'DESC')
        ->take(50)
        ->get();
        return view('super::posts.reported', [
            'title' => 'Reported Posts',
            'posts' => $posts
        ]);
    }

    /**
     * View Reported Post
     * @return Renderable
     */
    public function reportedPost(Post $post){
        return view('super::posts.reported_post', [
            'title' => 'Reported Post',
            'post' => $post
        ]);
    }

    

    /**
     * Clear All Post reports
     * @return void
     */
    public function clearReportsPost(Post $post){
        
        PostReport::where('post_id', $post->id)->delete();
        $post->reported = 0;
        $post->save();
        return redirect(route('super.post.report'))->with('success', 'All Post reports has been cleared.');

    }

    /**
     * Clear All Post reports
     * @return void
     */
    public function blockPost(Post $post){
        $post->status = 4;
        $post->reported = 0;
        $post->save();
        return redirect(route('super.post.report'))->with('success', 'Post has been blocked.');
    }

    /**
     * List all Blocked Posts
     * @return Renderable
     */
    public function blocked()
    {
        $posts = Post::where('status', 4)
        ->orderBy('id', 'DESC')
        ->take(50)
        ->get();
        return view('super::posts.blocked', [
            'title' => 'Archived Posts',
            'posts' => $posts
        ]);
    }

    /**
     * Admin Delete a Post
     * 
     * @param \App\Models\Post $post
     * @return void
     */
    public function deletePost(Post $post){
        PostLike::where('post_id', $post->id)->delete();
        PostComment::where('post_id', $post->id)->delete();
        PostPayment::where('post_id', $post->id)->delete();
        PostMedia::where('post_id', $post->id)->delete();
        PostPreview::where('post_id', $post->id)->delete();
        PostReport::where('post_id', $post->id)->delete();
        $post->delete();
        return redirect()->back()->with('success', 'Post has been deleted.');
    }

    /**
     * Admin Archive the Post
     * 
     * @param \App\Models\Post $post
     * @return void
     */
    public function archivePost(Post $post){
        $post->status = 2;
        $post->save();
        return redirect()->back()->with('success', 'Post has been archived.');
    }

    /**
     * Admin Publis a post
     * 
     * @param \App\Models\Post $post
     * @return void
     */
    public function publishPost(Post $post){
        $post->status = 1;
        $post->save();
        return redirect()->back()->with('success', 'Post has been published.');
    }
    

}
