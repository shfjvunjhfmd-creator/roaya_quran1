<?php
include "includes/auth.php";
include "includes/config.php";

$today = date('Y-m-d');

// تسجيل حضور عند مسح QR أو الدخول عبر الرابط
if(isset($_GET['id'])){
    $student_id = intval($_GET['id']);

    // تأكد إن الطالب موجود
    $checkStudent = mysqli_query($conn,"SELECT * FROM students WHERE id=$student_id");
    if(mysqli_num_rows($checkStudent)==0){
        $msg = "طالب غير موجود!";
    } else {
        // منع التكرار في نفس اليوم
        $check = mysqli_query($conn,"SELECT * FROM attendance 
            WHERE student_id=$student_id AND date='$today'");

        if(mysqli_num_rows($check)==0){
            // تسجيل حضور تلقائي
            mysqli_query($conn,"INSERT INTO attendance (student_id,date,status)
            VALUES ($student_id,'$today','حاضر')");
            $msg = "✅ تم تسجيل الحضور للطالب ID=$student_id";
        } else {
            $msg = "⚠️ تم تسجيل الحضور مسبقًا اليوم للطالب ID=$student_id";
        }
    }
}

// جلب كل الحضور اليوم
$attendance = mysqli_query($conn,"
SELECT students.name, attendance.status, attendance.date, attendance.time
FROM attendance
JOIN students ON students.id = attendance.student_id
WHERE attendance.date='$today'
ORDER BY attendance.time DESC
");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>إدارة الحضور | رؤية لتحفيظ القرآن</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#f4f4f4; padding:20px; }
.table th { background:#1e7f5c; color:white; }
.status-hadir { color: green; font-weight:bold; }
.status-ghaib { color: red; font-weight:bold; }
.msg { margin-bottom:15px; font-weight:bold; }
</style>
</head>
<body>
<div class="container">
<h2 class="mb-4 text-center">إدارة الحضور - <?= $today ?></h2>

<?php if(isset($msg)) echo "<div class='alert alert-info msg'>$msg</div>"; ?>

<!-- جدول الحضور -->
<table class="table table-bordered table-striped text-center">
<thead class="table-dark">
<tr>
<th>#</th>
<th>الاسم</th>
<th>الحالة</th>
<th>التاريخ</th>
<th>الوقت</th>
</tr>
</thead>
<tbody>
<?php $i=1; while($r=mysqli_fetch_assoc($attendance)){ ?>
<tr>
<td><?= $i++ ?></td>
<td><?= $r['name'] ?></td>
<td class="<?= $r['status']=='حاضر'?'status-hadir':'status-ghaib' ?>"><?= $r['status'] ?></td>
<td><?= $r['date'] ?></td>
<td><?= $r['time'] ?></td>
</tr>
<?php } ?>
</tbody>
</table>

<p class="text-center mt-4">
يمكنك تسجيل الحضور عبر مسح QR Code لكل طالب أو الدخول بالرابط:<br>
<code>attendance.php?id=رقم_الطالب</code>
</p>

</div>
</body>
</html>

<tr>
<th>#</th>
<th>الاسم</th>
<th>الحالة</th>
<th>التاريخ</th>
<th>الوقت</th>
</tr>
</thead>
<tbody>
<?php $i=1; while($r=mysqli_fetch_assoc($attendance)){ ?>
<tr>
<td><?= $i++ ?></td>
<td><?= $r['name'] ?></td>
<td><?= $r['status'] ?></td>
<td><?= $r['date'] ?></td>
<td><?= $r['time'] ?></td>
</tr>
<?php } ?>
</tbody>
</table>

<p class="text-center mt-4">يمكنك تسجيل الحضور عبر مسح QR Code لكل طالب أو عبر الرابط:</p>
<p class="text-center"><code>attendance.php?id=رقم_الطالب</code></p>

</div>
</body>
</html>
