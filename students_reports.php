<?php
session_start();
include "includes/auth.php";
include "includes/config.php";
require "vendor/autoload.php";
use Dompdf\Dompdf;

// جلب كل الطلاب مع بيانات ولي الأمر
$students = mysqli_query($conn,"SELECT students.*, parents.name as parent_name, parents.phone as parent_phone 
                                FROM students 
                                LEFT JOIN parents ON students.parent_id = parents.id");

// توليد تقرير PDF فردي
if(isset($_GET['report_id'])){
    $id = intval($_GET['report_id']);
    $q = mysqli_query($conn,"SELECT date,time FROM attendance WHERE student_id='$id'");
    $student_q = mysqli_query($conn,"SELECT name FROM students WHERE id='$id'");
    $student = mysqli_fetch_assoc($student_q);

    $html = "<h3>تقرير حضور الطالب: {$student['name']}</h3>
             <table border='1' width='100%' style='border-collapse: collapse;'>
             <tr><th>التاريخ</th><th>الوقت</th></tr>";

    while($r=mysqli_fetch_assoc($q)){
        $html.="<tr><td>{$r['date']}</td><td>{$r['time']}</td></tr>";
    }
    $html.="</table>";

    $pdf = new Dompdf();
    $pdf->loadHtml($html);
    $pdf->render();
    $pdf->stream("report_{$student['name']}.pdf");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>تقارير الطلاب</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { font-family: Tahoma, sans-serif; background: #f5f5f5; padding: 30px; }
.table-container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);}
</style>
</head>
<body>
<div class="container">
<h2 class="text-center mb-4">تقارير الطلاب</h2>

<div class="table-container">
<input class="form-control mb-3" id="searchInput" type="text" placeholder="ابحث باسم الطالب...">

<table class="table table-bordered text-center" id="studentsTable">
<thead class="table-dark">
<tr>
<th>#</th>
<th>اسم الطالب</th>
<th>ولي الأمر</th>
<th>رقم الهاتف</th>
<th>تقرير PDF</th>
</tr>
</thead>
<tbody>
<?php $i=1; while($s=mysqli_fetch_assoc($students)){ ?>
<tr>
<td><?= $i++ ?></td>
<td><?= htmlspecialchars($s['name']) ?></td>
<td><?= htmlspecialchars($s['parent_name']) ?></td>
<td><?= htmlspecialchars($s['parent_phone']) ?></td>
<td>
    <a href="?report_id=<?= $s['id'] ?>" class="btn btn-info btn-sm" target="_blank">تحميل PDF</a>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// فلترة الطلاب بالبحث
const searchInput = document.getElementById('searchInput');
searchInput.addEventListener('keyup', function(){
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#studentsTable tbody tr');
    rows.forEach(row => {
        const name = row.cells[1].textContent.toLowerCase();
        row.style.display = name.includes(filter) ? '' : 'none';
    });
});
</script>
</body>
</html>
