<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use App\Models\User;
use App\Models\Complaint;
use App\Models\ServiceRequest;
use App\Models\Professional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\WalletTransaction;


class AdminController extends Controller
{
    public function loginPage()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password) || $user->role !== 'admin') {
            return back()->withErrors(['error' => 'بيانات تسجيل الدخول غير صحيحة أو المستخدم ليس أدمن']);
        }

        session(['admin_id' => $user->user_id]);

        return redirect('/admin/dashboard');
    }

    public function dashboard()
    {
        $stats = [
            'complaints' => Complaint::count(),
            'requests' => ServiceRequest::count(),
            'professionals' => Professional::count(),
            'users' => User::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
    public function professionals()
    {
        $professionals = Professional::with('user')->get();
        return view('admin.professionals', compact('professionals'));
    }
    public function professionalDetails($id)
    {
        $professional = Professional::with('user')->findOrFail($id);

        // حساب متوسط التقييم
        $average = \App\Models\Review::where('professional_id', $professional->professional_id)->avg('rating');

        // إذا ما في تقييمات خلي الديفولت 5
        if ($average === null) {
            $average = 5;
        }

        return view('admin.professional_details', [
            'professional' => $professional,
            'average' => round($average, 2)
        ]);
    }
    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function userDetails($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user_details', compact('user'));
    }
    public function requests()
    {
        $requests = ServiceRequest::with(['customer.user'])->get();
        return view('admin.requests', compact('requests'));
    }

    public function requestDetails($id)
    {
        $request = ServiceRequest::with(['customer.user', 'acceptedOffer.professional.user'])->findOrFail($id);
        return view('admin.request_details', compact('request'));
    }
    public function banUser(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'reason' => 'nullable|string',
            'expires_at' => 'nullable|date'
        ]);

        Ban::create($data);

        return back()->with('success', 'تم حظر المستخدم بنجاح');
    }



    public function chargeWallet(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|numeric|min:1'
        ]);

        // البحث عن المستخدم حسب رقم الهاتف
        $user = User::where('phone', $data['phone'])->first();

        if (!$user) {
            return back()->with('error', 'لا يوجد مستخدم بهذا الرقم');
        }

        // التأكد أنه مهني
        $professional = $user->professional;

        if (!$professional) {
            return back()->with('error', 'هذا المستخدم ليس مهني');
        }

        // جلب المحفظة
        $wallet = $professional->wallet;

        if (!$wallet) {
            return back()->with('error', 'لا توجد محفظة لهذا المهني');
        }

        // شحن الرصيد
        $wallet->balance += $data['amount'];
        $wallet->save();

        // تسجيل العملية
        WalletTransaction::create([
            'wallet_id' => $wallet->wallet_id,
            'type' => 'deposit',
            'amount' => $data['amount']
        ]);

        return back()->with('success', 'تم شحن الرصيد بنجاح');
    }
    public function bannedUsers()
    {
        $bans = \App\Models\Ban::with('user')->get();

        return view('admin.banned_users', compact('bans'));
    }
    public function unbanUser(Request $request)
    {
        $ban = \App\Models\Ban::find($request->ban_id);

        if ($ban) {
            $ban->delete();
        }

        return back()->with('success', 'تم رفع الحظر عن المستخدم');
    }
}
