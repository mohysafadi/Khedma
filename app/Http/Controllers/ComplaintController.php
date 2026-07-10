<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    // إرسال شكوى من المستخدم
    public function submit(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string',
        ]);

        Complaint::create([
            'user_id' => $request->user()->id,   // إذا عندك user_id بدل id خبرني
            'message' => $data['message'],
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'تم إرسال الشكوى بنجاح']);
    }

    // جلب الشكاوي للأدمن
    public function index()
    {
        $complaints = Complaint::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'complaints' => $complaints
        ]);
    }

    // تغيير حالة الشكوى
    public function updateStatus(Request $request, $id)
    {
        $data = $request->validate([
            'status' => 'required|string', // pending, in_review, resolved
        ]);

        $complaint = Complaint::findOrFail($id);
        $complaint->status = $data['status'];
        $complaint->save();

        return response()->json(['message' => 'تم تحديث حالة الشكوى']);
    }
}
