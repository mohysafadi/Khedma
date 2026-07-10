<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestChat extends Model
{
    protected $primaryKey = 'chat_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'request_id',
        'customer_id',
        'professional_id',
    ];

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'chat_id');
    }
}
