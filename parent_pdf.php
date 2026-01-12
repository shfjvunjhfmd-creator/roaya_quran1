<?php
include "includes/config.php";
require "vendor/autoload.php";
use Dompdf\Dompdf;

// التحقق من وجود ID
$id = intval($_GET['id'] ?? 0);
if(!$id){
    die("❌ لم يتم تحديد الطالب");
}

// جلب بيانات الحضور
$q = mysqli_query($conn,"SELECT date,time FROM attendance WHERE student_id=$id");
if(!$q){
    die("خطأ في قاعدة البيانات: " . mysqli_error($conn));
}

// توليد HTML للتقرير
$html = "<h3>تقرير حضور الطالب</h3>
<table border='1' cellpadding='5' cellspacing='0'>
<tr><th>التاريخ</th><th>الوقت</th></tr>";

while($r = mysqli_fetch_assoc($q)){
    $date = htmlspecialchars($r['date']);
    $time = htmlspecialchars($r['time']);
    $html .= "<tr><td>$date</td><td>$time</td></tr>";
}

$html .= "</table>";

// توليد PDF
$pdf = new Dompdf();
$pdf->loadHtml($html);
$pdf->render();
$pdf->stream("student_report_$id.pdf", ["Attachment" => true]);
?>
