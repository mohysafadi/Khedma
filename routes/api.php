<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LocationController;
use App\Http\Controllers\ServiceCategoryController;

Route::get('/service-categories', [ServiceCategoryController::class, 'index']);

Route::get('/governorates', [LocationController::class, 'governorates']);
Route::get('/governorates/{id}/cities', [LocationController::class, 'cities']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/register/basic-info', [AuthController::class, 'completeBasicInfo']);
Route::post('/register/professional-info', [AuthController::class, 'completeProfessionalInfo']);


Route::post('/login', [AuthController::class, 'login']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
