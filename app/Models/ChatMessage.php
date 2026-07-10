<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $primaryKey = 'message_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'chat_id',
        'sender_id',
        'message',
    ];

    public function chat()
    {
        return $this->belongsTo(RequestChat::class, 'chat_id');
    }
}
