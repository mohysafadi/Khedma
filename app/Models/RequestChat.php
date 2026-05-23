<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestChat extends Model
{
    protected $primaryKey = 'message_id';

    public $timestamps = false;

    protected $fillable = [
        'request_id',
        'sender_id',
        'message',
        'sent_at',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function request()
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id');
    }
}