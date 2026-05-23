<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $primaryKey = 'admin_id';

    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class, 'admin_id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'admin_id');
    }
}