<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempFile extends Model
{
    use HasFactory;

    protected $table = 'temp_files';
    protected $primaryKey = 'id';
    protected $get = ['id'];

    protected $fillable = [
        'user_id',
        'uid',
        'type',
        'name',
        'local_name',
        'mime',
        'size',
        'ext'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Types of the Media files
     */
    private static $mediaTypes = [
        'image' => [
            'image/jpeg',
            'image/png'
        ],
        'video' => [
            'video/mp4',
            'application/x-mpegURL',
            'video/quicktime',
            'video/avi',
            'video/mpeg'
        ]
    ];

    /**
     * Get Type of the media file like image, video
     * 
     * @param string $mime Mime type the target file
     * @return string Type of media file
     */
    public static function getMediaType($mime){
        $type = 'unknown';
        if(in_array($mime, self::$mediaTypes['image'])){
            $type  = 'image';
        } else if(in_array($mime, self::$mediaTypes['video'])){
            $type = 'video';
        }
        return $type;
    }
}
