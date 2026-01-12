<?php
include "includes/config.php";
require_once('tcpdf_min/tcpdf.php');
include('phpqrcode/qrlib.php');

// تأكد من وجود مجلد QR
$qrDir = __DIR__ . '/qr_images';
if(!is_dir($qrDir)){
    mkdir($qrDir, 0755, true);
}

// جلب كل الطلاب مع بيانات ولي الأمر
$students = mysqli_query($conn,"SELECT students.*, parents.name as parent_name, parents.phone as parent_phone
                                FROM students
                                LEFT JOIN parents ON students.parent_id = parents.id");

if(mysqli_num_rows($students) == 0){
    die("لا يوجد طلاب مسجلين!");
}

// إنشاء PDF جديد
$pdf = new TCPDF('P','mm','A4');
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('مركز رؤية لتحفيظ القرآن');
$pdf->SetTitle('كروت الطلاب');
$pdf->SetMargins(15, 20, 15);

while($student = mysqli_fetch_assoc($students)){
    $pdf->AddPage();

    // العنوان
    $pdf->SetFont('dejavusans','B',22);
    $pdf->Cell(0,15,'كارت الطالب',0,1,'C');

    // إطار الكارت
    $pdf->SetLineWidth(0.5);
    $pdf->Rect(15,40,180,100);

    $pdf->Ln(10);
    $pdf->SetFont('dejavusans','',16);

    // بيانات الطالب
    $pdf->Cell(0,10,'الاسم: '.htmlspecialchars($student['name']),0,1,'C');
    $pdf->Cell(0,10,'رقم التعريف: '.$student['id'],0,1,'C');

    // بيانات ولي الأمر
    $pdf->Cell(0,10,'ولي الأمر: '.htmlspecialchars($student['parent_name']),0,1,'C');
    $pdf->Cell(0,10,'رقم الهاتف: '.htmlspecialchars($student['parent_phone']),0,1,'C');

    // توليد QR Code تلقائي إذا غير موجود
    $qrFile = $qrDir . "/qr_".$student['id'].".png";
    if(!file_exists($qrFile)){
        $attendance_url = "http://localhost/roaya_quran/scan_attendance.php?id=".$student['id'];
        QRcode::png($attendance_url, $qrFile, QR_ECLEVEL_H, 6);
    }

    // إضافة QR Code للكارت
    if(file_exists($qrFile)){
        $pdf->Image($qrFile,70,85,70,70); // X, Y, Width, Height
    }
}

// إخراج PDF لكل الطلاب في ملف واحد
$pdf->Output("all_student_cards.pdf","I");
?>
