<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $primaryKey = 'request_id';

    protected $fillable = [
        'customer_id',
        'category_id',
        'description',
        'address',
        'photo',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class, 'request_id');
    }

    public function chat()
    {
        return $this->hasMany(RequestChat::class, 'request_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'request_id');
    }
}