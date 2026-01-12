<?php
include "includes/config.php";
require "vendor/autoload.php";

use Dompdf\Dompdf;

$html = "<h2>تقرير الحضور - رؤية لتحفيظ القرآن الكريم</h2>";
$html .= "<table border='1' width='100%'>
<tr><th>الطالب</th><th>التاريخ</th><th>الوقت</th></tr>";

$q = mysqli_query($conn,"
SELECT students.name, attendance.date, attendance.time
FROM attendance
JOIN students ON students.id = attendance.student_id
");

while($r=mysqli_fetch_assoc($q)){
$html .= "<tr>
<td>{$r['name']}</td>
<td>{$r['date']}</td>
<td>{$r['time']}</td>
</tr>";
}
$html .= "</table>";

$pdf = new Dompdf();
$pdf->loadHtml($html);
$pdf->render();
$pdf->stream("attendance.pdf");
