<?php

namespace App\Http\Controllers;

use App\Models\Professional;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use Carbon\Carbon;

class WalletController extends Controller
{
    // جلب رصيد المحفظة
    public function show(Request $request)
    {
        $professional = $request->user()->professional;

        if (!$professional) {
            return response()->json(['message' => 'هذا المستخدم ليس مهني'], 403);
        }

        $wallet = $professional->wallet;

        return response()->json([
            'balance' => $wallet->balance,
        ]);
    }

    // جلب سجل العمليات
    public function transactions(Request $request)
    {
        $user = $request->user();

        // جلب المهني المرتبط بالمستخدم
        $professional = Professional::where('user_id', $user->user_id)->first();

        if (!$professional) {
            return response()->json(['message' => 'هذا المستخدم ليس مهني'], 403);
        }

        // جلب المحفظة
        $wallet = Wallet::where('professional_id', $professional->professional_id)->first();

        if (!$wallet) {
            return response()->json(['transactions' => []]);
        }

        // جلب المعاملات
        $transactions = WalletTransaction::where('wallet_id', $wallet->wallet_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($t) {
                return [
                    'date'   => date('Y-m-d', strtotime($t->created_at)), // 🔥 الحل
                    'type'   => $t->type,
                    'amount' => $t->amount,
                ];
            });

        return response()->json([
            'transactions' => $transactions
        ]);
    }
}
