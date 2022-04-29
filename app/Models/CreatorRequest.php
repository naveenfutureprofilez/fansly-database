<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CreatorRequest extends Model
{
    use HasFactory;

    protected $table = 'creator_requests';
    protected $primaryKey = 'id';
    protected $get = ['id'];

    protected $fillable = [
        'user_id',
        'address',
        'id_type',
        'id_no',
        'id_expiry',
        'id_expire',
        'verify_img',
        'social'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'id_expiry'   => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'address'    => 'array',
        'social'     => 'array'
    ];

    /**
     * Get related User
     * @return \App\Models\User
     */
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get Approver Details
     * @return \App\Models\User
     */
    public function approver(){
        return $this->belongsTo(User::class, 'approved_by');
    }
}
