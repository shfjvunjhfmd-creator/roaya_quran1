<?php
include "includes/config.php";
require_once('tcpdf_min/tcpdf.php');

// جلب معرف الطالب من الرابط
$id = $_GET['id'] ?? 0;
if(!$id) die("الطالب غير موجود");

// جلب بيانات الطالب وولي الأمر
$student_q = mysqli_query($conn,"
SELECT students.*, parents.name AS parent_name, parents.phone AS parent_phone
FROM students
LEFT JOIN parents ON students.parent_id = parents.id
WHERE students.id='$id'
");
$student = mysqli_fetch_assoc($student_q);
if(!$student) die("الطالب غير موجود");

// جلب بيانات الحضور
$attendance_q = mysqli_query($conn,"
SELECT date,time FROM attendance
WHERE student_id='$id'
ORDER BY date ASC
");

// إنشاء PDF جديد
$pdf = new TCPDF('P','mm','A4');
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle("تقرير الطالب {$student['name']}");
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(15,20,15);
$pdf->AddPage();

// الخط العربي
$pdf->SetFont('dejavusans','',14);

// عنوان التقرير
$pdf->SetTextColor(30,127,92); // أخضر احترافي
$pdf->Cell(0,10,"تقرير حضور الطالب",0,1,'C');
$pdf->Ln(5);

// بيانات الطالب
$pdf->SetTextColor(0,0,0);
$pdf->Cell(0,8,"الاسم: ".$student['name'],0,1,'L');
$pdf->Cell(0,8,"رقم الطالب: ".$student['id'],0,1,'L');
$pdf->Cell(0,8,"ولي الأمر: ".$student['parent_name'],0,1,'L');
$pdf->Cell(0,8,"رقم الهاتف: ".$student['parent_phone'],0,1,'L');
$pdf->Ln(5);

// جدول الحضور
$pdf->SetFillColor(30,127,92); // رأس الجدول أخضر
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('dejavusans','B',12);
$pdf->Cell(50,10,'التاريخ',1,0,'C',1);
$pdf->Cell(50,10,'الوقت',1,1,'C',1);

$pdf->SetFont('dejavusans','',12);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(230,230,250); // لون خلفية الصفوف بالتناوب

$fill = false;
while($r = mysqli_fetch_assoc($attendance_q)){
    $pdf->Cell(50,8,$r['date'],1,0,'C',$fill);
    $pdf->Cell(50,8,$r['time'],1,1,'C',$fill);
    $fill = !$fill;
}

// عرض PDF مباشرة في المتصفح
$pdf->Output("report_student_{$student['id']}.pdf", "I");
