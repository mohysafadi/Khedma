<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Review;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'request_id' => 'required|exists:service_requests,request_id',
            'rating'     => 'required|integer|min:1|max:10',
        ]);

        // المستخدم من جدول users
        $user = $request->user();

        // جلب العميل المرتبط بهذا المستخدم من جدول customers
        $customer = Customer::where('user_id', $user->user_id)->firstOrFail();

        // جلب الطلب
        $serviceRequest = ServiceRequest::findOrFail($data['request_id']);

        // تأكد أن الطلب يعود لهذا العميل
        if ($serviceRequest->customer_id !== $customer->customer_id) {
            return response()->json([
                'message' => 'لا يمكنك تقييم هذا الطلب لأنه لا يعود لك'
            ], 403);
        }

        // تأكد أن الطلب مقبول
        if ($serviceRequest->status !== 'accepted') {
            return response()->json([
                'message' => 'لا يمكن تقييم المهني قبل قبول العرض'
            ], 400);
        }

        // جلب المهني من الطلب
        $professional_id = $serviceRequest->professional_id;

        // منع التقييم المكرر
        $existing = Review::where('customer_id', $customer->customer_id)
            ->where('professional_id', $professional_id)
            ->where('request_id', $data['request_id'])
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'لقد قمت بتقييم هذا الطلب مسبقاً'
            ], 409);
        }

        // إنشاء التقييم
        $review = Review::create([
            'customer_id'     => $customer->customer_id,
            'professional_id' => $professional_id,
            'request_id'      => $data['request_id'],
            'rating'          => $data['rating'],
        ]);

        return response()->json([
            'message' => 'تم إرسال التقييم بنجاح',
            'review'  => $review
        ], 201);
    }

    // جلب متوسط تقييم مهني
    public function average($professional_id)
    {
        $average = Review::where('professional_id', $professional_id)->avg('rating');

        return response()->json([
            'professional_id' => $professional_id,
            'average_rating'  => round($average, 2),
        ]);
    }
}
