@extends('admin.layout')

@section('content')

<div class="card shadow">
    <div class="card-header">
        <h3>إدارة المستخدم رقم {{ $user->user_id }}</h3>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <h4>حظر المستخدم</h4>
        <form action="/admin/users/ban" method="POST" class="mb-4">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->user_id }}">

            <div class="mb-3">
                <label>سبب الحظر</label>
                <input type="text" name="reason" class="form-control">
            </div>

            <div class="mb-3">
                <label>ينتهي في (اختياري)</label>
                <input type="datetime-local" name="expires_at" class="form-control">
            </div>

            <button class="btn btn-danger">حظر المستخدم</button>
        </form>

        <hr>

        <h4>تقييد المستخدم</h4>
        <form action="/admin/users/restrict" method="POST">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->user_id }}">

            <div class="mb-3">
                <label>نوع التقييد</label>
                <select name="type" class="form-control">
                    <option value="block_offers">منع إرسال عروض</option>
                    <option value="block_accept">منع قبول عروض</option>
                    <option value="block_requests">منع إنشاء طلبات</option>
                </select>
            </div>

            <div class="mb-3">
                <label>ينتهي في</label>
                <input type="datetime-local" name="expires_at" class="form-control">
            </div>

            <button class="btn btn-warning">تقييد المستخدم</button>
        </form>

        <a href="/admin/users" class="btn btn-secondary mt-3">رجوع لقائمة المستخدمين</a>

    </div>
</div>

@endsection