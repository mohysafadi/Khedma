@extends('admin.layout')

@section('content')

<div class="card shadow">
    <div class="card-header">
        <h3>تفاصيل الطلب رقم {{ $request->request_id }}</h3>
    </div>

    <div class="card-body">

        <h4 class="mb-3">بيانات الزبون</h4>
        <ul class="list-group mb-4">
            <li class="list-group-item"><strong>اسم الزبون:</strong> {{ $request->customer->user->name }}</li>
            <li class="list-group-item"><strong>البريد الإلكتروني:</strong> {{ $request->customer->user->email }}</li>
            <li class="list-group-item"><strong>رقم الهاتف:</strong> {{ $request->customer->user->phone }}</li>
        </ul>

        <h4 class="mb-3">بيانات الطلب</h4>
        <ul class="list-group mb-4">
            <li class="list-group-item"><strong>الوصف:</strong> {{ $request->description }}</li>
            <li class="list-group-item"><strong>الحالة:</strong> {{ $request->status }}</li>
            <li class="list-group-item"><strong>تاريخ الإنشاء:</strong> {{ $request->created_at }}</li>
        </ul>

        <h4 class="mb-3">العرض المقبول</h4>

        @if($request->acceptedOffer)
        <ul class="list-group mb-4">
            <li class="list-group-item"><strong>السعر:</strong> {{ $request->acceptedOffer->price }}</li>
            <li class="list-group-item"><strong>المدة:</strong> {{ $request->acceptedOffer->duration }}</li>
            <li class="list-group-item"><strong>الوصف:</strong> {{ $request->acceptedOffer->description }}</li>
        </ul>

        <h4 class="mb-3">المهني</h4>
        <ul class="list-group mb-4">
            <li class="list-group-item"><strong>اسم المهني:</strong> {{ $request->acceptedOffer->professional->user->name }}</li>
            <li class="list-group-item"><strong>رقم الهاتف:</strong> {{ $request->acceptedOffer->professional->user->phone }}</li>
            <li class="list-group-item"><strong>البريد الإلكتروني:</strong> {{ $request->acceptedOffer->professional->user->email }}</li>
        </ul>

        @else
        <div class="alert alert-info">لا يوجد عرض مقبول لهذا الطلب.</div>
        @endif

        <a href="/admin/service-requests" class="btn btn-secondary">رجوع لقائمة الطلبات</a>

    </div>
</div>

@endsection