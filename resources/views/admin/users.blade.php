@extends('admin.layout')

@section('content')

<div class="card shadow">
    <div class="card-header">
        <h3>قائمة المستخدمين</h3>
    </div>

    <div class="card-body">

        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>رقم المستخدم</th>
                    <th>اسم المستخدم</th>
                    <th>البريد الإلكتروني</th>
                    <th>رقم الهاتف</th>
                    <th>الدور</th>
                    <th>تفاصيل</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user->user_id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>

                    <td>
                        <span class="badge 
                            @if($user->role == 'customer') bg-primary
                            @elseif($user->role == 'professional') bg-success
                            @elseif($user->role == 'admin') bg-dark
                            @endif
                        ">
                            {{ $user->role }}
                        </span>
                    </td>

                    <td>
                        <a href="/admin/users/{{ $user->user_id }}" 
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