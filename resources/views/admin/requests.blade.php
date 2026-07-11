@extends('admin.layout')

@section('content')

<div class="card shadow">
    <div class="card-header">
        <h3>قائمة الطلبات</h3>
    </div>

    <div class="card-body">

        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>رقم الطلب</th>
                    <th>اسم الزبون</th>
                    <th>رقم الهاتف</th>
                    <th>الوصف</th>
                    <th>الحالة</th>
                    <th>تاريخ الإنشاء</th>
                    <th>تفاصيل</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($requests as $req)
                <tr>
                    <td>{{ $req->request_id }}</td>

                    <!-- بيانات الزبون -->
                    <td>{{ $req->customer->user->name }}</td>
                    <td>{{ $req->customer->user->phone }}</td>

                    <td>{{ $req->description }}</td>

                    <td>
                        <span class="badge 
                            @if($req->status == 'pending') bg-warning text-dark
                            @elseif($req->status == 'accepted') bg-success
                            @elseif($req->status == 'rejected') bg-danger
                            @elseif($req->status == 'completed') bg-primary
                            @endif
                        ">
                            {{ $req->status }}
                        </span>
                    </td>

                    <td>{{ $req->created_at }}</td>

                    <td>
                        <a href="/admin/service-requests/{{ $req->request_id }}" 
                           class="btn btn-sm btn-primary">
                            عرض التفاصيل
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <a href="/admin/dashboard" class="btn btn-secondary mt-3">رجوع للوحة التحكم</a>

    </div>
</div>

@endsection