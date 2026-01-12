<?php
include('phpqrcode/qrlib.php');

$student_id = $_GET['id'] ?? 0;

// الرابط لتسجيل الحضور
$attendance_url = "http://localhost/roaya_quran/scan_attendance.php?id=$student_id";

// مسار حفظ الصورة محليًا
$filename = __DIR__ . "/qr_images/qr_$student_id.png";

// توليد QR Code وحفظه
QRcode::png($attendance_url, $filename, QR_ECLEVEL_H, 6);

// عرض الصورة على المتصفح
header('Content-Type: image/png');
readfile($filename);
