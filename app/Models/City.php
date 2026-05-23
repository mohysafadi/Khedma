<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $primaryKey = 'city_id';
    public $timestamps = true;

    public function governorate()
    {
        return $this->belongsTo(Governorate::class, 'governorate_id');
    }
}