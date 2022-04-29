<?php
namespace App\Http\Controllers\Api;

use App\Models\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller as BaseController;
use App\Models\TempFile;
use App\Http\Requests\FileRequest;
use App\Http\Requests\MultiFileRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Image;
class FilesController extends BaseController {

    public function uploadTempFile(FileRequest $fileRequest)
    {
        $data = $fileRequest->only(['uid','local_uid']);
        $media = $fileRequest->file('file');
        $data['name'] = $media->getClientOriginalName();
        $data['local_name'] = $data['local_uid'];
        $data['mime'] = $media->getClientMimeType();
        $data['size'] = $media->getSize();
        $data['type'] = TempFile::getMediaType($data['mime']);

        $ext = $media->extension();
        $fileName = $data['uid'].'.'.$ext;
        
        /**
         * Add Watermakr
         */
        if($data['type'] == 'image'){

            $wImg = Image::make($media->getRealPath());
            $wm = storage_path('app/public/watermark/default.png');
            $wImg->insert($wm, 'bottom-right', 0, 0);
            $wImg->encode($ext);
            $wImg->save(storage_path('app/public/temp/'.$fileName));

        } else {
            
            $media->storeAs('public/temp/', $fileName);
        }

        $data['user_id'] = Auth::user()->id;
        $data['ext'] = $ext;
        $file = TempFile::Create($data);
        return response()->json([
            'status' => true,
            'msg'    => 'Okay',
            'media'  => $fileName,
            'uid'    => $file->uid,
            'type'   => $file->type
        ], 200);
    }

    /**
     * Upload Multiple FIles in temp Directory
     * 
     * @param \App\Http\Requests\MultiFileRequest $multiFileRequest
     * @return json
     */
    public function uploadMultiFiles(MultiFileRequest $multiFileRequest){
        $data = [];
        $reqFiles = $multiFileRequest->file('files');
        foreach($reqFiles as $file){
            $uid = Str::uuid();
            $fileData = [
                'uid'       => $uid,
                'user_id'   => 31,
                'name'      => $file->getClientOriginalName(),
                'mime'      => $file->getClientMimeType(),
                'size'      => $file->getSize(),
                'type'      => TempFile::getMediaType($file->getClientMimeType())
            ];
            $ext = $file->extension();
            $fileName = $fileData['uid'].'.'.$ext;
            $file->storeAs('public/temp/', $fileName);

            $up = TempFile::Create($fileData);
            $data[] = [
                'type' => $up->type,
                'media'=> $fileName
            ];
        }

        return response()->json([
            'status'    => true,
            'msg'       => 'Okay',
            'files'     => $data
        ],200);
    }

    public function deleteFile($file){
        $tempFile = TempFile::where('uid',$file)
        ->orWhere('local_name', $file)
        ->first();
        if(!empty($tempFile->uid)){
            $name = explode('.', $tempFile->name);
            $ext = end($name);
            // Storage::disk('public')->delete('')
            Storage::delete('public/temp/'.$tempFile->uid.'.'.$ext);
            $tempFile->delete();
        }
        return response()->json([
            'status' => true,
            'msg'    => 'Okay'
        ], 200);
    }

    public function uploadAtS3(Request $request){

        if($request->hasFile('img')){
            $file = Storage::disk('s3')->putFile('avatar', $request->file('img'));
            $path = Storage::disk('s3')->path($file);
            $resp = [
                'status' => true,
                'msg'    => 'File Uploaded',
                'path'   => $path
            ];
        } else {
            $resp = [
                'status' => false,
                'msg'    => 'File Missing',
            ];
        }

        return response()->json($resp,200);
    }

    public function addWatermark(){
        $imgName = 'test.png';
        $tName = 'watermark.png';
        $wm = storage_path('app/public/watermark/default.png');
        $img = Image::make(storage_path('app/public/verify/'.$imgName));

        $img->insert($wm, 'bottom-right', 0, 0);
        $img->encode('png');
        $img->save(storage_path('app/public/verify/'.$tName));

        return response()->json([
            'status' => true,
            'target' => storage_path('app/public/watermark/default.png'),
            'url' => asset('public/storage/verify/'.$tName),
            'w' => asset('public/storage/watermark/default.png'),
        ], 200);

    }
}