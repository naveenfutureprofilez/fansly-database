<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class MessageMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'mime',
        'type',
        'name',
        'uid',
        'size',
        'ext'
    ];


    public function getFullNameAttribute(){
        return $this->uid.'.'.$this->ext;
    }
    
    public function getUrlAttribute(){
        if(Storage::disk('public')->exists('msg/'.$this->full_name)){
           return '/backend/v1/public/storage/msg/'.$this->full_name;
        } else {
            return false;
        }
    }
}
