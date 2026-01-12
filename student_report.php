<?php
include "includes/config.php";
require "vendor/autoload.php"; // لو استخدمت Dompdf
use Dompdf\Dompdf;

$id = $_GET['id'] ?? 0;
if(!$id) die("الطالب غير موجود");

// جلب بيانات الحضور للطالب
$q = mysqli_query($conn,"
SELECT date,time FROM attendance
WHERE student_id='$id'
");

$html = "<h3>تقرير حضور الطالب</h3><table border='1' cellpadding='5' cellspacing='0'>
<tr style='background:#f0f0f0'><th>التاريخ</th><th>الوقت</th></tr>";

while($r=mysqli_fetch_assoc($q)){
    $html .= "<tr><td>{$r['date']}</td><td>{$r['time']}</td></tr>";
}
$html .= "</table>";

// إنشاء PDF
$pdf = new Dompdf();
$pdf->loadHtml($html);
$pdf->setPaper('A4','portrait');
$pdf->render();
$pdf->stream("student_report_$id.pdf",["Attachment"=>0]); // 0 يعرض في المتصفح
