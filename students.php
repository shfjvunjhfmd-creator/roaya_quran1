<?php
include "includes/auth.php";
include "includes/config.php";

// إضافة طالب جديد
if(isset($_POST['add_student'])){
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $parent_name = mysqli_real_escape_string($conn,$_POST['parent_name']);
    $parent_phone = mysqli_real_escape_string($conn,$_POST['parent_phone']);

    // إضافة ولي الأمر
    $parent_sql = "INSERT INTO parents (name, phone) VALUES ('$parent_name', '$parent_phone')";
    if(!mysqli_query($conn,$parent_sql)){
        die("خطأ في إضافة ولي الأمر: " . mysqli_error($conn));
    }
    $parent_id = mysqli_insert_id($conn);

    // إضافة الطالب
    $student_sql = "INSERT INTO students (name, parent_id) VALUES ('$name', $parent_id)";
    if(!mysqli_query($conn,$student_sql)){
        die("خطأ في إضافة الطالب: " . mysqli_error($conn));
    }

    header("Location: students.php");
    exit;
}

// حذف طالب
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $check = mysqli_query($conn,"SELECT * FROM students WHERE id=$id");
    if(mysqli_num_rows($check) > 0){
        mysqli_query($conn,"DELETE FROM students WHERE id=$id");
        header("Location: students.php");
        exit;
    } else {
        echo "<div class='alert alert-warning'>الطالب غير موجود</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>إدارة الطلاب – رؤية لتحفيظ القرآن الكريم</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background-color: #f5f5f5; }
.card, table { margin-bottom: 20px; }
.qr-img { width: 100px; height: 100px; }
</style>
</head>
<body>
<div class="container mt-5">

<h2 class="mb-4 text-center text-success">إدارة الطلاب – رؤية لتحفيظ القرآن الكريم</h2>

<!-- إضافة طالب -->
<div class="card p-3 mb-4">
    <h5>إضافة طالب جديد</h5>
    <form method="POST">
        <div class="row">
            <div class="col-md-4 mb-2"><input type="text" name="name" class="form-control" placeholder="اسم الطالب" required></div>
            <div class="col-md-4 mb-2"><input type="text" name="parent_name" class="form-control" placeholder="اسم ولي الأمر" required></div>
            <div class="col-md-4 mb-2"><input type="text" name="parent_phone" class="form-control" placeholder="رقم الهاتف" required></div>
        </div>
        <button type="submit" name="add_student" class="btn btn-success">إضافة الطالب</button>
    </form>
</div>

<!-- جدول الطلاب -->
<div class="card p-3">
<table class="table table-bordered table-striped text-center">
<thead>
<tr>
<th>#</th>
<th>الاسم</th>
<th>ولي الأمر</th>
<th>رقم الهاتف</th>
<th>QR Code</th>
<a href="student_card.php?id=<?= $s['id'] ?>" target="_blank" class="btn btn-primary btn-sm">كارت</a>
<td><a href="student_report_pro.php?id=<?= $s['id'] ?>" target="_blank" class="btn btn-info btn-sm">تقرير</a></td>
<th>حذف</th>
</tr>
</thead>
<tbody>
<?php
$students = mysqli_query($conn,"SELECT students.*, parents.name as parent_name, parents.phone as parent_phone 
                                 FROM students 
                                 LEFT JOIN parents ON students.parent_id = parents.id");

while($s = mysqli_fetch_assoc($students)){
?>
<tr>
<td><?= $s['id'] ?></td>
<td><?= htmlspecialchars($s['name']) ?></td>
<td><?= htmlspecialchars($s['parent_name']) ?></td>
<td><?= htmlspecialchars($s['parent_phone']) ?></td>
<td><img src="qr.php?id=<?= $s['id'] ?>" class="qr-img"></td>
<td><a href="student_card.php?id=<?= $s['id'] ?>" target="_blank" class="btn btn-primary btn-sm">كارت</a></td>
<td><a href="report.php?id=<?= $s['id'] ?>" target="_blank" class="btn btn-info btn-sm">تقرير</a></td>
<td><a href="students.php?delete=<?= $s['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('هل تريد حذف الطالب؟');">حذف</a></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>

</div>