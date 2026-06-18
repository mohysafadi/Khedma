<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // ================================
    // 1) عرض البروفايل
    // ================================
    public function profile(Request $request)
    {
        $user = $request->user()->load(
            'customer.governorate',
            'customer.city',
            'professional.governorate',
            'professional.category'
        );

        // تحديد الموقع حسب الدور
        if ($user->role === 'customer') {
            $governorate = $user->customer->governorate->name ?? null;
            $city        = $user->customer->city->name ?? null;
        } else {
            $governorate = $user->professional->governorate->name ?? null;
            $city        = null;
        }

        $location = $governorate
            ? ($city ? "$governorate – $city" : $governorate)
            : "غير محدد";

        // بيانات البروفايل الأساسية
        $profile = [
            'name'     => $user->name,
            'email'    => $user->email,
            'phone'    => $user->phone,
            'location' => $location,
            'role'     => $user->role,
        ];

        // إضافة بيانات المهني فقط
        if ($user->role === 'professional') {
            $profile['experience_years'] = $user->professional->experience_years;
            $profile['bio']              = $user->professional->bio;
            $profile['category']         = $user->professional->category->name ?? null;
        }

        return response()->json($profile);
    }

    // ================================
    // 2) التحقق من وجود الإيميل
    // ================================
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'الإيميل غير موجود'], 404);
        }

        return response()->json(['message' => 'الإيميل موجود ويمكنك إنشاء كلمة سر جديدة الآن']);
    }

    // ================================
    // 3) إنشاء كلمة سر جديدة بدون الحاجة للقديمة
    // ================================
    public function resetPasswordDirect(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|min:6'
        ]);

        User::where('email', $request->email)->update([
            'password' => bcrypt($request->password)
        ]);

        return response()->json(['message' => 'تم إنشاء كلمة سر جديدة بنجاح']);
    }
}