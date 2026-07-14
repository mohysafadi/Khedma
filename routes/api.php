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
use App\Http\Controllers\OfferController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ReviewController;

//التصنيفات والخدمات
// جلب كل تصنيفات الخدمات
Route::get('/service-categories', [ServiceCategoryController::class, 'index']);

// جلب المهنيين حسب التصنيف
Route::get('/professionals', [ProfessionalController::class, 'getByCategory']);
//المحافظات والمدن
// جلب كل المحافظات
Route::get('/governorates', [LocationController::class, 'governorates']);
// جلب المدن حسب المحافظة
Route::get('/governorates/{id}/cities', [LocationController::class, 'cities']);
//تسجيل مستخدم جديد ب3 خطوات
// الخطوة 1: إنشاء حساب جديد (اسم + إيميل + كلمة سر)
Route::post('/register', [AuthController::class, 'register']);
// الخطوة 2: إدخال المعلومات الأساسية (الدور + الهاتف + الموقع)
Route::post('/register/basic-info', [AuthController::class, 'completeBasicInfo']);
// الخطوة 3: معلومات المهني (الخبرة + الفئة + الأدوات)
Route::post('/register/professional-info', [AuthController::class, 'completeProfessionalInfo']);
//تسجيل الدخول وارجاع توكن 
Route::post('/login', [AuthController::class, 'login']);
//نسيان كلمة السر
// التحقق من وجود الإيميل
Route::post('/forgot-password', [UserController::class, 'checkEmail']);
// إنشاء كلمة سر جديدة مباشرة
Route::post('/reset-password', [UserController::class, 'resetPasswordDirect']);
//البروفايل
Route::middleware('auth:sanctum')->group(function () {
    // جلب بيانات البروفايل
    Route::get('/profile', [UserController::class, 'profile']);
    // تحديث بيانات البروفايل
    Route::post('/profile/update', [ProfileController::class, 'updateProfile']);
    // تغيير كلمة السر من داخل الحساب (يتطلب كلمة السر القديمة)
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);
    //الطلبات
    // إنشاء طلب جديد
    Route::post('/service-requests', [ServiceRequestController::class, 'store']);
    // جلب طلبات الزبون
    Route::get('/customer/requests', [ServiceRequestController::class, 'index']);
    // تفاصيل طلب معيّن (للزبون)
    Route::get('/service-requests/{id}', [ServiceRequestController::class, 'show']);
    // تفاصيل طلب معيّن (للمهني)
    Route::get('/professional/requests/{id}', [ServiceRequestController::class, 'professionalShow']);
    // جلب الطلبات المتاحة للمهني حسب المحافظة والتصنيف
    Route::get('/professional/requests', [ServiceRequestController::class, 'professionalRequests']);
    //العروض
    // تقديم عرض جديد على طلب
    Route::post('/service-requests/{id}/offers', [OfferController::class, 'store']);
    // جلب تفاصيل عرض واحد
    Route::get('/offers/{offer_id}', [OfferController::class, 'show']);
    // قبول عرض
    Route::post('/service-requests/{request_id}/offers/{offer_id}/accept', [OfferController::class, 'accept']);
    // رفض عرض
    Route::post('/service-requests/{request_id}/offers/{offer_id}/reject', [OfferController::class, 'reject']);
    //جلب العروض الخاصة بالمهني
    Route::get('/professional/offers', [OfferController::class, 'professionalOffers']);
    //المحفظة
    // جلب رصيد المحفظة
    Route::get('/wallet', [WalletController::class, 'show']);
    // جلب سجل العمليات
    Route::get('/wallet/transactions', [WalletController::class, 'transactions']);
    //المحادثات
    Route::middleware('auth:sanctum')->group(function () {
        //جلب المحادثات 
        Route::get('/chats', [ChatController::class, 'getChats']);
        //جلب الرسالة في المحاددثة
        Route::get('/chats/{chat_id}/messages', [ChatController::class, 'getChatMessages']);
        //ارسال رسالة 
        Route::post('/chats/{chat_id}/messages/send', [ChatController::class, 'sendMessage']);
    });
    //الشكاوي 
    Route::middleware('auth:sanctum')->post('/complaints', [ComplaintController::class, 'submit']);
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        //ارسال شكوى
        Route::get('/admin/complaints', [ComplaintController::class, 'index']);
        Route::put('/admin/complaints/{id}', [ComplaintController::class, 'updateStatus']);
    });
    //التقييم 
    Route::middleware('auth:sanctum')->group(function () {
        //تقييم مهني 
        Route::post('/reviews', [ReviewController::class, 'store']);
        //جلب متوسط تقييمات مهني     
        Route::get('/reviews/average/{professional_id}', [ReviewController::class, 'average']);
    });
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
