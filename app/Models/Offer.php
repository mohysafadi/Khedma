<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $primaryKey = 'offer_id';

    protected $fillable = [
        'professional_id',
        'request_id',
        'description',
        'duration',
        'price',
        'status',
        'reason',
    ];

    public function professional()
    {
        return $this->belongsTo(Professional::class, 'professional_id');
    }

    public function request()
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id');
    }
}