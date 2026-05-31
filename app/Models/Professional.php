<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professional extends Model
{
    protected $primaryKey = 'professional_id';

    protected $fillable = [
        'user_id',
        'governorate_id',
        'professional_status',
        'category_id',
        'experience_years',
        'bio',
        'tool_image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class, 'professional_id');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'professional_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'professional_id');
    }
    public function governorate()
    {
        return $this->belongsTo(Governorate::class, 'governorate_id');
    }
}
