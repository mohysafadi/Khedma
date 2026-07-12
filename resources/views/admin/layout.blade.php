<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-image: url('/images/m.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #ffffff;
            border-left: 4px solid #4da6ff;
            /* أزرق سماوي */
            position: fixed;
            top: 0;
            right: 0;
            padding: 20px;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            display: block;
            padding: 12px;
            margin-bottom: 10px;
            background-color: #e6f3ff;
            color: #007bff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .sidebar a:hover {
            background-color: #cce7ff;
        }

        .content {
            margin-right: 270px;
            padding: 20px;

            /* خلفية شفافة */
            background-color: rgba(255, 255, 255, 0.45);

            /* تأثير زجاجي احترافي */

            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background-color: #4da6ff;
            color: white;
            border-radius: 10px 10px 0 0;
        }

        .btn-primary {
            background-color: #4da6ff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #3399ff;
        }

        .btn-warning {
            background-color: #ffcc00;
            border: none;
        }

        .btn-warning:hover {
            background-color: #e6b800;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="mb-4">لوحة التحكم</h4>

        <a href="/admin/dashboard">الصفحة الرئيسية</a>
        <a href="/admin/wallet/charge">شحن رصيد مهني</a>
        <a href="/admin/complaints">الشكاوي</a>
        <a href="/admin/users">المستخدمين</a>
        <a href="/admin/banned-users">المستخدمون المحظورون</a>
        <a href="/admin/professionals">المهنيين</a>
        <a href="/admin/service-requests">الطلبات</a>
        <a href="/logout" class="text-danger">تسجيل خروج</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        @yield('content')
    </div>

</body>

</html>