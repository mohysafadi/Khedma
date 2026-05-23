<?php

namespace App\Http\Controllers;

use App\Models\Governorate;
use App\Models\City;

class LocationController extends Controller
{
    public function governorates()
    {
        return Governorate::all();
    }

    public function cities($id)
    {
        return City::where('governorate_id', $id)->get();
    }
}