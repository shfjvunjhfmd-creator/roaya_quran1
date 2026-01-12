<?php
include "includes/auth.php";
include "includes/config.php";

// إضافة ولي أمر جديد
if(isset($_POST['name'], $_POST['phone'])){
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $phone = mysqli_real_escape_string($conn,$_POST['phone']);

    $sql = "INSERT INTO parents (name, phone) VALUES ('$name', '$phone')";
    if(!mysqli_query($conn,$sql)){
        die("❌ حدث خطأ أثناء إضافة ولي الأمر: " . mysqli_error($conn));
    }
}

// جلب أولياء الأمور
$parents = mysqli_query($conn,"SELECT * FROM parents");
if(!$parents){
    die("❌ خطأ في قاعدة البيانات: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>أولياء الأمور – رؤية لتحفيظ القرآن الكريم</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">

<!-- زر العودة للصفحة الرئيسية -->
<a href="dashboard.php" class="btn btn-dark mb-3">
    ← الصفحة الرئيسية
</a>

<h2 class="mb-3 text-success">إضافة ولي أمر جديد</h2>
<form method="post" class="mb-4 row g-2">
    <div class="col-md-6">
        <input type="text" name="name" class="form-control" placeholder="اسم ولي الأمر" required>
    </div>
    <div class="col-md-6">
        <input type="text" name="phone" class="form-control" placeholder="رقم الموبايل" required>
    </div>
    <div class="col-12">
        <button class="btn btn-primary mt-2">إضافة</button>
    </div>
</form>

<h3 class="text-success">قائمة أولياء الأمور</h3>
<table class="table table-bordered table-striped text-center">
    <thead class="table-dark">
        <tr><th>الاسم</th><th>رقم الموبايل</th></tr>
    </thead>
    <tbody>
    <?php while($p=mysqli_fetch_assoc($parents)){ ?>
    <tr>
        <td><?= htmlspecialchars($p['name']) ?></td>
        <td><?= htmlspecialchars($p['phone']) ?></td>
    </tr>
    <?php } ?>
    </tbody>
</table>
</div>
</body>
</html>
