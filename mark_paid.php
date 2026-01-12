<?php
include "includes/config.php";

$fee_id = $_POST['fee_id'] ?? 0;
if($fee_id){
    mysqli_query($conn,"UPDATE fees SET status='مدفوع' WHERE id=$fee_id");
    echo "<script>alert('تم تحديث الحالة إلى مدفوع ✅'); window.location='fees.php';</script>";
}else{
    echo "<script>alert('حدث خطأ'); window.location='fees.php';</script>";
}
