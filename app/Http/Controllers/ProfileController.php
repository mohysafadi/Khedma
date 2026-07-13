<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //  تعديل البروفايل
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'            => 'nullable|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'governorate_id'  => 'nullable|integer|exists:governorates,governorate_id',
            'city_id'         => 'nullable|integer|exists:cities,city_id',

            // خاص بالمهني فقط
            'experience_years' => 'nullable|integer|min:0',
            'bio'              => 'nullable|string|max:500',
        ]);

        // تحديث بيانات المستخدم الأساسية
        $user->update([
            'name'  => $data['name']  ?? $user->name,
            'phone' => $data['phone'] ?? $user->phone,
        ]);

        // تحديث بيانات الزبون
        if ($user->role === 'customer') {
            $user->customer->update([
                'governorate_id' => $data['governorate_id'] ?? $user->customer->governorate_id,
                'city_id'        => $data['city_id']        ?? $user->customer->city_id,
            ]);
        }

        // تحديث بيانات المهني
        if ($user->role === 'professional') {
            $user->professional->update([
                'governorate_id'   => $data['governorate_id']   ?? $user->professional->governorate_id,
                'experience_years' => $data['experience_years'] ?? $user->professional->experience_years,
                'bio'              => $data['bio']              ?? $user->professional->bio,
            ]);
        }

        return response()->json([
            'message' => 'تم تحديث البروفايل بنجاح',
            'user'    => $user->fresh()->load(
                'customer.governorate',
                'customer.city',
                'professional.governorate',
                'professional.category'
            ),
        ]);
    }

    //  تغيير كلمة السر
    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6',
        ]);

        $user = $request->user();

        if (!\Hash::check($request->old_password, $user->password)) {
            return response()->json(['message' => 'كلمة المرور القديمة غير صحيحة'], 400);
        }

        $user->update([
            'password' => bcrypt($request->new_password)
        ]);

        return response()->json(['message' => 'تم تغيير كلمة المرور بنجاح']);
    }
}
