<?php

namespace App\Http\Controllers;

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
        $professional = $request->user()->professional;

        if (!$professional) {
            return response()->json(['message' => 'هذا المستخدم ليس مهني'], 403);
        }

        $wallet = $professional->wallet;

        $transactions = WalletTransaction::where('wallet_id', $wallet->wallet_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($t) {
                return [
                    'date'   => Carbon::parse($t->created_at)->format('Y-m-d'),
                    'type'   => $t->type,
                    'amount' => $t->amount,
                ];
            });

        return response()->json([
            'transactions' => $transactions
        ]);
    }
}
