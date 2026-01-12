<?php
require "vendor/autoload.php";
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

$code = trim($_GET['code'] ?? '');
if(!$code){
    die("❌ لم يتم تحديد الكود لإنشاء QR");
}

// إنشاء QR Code
$qr = QrCode::create($code)
    ->setSize(150)
    ->setMargin(10);

// توليد PNG
$writer = new PngWriter();
header('Content-Type: '.$qr->getContentType());
echo $writer->write($qr)->getString();
?>
