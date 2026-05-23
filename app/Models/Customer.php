<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'user_id',
        'governorate',
        'city',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function requests()
    {
        return $this->hasMany(ServiceRequest::class, 'customer_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'customer_id');
    }
}
