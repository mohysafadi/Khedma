<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Import models
use App\Models\Customer;
use App\Models\Professional;
use App\Models\Admin;
use App\Models\Notification;
use App\Models\ChatMessage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // مهم جداً لعمل createToken()

    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'status',
    ];

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'user_id');
    }

    public function professional(): HasOne
    {
        return $this->hasOne(Professional::class, 'user_id');
    }

    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class, 'user_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }
    public function governorate()
    {
        return $this->belongsTo(Governorate::class, 'governorate_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
