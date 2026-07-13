@extends('admin.layout')

@section('content')

<div class="card shadow">
    <div class="card-header">
        <h3>تفاصيل المهني رقم {{ $professional->professional_id }}</h3>
    </div>

    <div class="card-body">

        <!-- بيانات المستخدم المرتبط بالمهني -->
        <h4 class="mb-3">بيانات المستخدم</h4>
        <ul class="list-group mb-4">
            <li class="list-group-item"><strong>اسم المستخدم:</strong> {{ $professional->user->name }}</li>
            <li class="list-group-item"><strong>البريد الإلكتروني:</strong> {{ $professional->user->email }}</li>
            <li class="list-group-item"><strong>رقم الهاتف:</strong> {{ $professional->user->phone }}</li>
        </ul>

        <!-- بيانات المهني -->
        <h4 class="mb-3">بيانات المهني</h4>
        <ul class="list-group mb-4">
            <li class="list-group-item"><strong>رقم المهني:</strong> {{ $professional->professional_id }}</li>
            <li class="list-group-item"><strong>سنوات الخبرة:</strong> {{ $professional->experience_years }}</li>
            <li class="list-group-item"><strong>النبذة (Bio):</strong> {{ $professional->bio ?? 'لا يوجد' }}</li>
           
            <li class="list-group-item"><strong>التصنيف:</strong> {{ $professional->rating ?? 'غير متوفر' }}</li>
        </ul>

        <!-- صورة العدة إذا موجودة -->
        <h4 class="mb-3">صورة العدة</h4>
        @if($professional->tool_image)
            <img src="{{ asset('storage/' . $professional->tool_image) }}" 
                 alt="Tool Image" 
                 class="img-fluid rounded mb-4" 
                 style="max-width: 300px;">
        @else
            <div class="alert alert-info">لا توجد صورة للعدة.</div>
        @endif

        <a href="/admin/professionals" class="btn btn-secondary mt-3">رجوع لقائمة المهنيين</a>

    </div>
</div>

@endsection