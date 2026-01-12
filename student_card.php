<?php
include "includes/config.php";
require_once('tcpdf_min/tcpdf.php');
include('phpqrcode/qrlib.php');

$id = $_GET['id'] ?? 0;

// جلب بيانات الطالب
$q = mysqli_query($conn,"SELECT students.*, parents.name as parent_name, parents.phone as parent_phone
                         FROM students
                         LEFT JOIN parents ON students.parent_id = parents.id
                         WHERE students.id=$id");
$student = mysqli_fetch_assoc($q);

if(!$student){
    die("الطالب غير موجود!");
}

// إنشاء PDF
$pdf = new TCPDF('P','mm','A4');
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('مركز رؤية لتحفيظ القرآن');
$pdf->SetTitle('كارت الطالب');
$pdf->SetMargins(15, 20, 15);
$pdf->AddPage();

// شعار السنتر (ضع شعارك في assets/logo.png)
$pdf->Image('assets/logo.png',80,10,50);

// عنوان الكارت
$pdf->SetFont('dejavusans','B',22);
$pdf->Ln(20);
$pdf->Cell(0,15,'كارت الطالب',0,1,'C');

// بيانات الطالب
$pdf->SetFont('dejavusans','',16);
$pdf->Cell(0,10,'الاسم: '.$student['name'],0,1,'C');
$pdf->Cell(0,10,'رقم التعريف: '.$student['id'],0,1,'C');
$pdf->Cell(0,10,'ولي الأمر: '.$student['parent_name'],0,1,'C');
$pdf->Cell(0,10,'رقم الهاتف: '.$student['parent_phone'],0,1,'C');

// توليد QR Code تلقائي إذا غير موجود
$qrFile = __DIR__ . "/qr_images/qr_".$student['id'].".png";
if(!file_exists($qrFile)){
    $attendance_url = "http://localhost/roaya_quran/attendance.php?id=".$student['id'];
    QRcode::png($attendance_url, $qrFile, QR_ECLEVEL_H, 6);
}

// إضافة QR Code للكارت
if(file_exists($qrFile)){
    $pdf->Image($qrFile,70,110,70,70);
}

// إخراج PDF مباشرة في المتصفح
$pdf->Output("student_card_$id.pdf","I");
