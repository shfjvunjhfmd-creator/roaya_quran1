<?php
session_start();
include "includes/config.php";

// التحقق من وجود parent_id
$pid = intval($_SESSION['parent_id'] ?? 0);
if(!$pid){
    die("❌ لم يتم تسجيل الدخول كولي أمر");
}

// جلب سجل الحضور
$q = mysqli_query($conn,"
    SELECT attendance.date, attendance.time
    FROM attendance
    JOIN students ON students.id = attendance.student_id
    WHERE students.parent_id=$pid
    ORDER BY attendance.date DESC, attendance.time DESC
");
if(!$q){
    die("خطأ في قاعدة البيانات: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>سجل حضور الطالب – رؤية لتحفيظ القرآن الكريم</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">

<h2 class="mb-4 text-success">سجل حضور الطالب</h2>

<table class="table table-bordered table-striped text-center">
    <thead class="table-dark">
        <tr><th>التاريخ</th><th>الوقت</th></tr>
    </thead>
    <tbody>
    <?php while($r=mysqli_fetch_assoc($q)){ ?>
    <tr>
        <td><?= htmlspecialchars($r['date']) ?></td>
        <td><?= htmlspecialchars($r['time']) ?></td>
    </tr>
    <?php } ?>
    </tbody>
</table>

</div>
</body>
</html>
