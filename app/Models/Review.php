<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $primaryKey = 'review_id';

    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'professional_id',
        'request_id',
        'rating',
        'comment',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function professional()
    {
        return $this->belongsTo(Professional::class, 'professional_id');
    }

    public function request()
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id');
    }
}