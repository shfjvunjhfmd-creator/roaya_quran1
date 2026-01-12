<?php
include "includes/config.php";
require_once('tcpdf_min/tcpdf.php');
include('phpqrcode/qrlib.php');

// التحقق من وجود ID
$id = intval($_GET['id'] ?? 0);
if(!$id){
    die("خطأ: لم يتم تحديد الطالب.");
}

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
$pdf->AddPage();
$pdf->SetFont('dejavusans','B',22);
$pdf->Cell(0,15,'كارت الطالب',0,1,'C');

$pdf->SetFont('dejavusans','',16);
$pdf->Cell(0,10,'الاسم: '.htmlspecialchars($student['name']),0,1,'C');
$pdf->Cell(0,10,'رقم التعريف: '.$student['id'],0,1,'C');
$pdf->Cell(0,10,'ولي الأمر: '.htmlspecialchars($student['parent_name']),0,1,'C');
$pdf->Cell(0,10,'رقم الهاتف: '.htmlspecialchars($student['parent_phone']),0,1,'C');

// توليد QR Code
$qrDir = __DIR__ . "/qr_images";
if(!is_dir($qrDir)){
    mkdir($qrDir, 0755, true);
}
$qrFile = $qrDir . "/qr_".$student['id'].".png";
if(!file_exists($qrFile)){
    $attendance_url = "http://localhost/roaya_quran/scan_attendance.php?id=".$student['id'];
    QRcode::png($attendance_url, $qrFile, QR_ECLEVEL_H, 6);
}

// إضافة QR Code للكارت
if(file_exists($qrFile)){
    $pdf->Image($qrFile,70,85,70,70);
}

// إخراج PDF مباشرة
$pdf->Output("student_card_".$id.".pdf","I");
?>
