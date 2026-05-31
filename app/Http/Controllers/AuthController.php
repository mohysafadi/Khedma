<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Professional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //  الخطوة الأولى
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => null,
            'status'   => 'pending',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Step 1 completed',
            'user_id' => $user->user_id,
            'token'   => $token,
        ], 201);
    }

    //  الخطوة الثانية
    public function completeBasicInfo(Request $request)
    {
        $data = $request->validate([
            'user_id'        => 'required|exists:users,user_id',
            'role'           => 'required|in:customer,professional',
            'phone'          => 'required|string|max:20',
            'governorate_id' => 'required|integer',
            'city_id'        => 'required_if:role,customer|integer',
        ]);

        $user = User::find($data['user_id']);

        // تحديث جدول users
        $user->update([
            'role'  => $data['role'],
            'phone' => $data['phone'],
        ]);

        // إنشاء سجل customer
        if ($data['role'] === 'customer') {
            Customer::create([
                'user_id'        => $user->user_id,   // ← ← ← هذا هو التعديل الوحيد
                'governorate_id' => $data['governorate_id'],
                'city_id'        => $data['city_id'],
            ]);
        }

        // إنشاء سجل professional
        if ($data['role'] === 'professional') {
            Professional::create([
                'user_id' => $user->user_id,
                'governorate_id'  => $data['governorate_id'],
                'professional_status' => 'pending',
            ]);
        }

        return response()->json([
            'message' => 'Step 2 completed',
            'role'    => $data['role'],
        ]);
    }
    //  الخطوة الثالثة

    public function completeProfessionalInfo(Request $request)
    {
        $data = $request->validate([
            'user_id'          => 'required|exists:users,user_id',
            'category_id'      => 'required|integer|exists:service_categories,category_id',
            'experience_years' => 'required|integer|min:0',
            'bio'              => 'required|string',
            'tool_image'       => 'required|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $professional = Professional::where('user_id', $data['user_id'])->first();

        if (!$professional) {
            return response()->json([
                'message' => 'Basic info not completed yet',
            ], 400);
        }

        // رفع الصورة
        $toolImagePath = $request->file('tool_image')->store('tools', 'public');

        // تحديث السطر
        $professional->update([
            'category_id'      => $data['category_id'],
            'experience_years' => $data['experience_years'],
            'bio'              => $data['bio'],
            'tool_image'       => $toolImagePath,
        ]);

        return response()->json([
            'message' => 'Professional registration completed',
            'tool_image_path' => $toolImagePath
        ]);
    }
    //  تسجيل الدخول
    public function login(Request $request)
    {
        //  التحقق من البيانات
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        //  محاولة تسجيل الدخول
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }

        //  جلب المستخدم بعد نجاح تسجيل الدخول
        $user = Auth::user();

        // 4) إنشاء توكن جديد
        $token = $user->createToken('auth_token')->plainTextToken;

        //  إرجاع الرد
        return response()->json([
            'message' => 'Login successful',
            'user_id' => $user->user_id,
            'role'    => $user->role,
            'token'   => $token,
        ]);
    }
}
