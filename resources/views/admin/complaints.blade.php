@extends('admin.layout')

@section('content')

<div class="card shadow">
    <div class="card-header">
        <h3>قائمة الشكاوي</h3>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>رقم الشكوى</th>
                    <th>رقم الطلب</th>
                    <th>اسم المستخدم</th>
                    <th>هاتف المستخدم</th>
                    <th>نص الشكوى</th>
                    <th>الحالة</th>
                    <th>تاريخ الإنشاء</th>
                    <th>تفاصيل</th>
                    <th>تعديل</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($complaints as $complaint)
                <tr>
                    <td>{{ $complaint->complaint_id }}</td>
                    <td>{{ $complaint->request_id }}</td>

                    <!-- معلومات المستخدم -->
                    <td>{{ $complaint->user?->name }}</td>
                    <td>{{ $complaint->user?->phone }}</td>

                    <td>{{ $complaint->message }}</td>

                    <td>
                        <span class="badge 
                            @if($complaint->status == 'pending') bg-warning text-dark
                            @elseif($complaint->status == 'in_review') bg-info text-dark
                            @elseif($complaint->status == 'resolved') bg-success
                            @endif
                        ">
                            {{ $complaint->status }}
                        </span>
                    </td>

                    <td>{{ $complaint->created_at }}</td>

                    <!-- زر التفاصيل -->
                    <td>
                        <a href="/admin/complaints/{{ $complaint->complaint_id }}" 
                           class="btn btn-sm btn-primary">
                            عرض التفاصيل
                        </a>
                    </td>

                    <!-- زر تعديل الحالة -->
                    <td>
                        <a href="/admin/complaints/{{ $complaint->complaint_id }}/edit" 
                           class="btn btn-sm btn-warning">
                            تعديل
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