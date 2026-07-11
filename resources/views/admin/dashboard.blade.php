@extends('admin.layout')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4">مرحباً بك في لوحة التحكم</h2>

    <!-- الصف الأول من الإحصائيات -->
    <div class="row">

        <div class="col-md-3 mb-3">
            <div class="card text-center shadow">
                <div class="card-header">
                    عدد الشكاوي
                </div>
                <div class="card-body">
                    <h3>{{ $stats['complaints'] }}</h3>
                    <a href="/admin/complaints" class="btn btn-primary btn-sm">عرض الشكاوي</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-center shadow">
                <div class="card-header">
                    عدد الطلبات
                </div>
                <div class="card-body">
                    <h3>{{ $stats['requests'] }}</h3>
                    <a href="/admin/service-requests" class="btn btn-primary btn-sm">عرض الطلبات</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-center shadow">
                <div class="card-header">
                    عدد المهنيين
                </div>
                <div class="card-body">
                    <h3>{{ $stats['professionals'] }}</h3>
                    <a href="/admin/professionals" class="btn btn-primary btn-sm">عرض المهنيين</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-center shadow">
                <div class="card-header">
                    عدد المستخدمين
                </div>
                <div class="card-body">
                    <h3>{{ $stats['users'] }}</h3>
                    <a href="/admin/users" class="btn btn-primary btn-sm">عرض المستخدمين</a>
                </div>
            </div>
        </div>

    </div>

    <!-- الصف الثاني: روابط سريعة -->
    <div class="row mt-4">

        <div class="col-md-4 mb-3">
            <div class="card shadow">
                <div class="card-header">
                    الشكاوي
                </div>
                <div class="card-body">
                    <p>إدارة جميع الشكاوي المقدمة من المستخدمين.</p>
                    <a href="/admin/complaints" class="btn btn-primary">الانتقال للصفحة</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow">
                <div class="card-header">
                    الطلبات
                </div>
                <div class="card-body">
                    <p>عرض وإدارة الطلبات المقدمة عبر التطبيق.</p>
                    <a href="/admin/service-requests" class="btn btn-primary">الانتقال للصفحة</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow">
                <div class="card-header">
                    المهنيين
                </div>
                <div class="card-body">
                    <p>إدارة المهنيين ومعلوماتهم وحالاتهم.</p>
                    <a href="/admin/professionals" class="btn btn-primary">الانتقال للصفحة</a>
                </div>
            </div>
        </div>

    </div>

    <!-- الصف الثالث: زر شحن رصيد مهني -->
    <div class="row mt-4">

        <div class="col-md-4 mb-3">
            <div class="card shadow">
                <div class="card-header">
                    شحن رصيد مهني
                </div>
                <div class="card-body">
                    <p>شحن رصيد أي مهني عبر رقم الهاتف.</p>
                    <a href="/admin/wallet/charge" class="btn btn-success">الانتقال للصفحة</a>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection