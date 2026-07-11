<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $table = 'service_requests';

    protected $primaryKey = 'request_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $casts = [
        'request_id' => 'integer',
        'customer_id' => 'integer',
        'category_id' => 'integer',
    ];

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
    public function professional()
    {
        return $this->belongsTo(Professional::class, 'professional_id');
    }
    public function acceptedOffer()
{
    return $this->hasOne(Offer::class, 'request_id')->where('status', 'accepted');
}
}
