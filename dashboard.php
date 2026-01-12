<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>لوحة التحكم | رؤية</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{
    background:#f5f7fa;
    font-family: Tahoma;
}
.card{
    transition:.3s;
}
.card:hover{
    transform: translateY(-5px);
    box-shadow:0 10px 20px rgba(0,0,0,.15);
}
</style>
</head>
<body>

<nav class="navbar navbar-dark bg-success px-4">
    <span class="navbar-brand">📘 مركز رؤية لتحفيظ القرآن</span>
    <a href="logout.php" class="btn btn-light btn-sm">تسجيل الخروج</a>
</nav>

<div class="container mt-4">
    <h4 class="mb-4">مرحبًا بك 👋</h4>

    <div class="row g-4">

        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5>👦 الطلاب</h5>
                <a href="students.php" class="btn btn-success mt-2">إدارة الطلاب</a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5>👨‍🏫 المدرسين</h5>
                <a href="teachers.php" class="btn btn-success mt-2">إدارة المدرسين</a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5>👨‍👩‍👧 أولياء الأمور</h5>
                <a href="parents.php" class="btn btn-success mt-2">إدارة أولياء الأمور</a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5>📊 التقارير</h5>
                <a href="students_reports.php" class="btn btn-success mt-2">تقارير الطلاب</a>
            </div>
        </div>

        
        </div>

    </div>
</div>

</body>
</html>