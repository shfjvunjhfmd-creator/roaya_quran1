<?php
include "includes/auth.php";
include "includes/config.php";

// جلب التاريخ (اختياري: يمكن تعديل الفترة)
$start_date = $_GET['start'] ?? date('Y-m-01'); // أول يوم في الشهر الحالي
$end_date = $_GET['end'] ?? date('Y-m-d');      // اليوم الحالي

// جلب كل الطلاب
$students = mysqli_query($conn,"SELECT students.*, parents.name as parent_name 
                                FROM students 
                                LEFT JOIN parents ON students.parent_id = parents.id");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>تقارير الحضور | رؤية لتحفيظ القرآن</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#f4f4f4; padding:20px; }
.table th { background:#1e7f5c; color:white; }
.status-hadir { color: green; font-weight:bold; }
.status-ghaib { color: red; font-weight:bold; }
</style>
</head>
<body>
<div class="container">
<h2 class="mb-4 text-center">تقارير الحضور من <?= $start_date ?> إلى <?= $end_date ?></h2>

<?php while($s=mysqli_fetch_assoc($students)): ?>
<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <?= $s['name'] ?> - ولي الأمر: <?= $s['parent_name'] ?>
        <a href="attendance_report_pdf.php?id=<?= $s['id'] ?>" target="_blank" class="btn btn-light btn-sm float-end">تحميل PDF</a>
    </div>
    <div class="card-body">
        <?php
        // جلب حضور الطالب في الفترة
        $attendance = mysqli_query($conn,"
            SELECT date, status, time FROM attendance
            WHERE student_id={$s['id']} AND date BETWEEN '$start_date' AND '$end_date'
            ORDER BY date ASC
        ");
        if(mysqli_num_rows($attendance)==0){
            echo "<p>لا يوجد حضور لهذا الطالب في الفترة المحددة.</p>";
        } else {
        ?>
        <table class="table table-bordered table-striped text-center">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>التاريخ</th>
                    <th>الوقت</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
            <?php $i=1; while($r=mysqli_fetch_assoc($attendance)): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $r['date'] ?></td>
                    <td><?= $r['time'] ?></td>
                    <td class="<?= $r['status']=='حاضر'?'status-hadir':'status-ghaib' ?>"><?= $r['status'] ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <?php } ?>
    </div>
</div>
<?php endwhile; ?>

</div>
</body>
</html>
