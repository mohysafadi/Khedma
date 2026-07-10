<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    //  الخطوة الأولى
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        // إنشاء المستخدم
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
            'role'     => null
        ]);

        // إنشاء التوكن مباشرة بعد التسجيل
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'تم إنشاء الحساب بنجاح - الخطوة 1',
            'step'    => 1,
            'token'   => $token,
            'user'    => $user,
            'input'   => $data
        ]);
    }
    //  الخطوة الثانية
    public function completeBasicInfo(Request $request)
    {
        $data = $request->validate([
            'user_id'        => 'required|exists:users,user_id',
            'role'           => 'required|in:customer,professional',
            'phone'          => 'required|string|max:20',
            'governorate_id' => 'required|exists:governorates,governorate_id',
            'city_id'        => 'nullable|exists:cities,city_id'
        ]);

        $user = User::findOrFail($data['user_id']);

        // تحديث بيانات المستخدم الأساسية
        $user->update([
            'role'  => $data['role'],
            'phone' => $data['phone']
        ]);

        if ($data['role'] === 'customer') {

            // إنشاء سجل الزبون
            $customer = $user->customer()->create([
                'governorate_id' => $data['governorate_id'],
                'city_id'        => $data['city_id']
            ]);

            $professional = null;
        } else {

            // إنشاء سجل المهني
            $professional = $user->professional()->create([
                'governorate_id' => $data['governorate_id']
            ]);

            // إنشاء محفظة جديدة للمهني مع رصيد ابتدائي 100 ليرة سورية
            $professional->wallet()->create([
                'balance' => 100
            ]);

            $customer = null;
        }

        return response()->json([
            'message'      => 'تم إدخال المعلومات الأساسية - الخطوة 2',
            'step'         => 2,
            'user'         => $user,
            'customer'     => $customer,
            'professional' => $professional,
            'input'        => $data
        ]);
    }
    //  الخطوة الثالثة

    public function completeProfessionalInfo(Request $request)
    {
        $data = $request->validate([
            'user_id'         => 'required|exists:users,user_id',
            'category_id'     => 'required|exists:service_categories,category_id',
            'experience_years' => 'required|integer|min:0',
            'bio'             => 'required|string|max:500',
            'tool_image'      => 'nullable|image'
        ]);

        $user = User::findOrFail($data['user_id']);

        if (!$user->professional) {
            return response()->json(['message' => 'هذا المستخدم ليس مهني'], 403);
        }

        $imagePath = $request->hasFile('tool_image')
            ? $request->file('tool_image')->store('tools', 'public')
            : null;

        $user->professional->update([
            'category_id'     => $data['category_id'],
            'experience_years' => $data['experience_years'],
            'bio'             => $data['bio'],
            'tool_image'      => $imagePath
        ]);

        return response()->json([
            'message'      => 'تم إدخال معلومات المهني - الخطوة 3',
            'step'         => 3,
            'user'         => $user->fresh()->load('professional'),
            'professional' => $user->professional,
            'input'        => $data
        ]);
    }
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // جلب المستخدم حسب الإيميل
        $user = User::where('email', $data['email'])->first();

        // التحقق من وجود المستخدم وصحة كلمة المرور
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة'
            ], 401);
        }

        // إنشاء توكن جديد
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'token' => $token,
            'user' => $user
        ]);
    }
}
