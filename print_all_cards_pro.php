<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "includes/config.php";
require_once('tcpdf_min/tcpdf.php');
include('phpqrcode/qrlib.php');

// ضبط الترميز للعربي
mysqli_set_charset($conn,"utf8");

// رابط شعار السنتر (حطه في المشروع)
$logoFile = __DIR__ . "/assets/logo.png"; // ضع شعارك هنا

// جلب كل الطلاب مع بيانات ولي الأمر
$students = mysqli_query($conn,"
SELECT students.*, parents.name AS parent_name, parents.phone AS parent_phone
FROM students
LEFT JOIN parents ON students.parent_id = parents.id
");

if(mysqli_num_rows($students) == 0){
    die("لا يوجد طلاب للطباعة");
}

// إنشاء PDF جديد
$pdf = new TCPDF('P','mm','A4');
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('مركز رؤية لتحفيظ القرآن');
$pdf->SetTitle('كروت الطلاب');
$pdf->SetMargins(15, 20, 15);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// الخط العربي المدمج
$fontname = 'dejavusans';

while($student = mysqli_fetch_assoc($students)){

    $pdf->AddPage();

    $pdf->SetFont($fontname,'',14);

    // **خلفية الكارت**
    $pdf->SetFillColor(230, 245, 255); // أزرق فاتح
    $pdf->Rect(10, 10, 190, 120, 'F');

    // **إطار الكارت**
    $pdf->SetLineWidth(0.8);
    $pdf->SetDrawColor(0, 102, 204); // أزرق داكن
    $pdf->Rect(10, 10, 190, 120);

    // شعار السنتر
    if(file_exists($logoFile)){
        $pdf->Image($logoFile, 15, 12, 30, 30); // X,Y,Width,Height
    }

    // بيانات الطالب
    $pdf->SetXY(50, 15);
    $pdf->Cell(0,10,'كارت الطالب',0,1,'L');
    $pdf->Ln(2);
    $pdf->SetX(50);
    $pdf->Cell(0,8,'الاسم: '.$student['name'],0,1,'L');
    $pdf->SetX(50);
    $pdf->Cell(0,8,'رقم الطالب: '.$student['id'],0,1,'L');
    $pdf->SetX(50);
    $pdf->Cell(0,8,'ولي الأمر: '.$student['parent_name'],0,1,'L');
    $pdf->SetX(50);
    $pdf->Cell(0,8,'رقم الهاتف: '.$student['parent_phone'],0,1,'L');

    // QR Code
    $qrDir = __DIR__ . "/qr_images/";
    if(!is_dir($qrDir)){
        mkdir($qrDir);
    }
    $qrFile = $qrDir."qr_".$student['id'].".png";
    if(!file_exists($qrFile)){
        $url = "http://localhost/roaya_quran/scan_attendance.php?id=".$student['id'];
        QRcode::png($url, $qrFile, QR_ECLEVEL_H, 6);
    }

    $pdf->Image($qrFile,140,50,60,60); // مكان QR
}

// إخراج PDF لكل الطلاب في ملف واحد
$pdf->Output("student_cards_pro.pdf","I");
