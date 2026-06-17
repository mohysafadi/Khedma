<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Offer;
use App\Models\ServiceRequest;
use App\Http\Resources\OfferResource;

class OfferController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 1) تقديم عرض جديد (Submit Offer)
    |--------------------------------------------------------------------------
    | يستخدمه المهني لإرسال عرض على طلب خدمة معيّن.
    */
    public function store($request_id, Request $request)
    {
        $professional = $request->user()->professional;

        if (!$professional) {
            return response()->json(['message' => 'هذا المستخدم ليس مهني'], 403);
        }

        $sr = ServiceRequest::findOrFail($request_id);

        // التحقق أن الطلب بنفس محافظة وتصنيف المهني
        if (
            $sr->category_id != $professional->category_id ||
            $sr->customer->governorate_id != $professional->governorate_id
        ) {
            return response()->json(['message' => 'غير مسموح لك بتقديم عرض على هذا الطلب'], 403);
        }

        $data = $request->validate([
            'description'   => 'required|string',
            'delivery_time' => 'required|string'
        ]);

        $offer = Offer::create([
            'professional_id' => $professional->professional_id,
            'request_id'      => $sr->request_id,
            'description'     => $data['description'],
            'delivery_time'   => $data['delivery_time'],
            'status'          => 'pending'
        ]);

        return response()->json([
            'message' => 'تم إرسال العرض بنجاح',
            'offer'   => new OfferResource($offer)
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | 2) جلب تفاصيل عرض واحد (Show Offer)
    |--------------------------------------------------------------------------
    | يستخدمه الزبون أو المهني لعرض تفاصيل عرض معيّن.
    */
    public function show($offer_id, Request $request)
    {
        $offer = Offer::with(['professional.user', 'request.category'])
            ->where('offer_id', $offer_id)
            ->firstOrFail();

        $user = $request->user();

        $isCustomerOwner     = $offer->request->customer_id === optional($user->customer)->customer_id;
        $isProfessionalOwner = $offer->professional->user_id === $user->id;

        if (!$isCustomerOwner && !$isProfessionalOwner) {
            return response()->json(['message' => 'غير مصرح'], 403);
        }

        return new OfferResource($offer);
    }


    /*
    |--------------------------------------------------------------------------
    | 3) قبول العرض (Accept Offer)
    |--------------------------------------------------------------------------
    | يستخدمه الزبون لقبول عرض معيّن على طلبه.
    */
    public function accept($request_id, $offer_id, Request $request)
    {
        $sr   = ServiceRequest::findOrFail($request_id);
        $user = $request->user();

        if ($sr->customer_id !== optional($user->customer)->customer_id) {
            return response()->json(['message' => 'غير مصرح'], 403);
        }

        $offer = Offer::where('offer_id', $offer_id)
            ->where('request_id', $request_id)
            ->firstOrFail();

        DB::transaction(function () use ($request_id, $offer, $sr) {
            // رفض باقي العروض
            Offer::where('request_id', $request_id)
                ->where('offer_id', '!=', $offer->offer_id)
                ->update(['status' => 'rejected']);

            // قبول العرض المحدد
            $offer->status = 'accepted';
            $offer->save();

            // تحديث حالة الطلب
            $sr->status = 'accepted';
            $sr->save();
        });

        return response()->json([
            'message' => 'تم قبول العرض',
            'offer'   => new OfferResource($offer->fresh())
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | 4) رفض العرض (Reject Offer)
    |--------------------------------------------------------------------------
    | يستخدمه الزبون لرفض عرض معيّن على طلبه.
    */
    public function reject($request_id, $offer_id, Request $request)
    {
        $sr   = ServiceRequest::findOrFail($request_id);
        $user = $request->user();

        if ($sr->customer_id !== optional($user->customer)->customer_id) {
            return response()->json(['message' => 'غير مصرح'], 403);
        }

        $offer = Offer::where('offer_id', $offer_id)
            ->where('request_id', $request_id)
            ->firstOrFail();

        $offer->status = 'rejected';
        $offer->save();

        return response()->json([
            'message' => 'تم رفض العرض',
            'offer'   => new OfferResource($offer)
        ]);
    }
    public function professionalOffers(Request $request)
    {
        $professional = $request->user()->professional;

        if (!$professional) {
            return response()->json(['message' => 'هذا المستخدم ليس مهني'], 403);
        }

        // جلب كل العروض الخاصة بالمهني
        $offers = Offer::with(['request.customer.user', 'request.customer.governorate', 'request.customer.city', 'request.category'])
            ->where('professional_id', $professional->professional_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($offer) {
                return [
                    'date'        => $offer->created_at->format('Y-m-d'),
                    'status'      => $offer->status,
                    'request'     => $offer->request->description,
                    'customer'    => $offer->request->customer->user->name,
                    'location'    => $offer->request->customer->governorate->name . "، " . $offer->request->customer->city->name,
                    'offer_body'  => $offer->description,
                    'delivery_time' => $offer->delivery_time,
                    'category'    => $offer->request->category->name
                ];
            });

        return response()->json([
            'offers' => $offers
        ]);
    }
}
