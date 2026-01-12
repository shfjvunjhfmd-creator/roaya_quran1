<?php
include "includes/auth.php";
include "includes/config.php";
require_once('tcpdf_min/tcpdf.php');

// تحديد الفترة (اختياري: الشهر الحالي)
$month = $_GET['month'] ?? date('Y-m');
$start_date = $month.'-01';
$end_date = date('Y-m-t', strtotime($start_date)); // آخر يوم في الشهر

// جلب كل الطلاب
$students = mysqli_query($conn,"SELECT * FROM students ORDER BY name ASC");

// إنشاء قائمة تواريخ الشهر
$period = new DatePeriod(
    new DateTime($start_date),
    new DateInterval('P1D'),
    new DateTime(date('Y-m-d', strtotime($end_date.' +1 day')))
);
$dates = [];
foreach($period as $dt) { $dates[] = $dt->format("Y-m-d"); }
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>تقارير الحضور | رؤية لتحفيظ القرآن</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.status-hadir { color: green; font-weight:bold; }
.status-ghaib { color: red; font-weight:bold; }
</style>
</head>
<body>
<div class="container mt-4">
<h2 class="mb-4 text-center">تقارير حضور الطلاب لشهر <?= date('F Y', strtotime($start_date)) ?></h2>

<?php while($s=mysqli_fetch_assoc($students)): ?>
<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <?= $s['name'] ?>
        <a href="attendance_full_report_pdf.php?id=<?= $s['id'] ?>" target="_blank" class="btn btn-light btn-sm float-end">تحميل PDF</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>اليوم</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($dates as $d):
                $att_q = mysqli_query($conn,"SELECT status FROM attendance WHERE student_id={$s['id']} AND date='$d'");
                if(mysqli_num_rows($att_q)==0){
                    $status = 'غائب';
                } else {
                    $status = mysqli_fetch_assoc($att_q)['status'];
                }
            ?>
            <tr>
                <td><?= $d ?></td>
                <td class="<?= $status=='حاضر'?'status-hadir':'status-ghaib' ?>"><?= $status ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endwhile; ?>
</div>
</body>
</html>
