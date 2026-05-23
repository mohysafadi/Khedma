<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $primaryKey = 'complaint_id';

    public $timestamps = false;

    protected $fillable = [
        'complainant_id',
        'against_id',
        'request_id',
        'admin_id',
        'description',
        'status',
        'response',
    ];

    public function complainant()
    {
        return $this->belongsTo(User::class, 'complainant_id');
    }

    public function against()
    {
        return $this->belongsTo(User::class, 'against_id');
    }

    public function request()
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}