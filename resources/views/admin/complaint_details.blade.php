@extends('admin.layout')

@section('content')

<div class="card shadow">
    <div class="card-header">
        <h3>تفاصيل الشكوى رقم {{ $complaint->complaint_id }}</h3>
    </div>

    <div class="card-body">

        <h4 class="mb-3">معلومات الشكوى</h4>
        <ul class="list-group mb-4">
            <li class="list-group-item"><strong>رقم الشكوى:</strong> {{ $complaint->complaint_id }}</li>
            <li class="list-group-item"><strong>نص الشكوى:</strong> {{ $complaint->message }}</li>
            <li class="list-group-item"><strong>الحالة:</strong> {{ $complaint->status }}</li>
            <li class="list-group-item"><strong>تاريخ الإنشاء:</strong> {{ $complaint->created_at }}</li>
        </ul>

        <h4 class="mb-3">معلومات الطلب المرتبط</h4>
        <ul class="list-group mb-4">
            <li class="list-group-item"><strong>رقم الطلب:</strong> {{ $complaint->request->request_id }}</li>
            <li class="list-group-item"><strong>وصف الطلب:</strong> {{ $complaint->request->description }}</li>
            <li class="list-group-item"><strong>حالة الطلب:</strong> {{ $complaint->request->status }}</li>
            <li class="list-group-item"><strong>تاريخ الطلب:</strong> {{ $complaint->request->created_at }}</li>
        </ul>

        <h4 class="mb-3">معلومات المستخدم</h4>
        <ul class="list-group mb-4">
            <li class="list-group-item"><strong>رقم المستخدم:</strong> {{ $complaint->user->user_id }}</li>
            <li class="list-group-item"><strong>اسم المستخدم:</strong> {{ $complaint->user->name }}</li>
            <li class="list-group-item"><strong>البريد الإلكتروني:</strong> {{ $complaint->user->email }}</li>
            <li class="list-group-item"><strong>رقم الهاتف:</strong> {{ $complaint->user->phone }}</li>
        </ul>

        <h4 class="mb-3">معلومات المهني (العرض المقبول)</h4>

        @if($complaint->request->acceptedOffer && $complaint->request->acceptedOffer->professional)
        <ul class="list-group mb-4">
            <li class="list-group-item"><strong>رقم المهني:</strong> {{ $complaint->request->acceptedOffer->professional->professional_id }}</li>

            <li class="list-group-item"><strong>اسم المهني:</strong> 
                {{ $complaint->request->acceptedOffer->professional->user->name }}
            </li>

            <li class="list-group-item"><strong>هاتف المهني:</strong> 
                {{ $complaint->request->acceptedOffer->professional->user->phone }}
            </li>

            <li class="list-group-item"><strong>البريد الإلكتروني:</strong> 
                {{ $complaint->request->acceptedOffer->professional->user->email }}
            </li>

            <li class="list-group-item"><strong>سنوات الخبرة:</strong> 
                {{ $complaint->request->acceptedOffer->professional->experience_years }}
            </li>

            <li class="list-group-item"><strong>النبذة (Bio):</strong> 
                {{ $complaint->request->acceptedOffer->professional->bio }}
            </li>
        </ul>
        @else
        <div class="alert alert-warning">لا يوجد عرض مقبول لهذا الطلب.</div>
        @endif

        <a href="/admin/complaints/{{ $complaint->complaint_id }}/edit" class="btn btn-warning">
            تعديل حالة الشكوى
        </a>

        <a href="/admin/complaints" class="btn btn-secondary">
            رجوع
        </a>

    </div>
</div>

@endsection