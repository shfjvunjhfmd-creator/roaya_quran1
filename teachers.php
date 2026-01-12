<?php
include "includes/auth.php";
include "includes/config.php";

// إضافة مدرس جديد
if(isset($_POST['add_teacher'])){
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $user = mysqli_real_escape_string($conn,$_POST['user']);
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    $salary = floatval($_POST['salary']);

    mysqli_query($conn,"INSERT INTO teachers (name,user,pass,salary) VALUES ('$name','$user','$pass','$salary')");
    header("Location: teachers.php");
    exit;
}

// حذف مدرس
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn,"DELETE FROM teachers WHERE id=$id");
    header("Location: teachers.php");
    exit;
}

// جلب جميع المدرسين
$teachers = mysqli_query($conn,"SELECT * FROM teachers");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>إدارة المدرسين</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f5f5f5; font-family: Tahoma, sans-serif; }
.card { margin-top: 20px; }
</style>
</head>
<body>
<div class="container mt-5">
<h2 class="mb-4 text-center">إدارة المدرسين</h2>

<!-- Tabs -->
<ul class="nav nav-tabs" id="teacherTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="add-tab" data-bs-toggle="tab" data-bs-target="#add" type="button" role="tab">إضافة مدرس</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="list-tab" data-bs-toggle="tab" data-bs-target="#list" type="button" role="tab">قائمة المدرسين</button>
  </li>
</ul>

<div class="tab-content">
  <!-- Tab 1: إضافة مدرس -->
  <div class="tab-pane fade show active" id="add" role="tabpanel">
    <div class="card p-4">
      <form method="POST" class="row g-2">
        <div class="col-md-3">
          <input type="text" name="name" class="form-control" placeholder="اسم المدرس" required>
        </div>
        <div class="col-md-3">
          <input type="text" name="user" class="form-control" placeholder="اسم المستخدم" required>
        </div>
        <div class="col-md-3">
          <input type="password" name="pass" class="form-control" placeholder="كلمة المرور" required>
        </div>
        <div class="col-md-3">
          <input type="number" step="0.01" name="salary" class="form-control" placeholder="الراتب / المصاريف" required>
        </div>
        <div class="col-12">
          <button type="submit" name="add_teacher" class="btn btn-success mt-2">إضافة المدرس</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Tab 2: قائمة المدرسين -->
  <div class="tab-pane fade" id="list" role="tabpanel">
    <div class="card p-4">
      <table class="table table-bordered text-center">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>الاسم</th>
            <th>اسم المستخدم</th>
            <th>الراتب / المصاريف</th>
            <th>حذف</th>
          </tr>
        </thead>
        <tbody>
        <?php while($t=mysqli_fetch_assoc($teachers)){ ?>
          <tr>
            <td><?= $t['id'] ?></td>
            <td><?= htmlspecialchars($t['name']) ?></td>
            <td><?= htmlspecialchars($t['user']) ?></td>
            <td><?= number_format($t['salary'],2) ?> ج.م</td>
            <td>
              <a href="teachers.php?delete=<?= $t['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('هل تريد حذف المدرس؟');">حذف</a>
            </td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</div>
</body>
</html>
