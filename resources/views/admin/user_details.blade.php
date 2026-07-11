@extends('admin.layout')

@section('content')

<div class="card shadow">
    <div class="card-header">
        <h3>تفاصيل المستخدم رقم {{ $user->user_id }}</h3>
    </div>

    <div class="card-body">

        <ul class="list-group mb-4">
            <li class="list-group-item"><strong>اسم المستخدم:</strong> {{ $user->name }}</li>
            <li class="list-group-item"><strong>البريد الإلكتروني:</strong> {{ $user->email }}</li>
            <li class="list-group-item"><strong>رقم الهاتف:</strong> {{ $user->phone }}</li>
            <li class="list-group-item"><strong>الدور:</strong> {{ $user->role }}</li>
        </ul>

        <a href="/admin/users" class="btn btn-secondary">رجوع لقائمة المستخدمين</a>
        <a href="/admin/users/{{ $user->user_id }}/actions" class="btn btn-danger mt-3">
            إدارة الحظر والتقييد
        </a>
    </div>
</div>

@endsection