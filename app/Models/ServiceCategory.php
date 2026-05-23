<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $table = 'service_categories';
    protected $primaryKey = 'category_id';

    protected $fillable = ['name', 'description'];

    public $timestamps = true; // غيّرها حسب جدولك

    public function professionals()
    {
        return $this->hasMany(Professional::class, 'category_id');
    }

    public function requests()
    {
        return $this->hasMany(ServiceRequest::class, 'category_id');
    }
}