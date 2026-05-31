<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'            => 'nullable|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'governorate_id'  => 'nullable|integer|exists:governorates,id',
            'city_id'         => 'nullable|integer|exists:cities,id',
        ]);

        $user->update([
            'name'  => $data['name']  ?? $user->name,
            'phone' => $data['phone'] ?? $user->phone,
        ]);

        if ($user->role === 'customer') {
            $user->customer->update([
                'governorate_id' => $data['governorate_id'] ?? $user->customer->governorate_id,
                'city_id'        => $data['city_id']        ?? $user->customer->city_id,
            ]);
        }

        if ($user->role === 'professional') {
            $user->professional->update([
                'governorate_id' => $data['governorate_id'] ?? $user->professional->governorate_id,
            ]);
        }

        return response()->json([
            'message' => 'Profile updated successfully',
            'user'    => $user->fresh()->load(
                'customer.governorate',
                'customer.city',
                'professional.governorate'
            ),
        ]);
    }
    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6',
        ]);

        $user = $request->user();

        if (!\Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'message' => 'كلمة المرور القديمة غير صحيحة'
            ], 400);
        }

        $user->update([
            'password' => bcrypt($request->new_password)
        ]);

        return response()->json([
            'message' => 'تم تغيير كلمة المرور بنجاح'
        ]);
    }
}
