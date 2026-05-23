<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Governorate extends Model
{
    protected $primaryKey = 'governorate_id';
    public $timestamps = true;

    public function cities()
    {
        return $this->hasMany(City::class, 'governorate_id');
    }
}