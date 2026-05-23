<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $primaryKey = 'commission_id';

    public $timestamps = false;

    protected $fillable = [
        'admin_id',
        'professional_id',
        'request_id',
        'amount',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
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