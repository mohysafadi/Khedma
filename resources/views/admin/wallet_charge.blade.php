@extends('admin.layout')

@section('content')

<div class="card shadow">
    <div class="card-header">
        <h3>شحن رصيد مهني</h3>
    </div>

    <div class="card-body">

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="/admin/wallet/charge" method="POST">
            @csrf

            <div class="mb-3">
                <label>رقم هاتف المهني</label>
                <input type="text" name="phone" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>المبلغ المراد شحنه</label>
                <input type="number" name="amount" class="form-control" required>
            </div>

            <button class="btn btn-primary">شحن الرصيد</button>
        </form>

        <a href="/admin/dashboard" class="btn btn-secondary mt-3">رجوع للوحة التحكم</a>

    </div>
</div>

@endsection