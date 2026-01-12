<?php
include "includes/auth.php";
include "includes/config.php";
require_once('tcpdf_min/tcpdf.php');

// جلب رقم الطالب
$id = $_GET['id'] ?? 0;
$id = intval($id);
if(!$id) die("الطالب غير موجود");

// جلب بيانات الطالب وولي الأمر
$student_q = mysqli_query($conn,"
    SELECT students.*, parents.name as parent_name
    FROM students
    LEFT JOIN parents ON students.parent_id = parents.id
    WHERE students.id=$id
");
$student = mysqli_fetch_assoc($student_q);
if(!$student) die("الطالب غير موجود");

// جلب حضور الطالب
$attendance_q = mysqli_query($conn,"
    SELECT date, time, status
    FROM attendance
    WHERE student_id=$id
    ORDER BY date ASC
");

// إنشاء PDF
$pdf = new TCPDF('P','mm','A4');
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle("تقرير حضور {$student['name']}");
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(15,20,15);
$pdf->AddPage();

// العنوان
$pdf->SetFont('dejavusans','B',16);
$pdf->SetTextColor(30,127,92);
$pdf->Cell(0,10,"تقرير حضور الطالب",0,1,'C');
$pdf->Ln(5);

// بيانات الطالب
$pdf->SetFont('dejavusans','',14);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(0,8,"الاسم: ".$student['name'],0,1,'L');
$pdf->Cell(0,8,"ولي الأمر: ".$student['parent_name'],0,1,'L');
$pdf->Ln(5);

// جدول الحضور
$pdf->SetFillColor(30,127,92);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('dejavusans','B',12);
$pdf->Cell(50,10,'التاريخ',1,0,'C',1);
$pdf->Cell(50,10,'الوقت',1,0,'C',1);
$pdf->Cell(50,10,'الحالة',1,1,'C',1);

// بيانات الحضور
$pdf->SetFont('dejavusans','',12);
$pdf->SetTextColor(0,0,0);
$fill=false;
while($r=mysqli_fetch_assoc($attendance_q)){
    $pdf->Cell(50,8,$r['date'],1,0,'C',$fill);
    $pdf->Cell(50,8,$r['time'],1,0,'C',$fill);
    // اللون حسب الحالة
    if($r['status']=='حاضر'){
        $pdf->SetTextColor(0,128,0); // أخضر
    } else {
        $pdf->SetTextColor(255,0,0); // أحمر
    }
    $pdf->Cell(50,8,$r['status'],1,1,'C',$fill);
    $pdf->SetTextColor(0,0,0);
    $fill = !$fill;
}

// فتح PDF مباشرة في المتصفح
$pdf->Output("report_student_{$student['id']}.pdf","I"); // "I" = inline
