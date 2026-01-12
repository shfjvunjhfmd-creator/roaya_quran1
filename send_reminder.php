<?php
include "includes/config.php";

$phone = $_POST['parent_phone'] ?? '';
$student = $_POST['student_name'] ?? '';
$amount = $_POST['amount'] ?? '';

if($phone && $student && $amount){
    // نص الرسالة
    $msg = "السلام عليكم، تذكير: يرجى دفع مصاريف الطالب $student بمبلغ $amount جنيه.";

    // **طريقة إرسال SMS افتراضية**
    // هنا تستخدم أي خدمة SMS مثل Twilio أو Msg91
    // مثال تجريبي باستخدام mail() كمثال:
    // mail("$phone@smsgateway.example.com","تذكير مصاريف",$msg);

    echo "<script>alert('تم إرسال التذكير لولي الأمر: $phone'); window.location='fees.php';</script>";
}else{
    echo "<script>alert('خطأ: بيانات غير مكتملة'); window.location='fees.php';</script>";
}
