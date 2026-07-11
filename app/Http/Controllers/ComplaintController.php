<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function submit(Request $request)
    {
        $request_id = $request->input('request_id');
        $message    = $request->input('message');

        $request->validate([
            'request_id' => 'required|exists:service_requests,request_id',
            'message'    => 'required|string',
        ]);

        $customerId = $request->user()->customer->customer_id;

        $serviceRequest = ServiceRequest::where('request_id', $request_id)
            ->where('customer_id', $customerId)
            ->first();

        if (!$serviceRequest) {
            return response()->json([
                'message' => 'لا يمكنك تقديم شكوى على طلب لا يعود لك'
            ], 403);
        }

        // إنشاء الشكوى
        $complaint = Complaint::create([
            'user_id'     => $request->user()->user_id,
            'customer_id' => $customerId,
            'request_id'  => $request_id,
            'message'     => $message,
            'status'      => 'pending',
        ]);

        // الرد الجديد
        return response()->json([
            'message'       => 'تم إرسال الشكوى بنجاح',
            'request_id'    => $complaint->request_id,
            'complaint_id'  => $complaint->complaint_id,
            'complaint_msg' => $complaint->message
        ]);
    }

    // جلب الشكاوي للأدمن
    public function index()
    {
        $complaints = Complaint::with(['user', 'customer', 'request'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'complaints' => $complaints
        ]);
    }

    // تحديث حالة الشكوى
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $complaint = Complaint::findOrFail($id);
        $complaint->status = $request->status;
        $complaint->save();

        return response()->json(['message' => 'تم تحديث حالة الشكوى']);
    }
    public function webIndex()
    {
        // تأكد إنو في أدمن مسجل دخول
        if (!session()->has('admin_id')) {
            return redirect()->route('admin.login');
        }

        $complaints = Complaint::with(['user', 'customer', 'request'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.complaints', compact('complaints'));
    }
    public function editPage($id)
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('admin.login');
        }

        $complaint = Complaint::findOrFail($id);

        return view('admin.edit_complaint', compact('complaint'));
    }
    public function show($id)
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('admin.login');
        }

        $complaint = Complaint::with([
            'user',
            'request.acceptedOffer.professional.user'
        ])->findOrFail($id);
        return view('admin.complaint_details', compact('complaint'));
    }
    public function updateStatusWeb(Request $request, $id)
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'status' => 'required|string'
        ]);

        $complaint = Complaint::findOrFail($id);
        $complaint->status = $request->status;
        $complaint->save();

        return redirect('/admin/complaints')->with('success', 'تم تحديث حالة الشكوى بنجاح');
    }
}
