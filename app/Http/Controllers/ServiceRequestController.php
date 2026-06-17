<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Http\Resources\ServiceRequestResource;
use App\Http\Resources\ServiceRequestDetailsResource;

class ServiceRequestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 1) إنشاء طلب جديد (Store)
    |--------------------------------------------------------------------------
    | يستخدمه الزبون لإرسال طلب خدمة جديد.
    */
    public function store(Request $request)
    {
        $customer = $request->user()->customer;

        if (!$customer) {
            return response()->json(['message' => 'هذا المستخدم ليس زبون'], 403);
        }

        $data = $request->validate([
            'category_id' => 'required|exists:service_categories,category_id',
            'description' => 'required|string',
            'address'     => 'required|string',
            'photo'       => 'nullable|image'
        ]);

        $photoPath = $request->hasFile('photo')
            ? $request->file('photo')->store('service_requests', 'public')
            : null;

        $sr = ServiceRequest::create([
            'customer_id' => $customer->customer_id,
            'category_id' => $data['category_id'],
            'description' => $data['description'],
            'address'     => $data['address'],
            'photo'       => $photoPath,
            'status'      => 'pending'
        ]);

        return response()->json([
            'message' => 'تم إرسال الطلب بنجاح',
            'request' => new ServiceRequestResource($sr)
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | 2) جلب طلبات الزبون (Index)
    |--------------------------------------------------------------------------
    | يستخدمه الزبون لعرض كل الطلبات الخاصة فيه.
    */
    public function index(Request $request)
    {
        $customer = $request->user()->customer;

        if (!$customer) {
            return response()->json(['message' => 'هذا المستخدم ليس زبون'], 403);
        }

        $requests = ServiceRequest::where('customer_id', $customer->customer_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return ServiceRequestResource::collection($requests);
    }


    /*
    |--------------------------------------------------------------------------
    | 3) تفاصيل طلب معيّن (Show)
    |--------------------------------------------------------------------------
    | يستخدمه الزبون لعرض تفاصيل طلبه.
    */
    public function show($id, Request $request)
    {
        $sr = ServiceRequest::with(['category', 'offers.professional.user'])
            ->findOrFail($id);

        if ($sr->customer_id != optional($request->user()->customer)->customer_id) {
            return response()->json(['message' => 'غير مسموح لك بعرض هذا الطلب'], 403);
        }

        return new ServiceRequestDetailsResource($sr);
    }


    /*
    |--------------------------------------------------------------------------
    | 4) تفاصيل طلب للمهني (Professional Show)
    |--------------------------------------------------------------------------
    | يستخدمه المهني لعرض تفاصيل طلب متاح ضمن محافظته وتصنيفه.
    */
    public function professionalShow($id, Request $request)
    {
        $professional = $request->user()->professional;

        if (!$professional) {
            return response()->json(['message' => 'هذا المستخدم ليس مهني'], 403);
        }

        $sr = ServiceRequest::with(['customer.user', 'customer.governorate', 'customer.city', 'category'])
            ->findOrFail($id);

        if (
            $sr->category_id != $professional->category_id ||
            $sr->customer->governorate_id != $professional->governorate_id
        ) {
            return response()->json(['message' => 'غير مسموح لك بعرض هذا الطلب'], 403);
        }

        return response()->json([
            'request_id'  => $sr->request_id,
            'customer'    => $sr->customer->user->name,
            'location'    => $sr->customer->governorate->name . "، " . $sr->customer->city->name,
            'date'        => $sr->created_at->format('Y-m-d'),
            'description' => $sr->description,
            'photo'       => $sr->photo ? asset('storage/' . $sr->photo) : null,
            'category'    => $sr->category->name
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | 5) جلب الطلبات المتاحة للمهني (Professional Requests)
    |--------------------------------------------------------------------------
    | يستخدمه المهني لعرض قائمة الطلبات ضمن محافظته وتصنيفه.
    */
    public function professionalRequests(Request $request)
    {
        $professional = $request->user()->professional;

        if (!$professional) {
            return response()->json(['message' => 'هذا المستخدم ليس مهني'], 403);
        }

        $governorateId = $professional->governorate_id;
        $categoryId    = $professional->category_id;

        $requests = ServiceRequest::with(['customer.user', 'customer.governorate', 'customer.city', 'category'])
            ->where('category_id', $categoryId)
            ->where('status', 'pending')
            ->whereHas('customer', function ($q) use ($governorateId) {
                $q->where('governorate_id', $governorateId);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($req) {
                return [
                    'request_id'  => $req->request_id,
                    'customer'    => $req->customer->user->name,
                    'description' => $req->description,
                    'location'    => $req->customer->governorate->name . "، " . $req->customer->city->name,
                    'photo'       => $req->photo ? asset('storage/' . $req->photo) : null,
                    'created_at'  => $req->created_at->format('Y-m-d'),
                    'category'    => $req->category->name
                ];
            });

        return response()->json([
            'requests' => $requests
        ]);
    }
}