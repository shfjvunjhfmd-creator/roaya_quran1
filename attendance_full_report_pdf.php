<?php
include "includes/config.php";
require_once('tcpdf_min/tcpdf.php');

$id = $_GET['id'] ?? 0;
$id = intval($id);
if(!$id) die("الطالب غير موجود");

// بيانات الطالب
$student_q = mysqli_query($conn,"SELECT * FROM students WHERE id=$id");
$student = mysqli_fetch_assoc($student_q);
if(!$student) die("الطالب غير موجود");

// تحديد الفترة (الشهر الحالي)
$month = $_GET['month'] ?? date('Y-m');
$start_date = $month.'-01';
$end_date = date('Y-m-t', strtotime($start_date));

// إنشاء قائمة تواريخ الشهر
$period = new DatePeriod(
    new DateTime($start_date),
    new DateInterval('P1D'),
    new DateTime(date('Y-m-d', strtotime($end_date.' +1 day')))
);
$dates = [];
foreach($period as $dt) { $dates[] = $dt->format("Y-m-d"); }

// إنشاء PDF
$pdf = new TCPDF('P','mm','A4');
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle("تقرير حضور {$student['name']}");
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(15,20,15);
$pdf->AddPage();

// عنوان
$pdf->SetFont('dejavusans','B',16);
$pdf->SetTextColor(30,127,92);
$pdf->Cell(0,10,"تقرير حضور الطالب",0,1,'C');
$pdf->Ln(5);

// بيانات الطالب
$pdf->SetFont('dejavusans','',14);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(0,8,"الاسم: ".$student['name'],0,1,'L');
$pdf->Ln(3);

// جدول الحضور
$pdf->SetFillColor(30,127,92);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('dejavusans','B',12);
$pdf->Cell(70,10,'اليوم',1,0,'C',1);
$pdf->Cell(70,10,'الحالة',1,1,'C',1);

$pdf->SetFont('dejavusans','',12);
$pdf->SetTextColor(0,0,0);
$fill=false;

foreach($dates as $d){
    $att_q = mysqli_query($conn,"SELECT status FROM attendance WHERE student_id=$id AND date='$d'");
    if(mysqli_num_rows($att_q)==0){
        $status = 'غائب';
    } else {
        $status = mysqli_fetch_assoc($att_q)['status'];
    }
    
    $pdf->Cell(70,8,$d,1,0,'C',$fill);
    // اللون حسب الحالة
    if($status=='حاضر'){
        $pdf->SetTextColor(0,128,0);
    } else {
        $pdf->SetTextColor(255,0,0);
    }
    $pdf->Cell(70,8,$status,1,1,'C',$fill);
    $pdf->SetTextColor(0,0,0);
    $fill = !$fill;
}

// فتح PDF مباشرة
$pdf->Output("attendance_{$student['id']}.pdf","I");
