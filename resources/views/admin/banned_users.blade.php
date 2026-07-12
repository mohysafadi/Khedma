@extends('admin.layout')

@section('content')

<div class="container">

    <h2 class="mb-4">المستخدمون المحظورون</h2>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>اسم المستخدم</th>
                <th>البريد الإلكتروني</th>
                <th>سبب الحظر</th>
                <th>تاريخ الحظر</th>
                <th>ينتهي في</th>
                <th>إجراءات</th>
            </tr>
        </thead>

        <tbody>
            @foreach($bans as $ban)
                <tr>
                    <td>{{ $ban->user->name }}</td>
                    <td>{{ $ban->user->email }}</td>
                    <td>{{ $ban->reason ?? 'غير محدد' }}</td>
                    <td>{{ $ban->created_at }}</td>
                    <td>{{ $ban->expires_at ?? 'غير منتهي' }}</td>

                    <td>
                        <form action="/admin/users/unban" method="POST">
                            @csrf
                            <input type="hidden" name="ban_id" value="{{ $ban->id }}">
                            <button class="btn btn-danger btn-sm">رفع الحظر</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

</div>

@endsection