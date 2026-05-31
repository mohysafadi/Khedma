<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRequest;

class ServiceRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:service_categories,category_id',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'photo' => 'required|image|max:4096'
        ]);

        // رفع الصورة
        $photoPath = $request->file('photo')->store('service_requests', 'public');

        // إنشاء الطلب
        $serviceRequest = ServiceRequest::create([
            'customer_id' => $request->user()->customer->customer_id,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'address' => $request->address,
            'photo' => $photoPath,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'تم إرسال الطلب بنجاح',
            'request' => $serviceRequest
        ]);
    }
}
