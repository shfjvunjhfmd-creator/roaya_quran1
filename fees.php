
<?php
include "includes/config.php";

// جلب كل الطلاب
$students = mysqli_query($conn,"SELECT id, name, parent_phone FROM students");

// إضافة مصروف جديد
if(isset($_POST['add_fee'])){
    $student_id = $_POST['student_id'];
    $amount = $_POST['amount'];
    $desc = $_POST['desc'];
    $date = date('Y-m-d');

    mysqli_query($conn,"INSERT INTO fees (student_id, amount, description, date) 
                        VALUES ($student_id, $amount, '$desc', '$date')");
    header("Location: fees.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>إدارة المصاريف | رؤية لتحفيظ القرآن</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: Tahoma; background: #f5f5f5; padding: 20px; }
h2 { color: #1e7f5c; margin-bottom: 30px; text-align: center; }
.card { margin-bottom: 20px; }
.badge { font-size: 90%; }
</style>
</head>
<body>

<div class="container">
<h2>إدارة المصاريف</h2>

<!-- إضافة مصروف -->
<div class="card p-3">
<form method="POST">
<div class="row g-2">
<div class="col-md-4">
<select name="student_id" class="form-control" required>
<option value="">اختر الطالب</option>
<?php while($s=mysqli_fetch_assoc($students)){ ?>
<option value="<?= $s['id'] ?>"><?= $s['name'] ?> (<?= $s['parent_phone'] ?>)</option>
<?php } ?>
</select>
</div>
<div class="col-md-3">
<input type="number" name="amount" class="form-control" placeholder="المبلغ" required>
</div>
<div class="col-md-3">
<input type="text" name="desc" class="form-control" placeholder="الوصف">
</div>
<div class="col-md-2">
<button type="submit" name="add_fee" class="btn btn-success w-100">إضافة</button>
</div>
</div>
</form>
</div>

<!-- جدول المصاريف -->
<table class="table table-bordered table-striped text-center mt-4">
<thead class="table-dark">
<tr>
<th>#</th>
<th>الطالب</th>
<th>رقم ولي الأمر</th>
<th>المبلغ</th>
<th>الوصف</th>
<th>التاريخ</th>
<th>الحالة</th>
<th>تأكيد الدفع</th>
<th>تذكير</th>
</tr>
</thead>
<tbody>
<?php
$fees = mysqli_query($conn,"SELECT fees.*, students.name AS student_name, students.parent_phone 
                            FROM fees 
                            JOIN students ON students.id = fees.student_id
                            ORDER BY fees.date DESC");

$total = 0;
while($f=mysqli_fetch_assoc($fees)){
    $total += $f['amount'];
?>
<tr>
<td><?= $f['id'] ?></td>
<td><?= $f['student_name'] ?></td>
<td><?= $f['parent_phone'] ?></td>
<td><?= $f['amount'] ?> جنيه</td>
<td><?= $f['description'] ?></td>
<td><?= $f['date'] ?></td>
<td>
<?= $f['status']=='مدفوع' ? '<span class="badge bg-success">✅ مدفوع</span>' : '<span class="badge bg-danger">❌ غير مدفوع</span>' ?>
</td>
<td>
<?php if($f['status']!='مدفوع'){ ?>
<form method="POST" action="mark_paid.php" style="margin:0;">
<input type="hidden" name="fee_id" value="<?= $f['id'] ?>">
<button type="submit" class="btn btn-success btn-sm">تم الدفع</button>
</form>
<?php } else { echo '-'; } ?>
</td>
<td>
<?php if($f['status']!='مدفوع'){ ?>
<form method="POST" action="send_reminder.php" style="margin:0;">
<input type="hidden" name="parent_phone" value="<?= $f['parent_phone'] ?>">
<input type="hidden" name="student_name" value="<?= $f['student_name'] ?>">
<input type="hidden" name="amount" value="<?= $f['amount'] ?>">
<button type="submit" class="btn btn-info btn-sm">تذكير</button>
</form>
<?php } else { echo '-'; } ?>
</td>
</tr>
<?php } ?>
<tr class="table-info">
<td colspan="3">الإجمالي</td>
<td colspan="6"><?= $total ?> جنيه</td>
</tr>
</tbody>
</table>
</div>

</body>
</html>
