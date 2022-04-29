<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PostPreview extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'uid',
        'post_id',
        'type',
        'name',
        'aws_id',
        'ext',
        'mime',
        'size'
    ];


    public function getFullNameAttribute(){
        return $this->uid.'.'.$this->ext;
    }
    
    public function getUrlAttribute(){
        if(Storage::disk('public')->exists('post/preview/'.$this->full_name)){
           return '/backend/v1/public/storage/post/preview/'.$this->full_name;
        } else {
            return false;
        }
    }
}
