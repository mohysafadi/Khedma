<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Complaint extends Model
{
    protected $table = 'complaints';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'message',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}