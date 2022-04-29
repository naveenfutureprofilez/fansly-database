<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostMedia;
use App\Models\PostPreview;
use App\Models\TempFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * CRON Jobs Handling Controller
 * All CRON actions controller via this controller
 */
class CronController extends Controller
{
    
    /**
     * Auto Upload Post Contents AT AWS S3
     * @return void
     */
    public function autoUploadS3(){
        $files = TempFile::whereNotNull('post_id')->get();
        $filesArr = [];
        foreach ($files as $k => $f) {
            $name = explode('.', $f->name);
            $ext = end($name);
            $fileName = $f->uid.'.'.$ext;
            if(Storage::exists('public/temp/'.$fileName)){
                $tmpFile = Storage::readStream('public/temp/'.$fileName);
                $file = Storage::disk('s3')->putStream('post/'.$f->type.'/'.$fileName, $tmpFile);
                $path = Storage::disk('s3')->path('post/'.$f->type.'/'.$fileName);

                $mediaData = [
                    'uid'     => $f->uid,
                    'user_id' => $f->user_id,
                    'post_id' => $f->post_id,
                    'type' => $f->type,
                    'mime' => $f->mime,
                    'size' => $f->size,
                    'name' => $f->name,
                    'ext' => $ext,
                    'aws_id' => $path
                ];

                $media = PostMedia::create($mediaData);

                $filesArr[$k] = [
                    'file' => $fileName,
                    'exist' => 'Yes',
                    'url'   => Storage::disk('s3')->url($file),
                    'path'  => $path,
                    'media' => $media->id
                ];
            } else {
                $filesArr[$k] = [
                    'file' => $fileName,
                    'exist' => 'No'
                ];
            }
        }
        echo '<pre>';
        print_r($filesArr);
        exit;
    }

    /**
     * Cron For Schedule Post
     * @return void
     */
    public function publishSchedulePost(){
        // $posts = Post::whereStatus(5)
        // ->whereNotNull('publish_schedule')
        // ->where('publish_schedule', '=', Carbon::now()->format('Y-m-d'))
        // ->count();

        $update = Post::whereStatus(5)
        ->whereNotNull('publish_schedule')
        ->where('publish_schedule', '=', Carbon::now()->format('Y-m-d'))
        ->update([
            'status' => 1
        ]);
        exit;
    }

    /**
     * Cron For Remove from Published
     * @return void
     */
    public function deleteSchedule(){
        $update = Post::whereStatus(1)
        ->whereNotNull('delete_schedule')
        ->where('delete_schedule', '<', Carbon::now()->format('Y-m-d'))
        ->update([
            'status' => 2
        ]);
        exit;
    }



    public function getFilesS3(){
        $medias = PostMedia::all();
        $filesArr = [];
        foreach ($medias as $m) {
            $filesArr[] = [
               'file' => $m->uid,
               'url'  => Storage::disk('s3')->temporaryUrl('post/'.$m->type.'/'.$m->uid.'.'.$m->ext, now()->addMinutes(1))
            ];
        }

        return view('test',[
            'files' => $filesArr
        ]);
    }
}
