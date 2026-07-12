<?php

use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ComplaintController;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/admin/login', [AdminController::class, 'loginPage'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');

Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/complaints', [ComplaintController::class, 'webIndex'])->name('admin.complaints');
Route::get('/admin/complaints/{id}/edit', [ComplaintController::class, 'editPage']);
Route::post('/admin/complaints/{id}/update', [ComplaintController::class, 'updateStatusWeb']);
Route::get('/admin/complaints/{id}', [ComplaintController::class, 'show'])->name('admin.complaints.show');
Route::get('/admin/professionals', [AdminController::class, 'professionals']);
Route::get('/admin/professionals/{id}', [AdminController::class, 'professionalDetails']);
Route::get('/admin/users', [AdminController::class, 'users']);
Route::get('/admin/users/{id}', [AdminController::class, 'userDetails']);
Route::get('/admin/service-requests', [AdminController::class, 'requests']);
Route::get('/admin/service-requests/{id}', [AdminController::class, 'requestDetails']);
Route::post('/admin/users/ban', [AdminController::class, 'banUser']);
Route::get('/admin/banned-users', [AdminController::class, 'bannedUsers']);

Route::post('/admin/users/unban', [AdminController::class, 'unbanUser']);
Route::get('/admin/wallet/charge', function () {
    return view('admin.wallet_charge');
});


Route::get('/logout', function () {
    Session::flush(); // حذف كل بيانات الجلسة
    return redirect('/admin/login'); // رجوع لصفحة تسجيل الدخول
});

Route::post('/admin/wallet/charge', [AdminController::class, 'chargeWallet']);

Route::get('/admin/users/{id}/actions', function ($id) {
    $user = User::findOrFail($id);
    return view('admin.user_actions', compact('user'));
});
