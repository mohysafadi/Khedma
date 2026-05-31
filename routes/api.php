<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\ServiceRequestController;

/*
 جلب التصنيفات والخدمات
*/
// جلب كل تصنيفات الخدمات
Route::get('/service-categories', [ServiceCategoryController::class, 'index']);
// جلب المهنيين حسب التصنيف
Route::get('/professionals', [ProfessionalController::class, 'getByCategory']);
/*
| 2) جلب المحافظات والمدن
*/
// جلب كل المحافظات
Route::get('/governorates', [LocationController::class, 'governorates']);
// جلب المدن حسب المحافظة
Route::get('/governorates/{id}/cities', [LocationController::class, 'cities']);
/*
| 3) تسجيل مستخدم جديد (3 خطوات)
*/
// الخطوة 1: إنشاء حساب جديد (اسم + إيميل + كلمة سر)
Route::post('/register', [AuthController::class, 'register']);
// الخطوة 2: إدخال المعلومات الأساسية (الدور + الهاتف + الموقع)
Route::post('/register/basic-info', [AuthController::class, 'completeBasicInfo']);
// الخطوة 3: معلومات المهني (الخبرة + الفئة + الأدوات)
Route::post('/register/professional-info', [AuthController::class, 'completeProfessionalInfo']);
/*
| 4) تسجيل الدخول
*/
// تسجيل الدخول وإرجاع التوكن
Route::post('/login', [AuthController::class, 'login']);
/*
نسيت كلمة السر 
*/
// التحقق من وجود الإيميل
Route::post('/forgot-password', [UserController::class, 'checkEmail']);
// إنشاء كلمة سر جديدة مباشرة
Route::post('/reset-password', [UserController::class, 'resetPasswordDirect']);
/*
البروفايل (يتطلب تسجيل دخول)
*/
Route::middleware('auth:sanctum')->group(function () {
    // جلب بيانات البروفايل
    Route::get('/profile', [UserController::class, 'profile']);
    // تحديث بيانات البروفايل
    Route::post('/profile/update', [ProfileController::class, 'updateProfile']);
    // تغيير كلمة السر من داخل الحساب (يتطلب كلمة السر القديمة)
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);
    Route::middleware('auth:sanctum')->post('/service-requests', [ServiceRequestController::class, 'store']);
    // جلب بيانات المستخدم الحالي
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
