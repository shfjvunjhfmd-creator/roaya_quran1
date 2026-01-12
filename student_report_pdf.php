<?php
include "includes/config.php";
require_once('tcpdf_min/tcpdf.php');

$id = intval($_GET['id']);
if(!$id) die("الطالب غير موجود");

// بيانات الطالب
$student_q = mysqli_query($conn,"SELECT * FROM students WHERE id=$id");
$student = mysqli_fetch_assoc($student_q);
if(!$student) die("الطالب غير موجود");

// إنشاء PDF
$pdf = new TCPDF('P','mm','A4');
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle("تقرير {$student['name']}");
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(15,20,15);
$pdf->AddPage();

// العنوان
$pdf->SetFont('dejavusans','B',16);
$pdf->SetTextColor(30,127,92);
$pdf->Cell(0,10,"تقرير الطالب: {$student['name']}",0,1,'C');
$pdf->Ln(5);

// جدول الحضور
$pdf->SetFont('dejavusans','B',12);
$pdf->SetFillColor(30,127,92);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(70,10,'اليوم',1,0,'C',1);
$pdf->Cell(70,10,'الحالة',1,1,'C',1);

$pdf->SetFont('dejavusans','',12);
$pdf->SetTextColor(0,0,0);
$month = date('Y-m');
$start_date = $month.'-01';
$end_date = date('Y-m-t', strtotime($start_date));

$period = new DatePeriod(
    new DateTime($start_date),
    new DateInterval('P1D'),
    new DateTime(date('Y-m-d', strtotime($end_date.' +1 day')))
);

foreach($period as $dt){
    $d = $dt->format('Y-m-d');
    $att_q = mysqli_query($conn,"SELECT status FROM attendance WHERE student_id=$id AND date='$d'");
    $status = (mysqli_num_rows($att_q)>0)? 'حاضر':'غائب';

    $pdf->Cell(70,8,$d,1,0,'C');
    if($status=='حاضر') $pdf->SetTextColor(0,128,0);
    else $pdf->SetTextColor(255,0,0);
    $pdf->Cell(70,8,$status,1,1,'C');
    $pdf->SetTextColor(0,0,0);
}

// جدول المصاريف
$pdf->Ln(5);
$pdf->SetFillColor(30,127,92);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(70,10,'تاريخ الدفع',1,0,'C',1);
$pdf->Cell(70,10,'المبلغ',1,1,'C',1);
$pdf->SetTextColor(0,0,0);

$fees_q = mysqli_query($conn,"SELECT * FROM student_fees WHERE student_id=$id ORDER BY payment_date ASC");
$total_paid = 0;
while($f=mysqli_fetch_assoc($fees_q)){
    $pdf->Cell(70,8,$f['payment_date'],1,0,'C');
    $pdf->Cell(70,8,number_format($f['amount'],2).' ج',1,1,'C');
    $total_paid += $f['amount'];
}

$pdf->Ln(3);
$pdf->SetFont('dejavusans','B',14);
$pdf->Cell(0,8,"إجمالي المدفوعات: ".number_format($total_paid,2)." ج",0,1,'C');

// فتح PDF مباشرة
$pdf->Output("report_student_{$id}.pdf","I");
