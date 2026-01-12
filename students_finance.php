<?php
include "includes/auth.php";
include "includes/config.php";
require_once('tcpdf_min/tcpdf.php');

// إضافة دفعة مالية
if(isset($_POST['add_fee'])){
    $student_id = intval($_POST['student_id']);
    $amount = floatval($_POST['amount']);
    $notes = mysqli_real_escape_string($conn,$_POST['notes']);
    $date = date('Y-m-d');

    mysqli_query($conn,"INSERT INTO student_fees (student_id,amount,payment_date,notes)
                        VALUES ($student_id,$amount,'$date','$notes')");
    header("Location: students_finance.php");
    exit;
}

// جلب كل الطلاب
$students_q = mysqli_query($conn,"SELECT * FROM students ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>إدارة الطلاب والمصاريف | رؤية لتحفيظ القرآن</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.status-hadir { color: green; font-weight:bold; }
.status-ghaib { color: red; font-weight:bold; }
.card-student { border:2px solid #1e7f5c; border-radius:12px; padding:15px; margin-bottom:20px; }
.btn-report { background-color:#1e7f5c; color:white; }
</style>
</head>
<body class="p-4">

<h2 class="mb-4 text-center">إدارة الطلاب | الحضور والمصاريف</h2>

<!-- إضافة دفعة مالية -->
<div class="card mb-4 p-3">
    <h5>إضافة دفعة مالية</h5>
    <form method="post" class="row g-2">
        <div class="col-md-4">
            <select name="student_id" class="form-control" required>
                <option value="">اختر الطالب</option>
                <?php while($s=mysqli_fetch_assoc($students_q)): ?>
                    <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-3">
            <input type="number" step="0.01" name="amount" class="form-control" placeholder="المبلغ" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="notes" class="form-control" placeholder="ملاحظات">
        </div>
        <div class="col-md-2">
            <button type="submit" name="add_fee" class="btn btn-success w-100">إضافة</button>
        </div>
    </form>
</div>

<!-- عرض الطلاب -->
<div class="row">
<?php
$students_q = mysqli_query($conn,"SELECT * FROM students ORDER BY name ASC");
while($s=mysqli_fetch_assoc($students_q)):

    // إجمالي المدفوعات للطالب
    $fees_q = mysqli_query($conn,"SELECT SUM(amount) as total FROM student_fees WHERE student_id={$s['id']}");
    $fee_row = mysqli_fetch_assoc($fees_q);
    $total_paid = $fee_row['total'] ?? 0;

    // حضور الطالب اليوم
    $today = date('Y-m-d');
    $att_q = mysqli_query($conn,"SELECT status FROM attendance WHERE student_id={$s['id']} AND date='$today'");
    $status = (mysqli_num_rows($att_q)>0) ? 'حاضر' : 'غائب';
?>
<div class="col-md-4">
    <div class="card-student">
        <h5><?= $s['name'] ?></h5>
        <p>حالة اليوم: <span class="<?= $status=='حاضر'?'status-hadir':'status-ghaib' ?>"><?= $status ?></span></p>
        <p>إجمالي المدفوعات: <?= number_format($total_paid,2) ?> ج</p>
        <a href="student_report_pdf.php?id=<?= $s['id'] ?>" target="_blank" class="btn btn-report btn-sm">عرض تقرير PDF</a>
    </div>
</div>
<?php endwhile; ?>
</div>

</body>
</html>
