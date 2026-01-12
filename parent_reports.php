<?php
session_start();
include "includes/config.php";

// التحقق من وجود parent_id
$pid = intval($_SESSION['parent_id'] ?? 0);
if(!$pid){
    die("❌ لم يتم تسجيل الدخول كولي أمر");
}

// جلب بيانات الطالب المرتبط بالولي
$result = mysqli_query($conn, "SELECT id, name FROM students WHERE parent_id=$pid");
if(!$result){
    die("خطأ في قاعدة البيانات: " . mysqli_error($conn));
}

$s = mysqli_fetch_assoc($result);
if(!$s){
    die("❌ لا يوجد طالب مرتبط بهذا الحساب");
}
?>
<h2>تقارير الطالب: <?= htmlspecialchars($s['name']) ?></h2>
<a href="parent_pdf.php?id=<?= $s['id'] ?>" target="_blank" class="btn btn-primary mt-2">
    تحميل تقرير PDF
</a>
