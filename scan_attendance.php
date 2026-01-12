<?php
include "includes/config.php";

$student_id = intval($_GET['id'] ?? 0);
$today = date('Y-m-d');

// التحقق من وجود الطالب
$checkStudent = mysqli_query($conn, "SELECT * FROM students WHERE id=$student_id");
if(!$checkStudent){
    die("خطأ في قاعدة البيانات: " . mysqli_error($conn));
}
if(mysqli_num_rows($checkStudent) == 0){
    die("❌ الطالب غير موجود");
}

// منع التكرار في نفس اليوم
$check = mysqli_query($conn,"SELECT * FROM attendance 
                             WHERE student_id=$student_id AND date='$today'");
if(!$check){
    die("خطأ في قاعدة البيانات: " . mysqli_error($conn));
}

if(mysqli_num_rows($check) == 0){
    $insert = mysqli_query($conn,"INSERT INTO attendance (student_id,date,status)
                                  VALUES ($student_id,'$today','حاضر')");
    if($insert){
        echo "✅ تم تسجيل الحضور بنجاح";
    } else {
        echo "❌ حدث خطأ أثناء تسجيل الحضور: " . mysqli_error($conn);
    }
}else{
    echo "⚠️ تم تسجيل الحضور مسبقًا اليوم";
}
?>
