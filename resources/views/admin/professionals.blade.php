@extends('admin.layout')

@section('content')

<div class="card shadow">
    <div class="card-header">
        <h3>قائمة المهنيين</h3>
    </div>

    <div class="card-body">

        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>رقم المهني</th>
                    <th>اسم المهني</th>
                    <th>البريد الإلكتروني</th>
                    <th>رقم الهاتف</th>
                    <th>سنوات الخبرة</th>
                    <th>الحالة</th>
                    <th>النبذة</th>
                    <th>تفاصيل</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($professionals as $pro)
                <tr>
                    <td>{{ $pro->professional_id }}</td>

                    <td>{{ $pro->user?->name }}</td>
                    <td>{{ $pro->user?->email }}</td>
                    <td>{{ $pro->user?->phone }}</td>

                    <td>{{ $pro->experience_years }}</td>

                    <td>
                        <span class="badge 
                            @if($pro->professional_status == 'pending') bg-warning text-dark
                            @elseif($pro->professional_status == 'approved') bg-success
                            @elseif($pro->professional_status == 'rejected') bg-danger
                            @endif
                        ">
                            {{ $pro->professional_status }}
                        </span>
                    </td>

                    <td>{{ $pro->bio ?? 'لا يوجد' }}</td>

                    <td>
                        <a href="/admin/professionals/{{ $pro->professional_id }}"
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