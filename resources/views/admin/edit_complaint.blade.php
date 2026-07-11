<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تعديل حالة الشكوى</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    <div class="card shadow">
        <div class="card-header">
            <h3>تعديل حالة الشكوى رقم {{ $complaint->complaint_id }}</h3>
        </div>

        <div class="card-body">

            <form method="POST" action="/admin/complaints/{{ $complaint->complaint_id }}/update">
                @csrf

                <div class="mb-3">
                    <label class="form-label">الحالة الجديدة:</label>
                    <select name="status" class="form-control" required>
                        <option value="pending" {{ $complaint->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                        <option value="in_review" {{ $complaint->status == 'in_review' ? 'selected' : '' }}>قيد المراجعة</option>
                        <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>تم الحل</option>
                    </select>
                </div>

                <button class="btn btn-primary">تحديث الحالة</button>
            </form>

            <a href="/admin/complaints" class="btn btn-secondary mt-3">رجوع</a>

        </div>
    </div>

</div>

</body>
</html>