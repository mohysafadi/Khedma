<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Offer;
use App\Models\ServiceRequest;
use App\Http\Resources\OfferResource;
use App\Models\WalletTransaction;

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

        if (
            $sr->category_id != $professional->category_id ||
            $sr->customer->governorate_id != $professional->governorate_id
        ) {
            return response()->json(['message' => 'غير مسموح لك بتقديم عرض على هذا الطلب'], 403);
        }

        // ✔ التحقق من الحقول الصحيحة
        $data = $request->validate([
            'description' => 'required|string',
            'duration'    => 'required|string',
            'price'       => 'required|numeric'
        ]);

        // ✔ إنشاء العرض بالحقول الصحيحة
        $offer = Offer::create([
            'professional_id' => $professional->professional_id,
            'request_id'      => $sr->request_id,
            'description'     => $data['description'],
            'duration'        => $data['duration'],
            'price'           => $data['price'],
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

        // التحقق أن الزبون هو صاحب الطلب
        if ($sr->customer_id !== optional($user->customer)->customer_id) {
            return response()->json(['message' => 'غير مصرح'], 403);
        }

        // جلب العرض المطلوب
        $offer = Offer::where('offer_id', $offer_id)
            ->where('request_id', $request_id)
            ->firstOrFail();

        // ================================
        // 🔥 منع قبول العرض إذا رصيد المهني أقل من 100
        // ================================
        $professional = $offer->professional;
        $wallet = $professional->wallet;

        if (!$wallet || $wallet->balance < 100) {
            return response()->json([
                'message' => 'لا يمكن قبول العرض — رصيد المهني أقل من 100 ليرة سورية'
            ], 403);
        }

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

            // ================================
            // 🔥 خصم العمولة من محفظة المهني
            // ================================
            $professional = $offer->professional;
            $wallet = $professional->wallet;

            if ($wallet) {
                $wallet->balance -= 100;
                $wallet->save();

                WalletTransaction::create([
                    'wallet_id' => $wallet->wallet_id,
                    'type'      => 'withdrawal',
                    'amount'    => 100,
                    'note'      => 'خصم عمولة قبول العرض'
                ]);
            }

            // ================================
            // 🔥 إنشاء محادثة بين الزبون والمهني
            // ================================
            DB::table('request_chats')->insert([
                'request_id'      => $sr->request_id,
                'customer_id'     => $sr->customer_id,
                'professional_id' => $offer->professional_id,
                'created_at'      => now(),
                'updated_at'      => now()
            ]);
        });

        return response()->json([
            'message' => 'تم قبول العرض وتم خصم العمولة',
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
                    'duration' => $offer->duration,
                    'category'    => $offer->request->category->name
                ];
            });

        return response()->json([
            'offers' => $offers
        ]);
    }
}
