<?php
include "includes/config.php";

$today = date("Y-m-d");

$q = mysqli_query($conn,"
SELECT students.name AS student_name, parents.phone
FROM students
JOIN parents ON parents.id = students.parent_id
WHERE students.id NOT IN (
  SELECT student_id FROM attendance WHERE date='$today'
)
");

while($row = mysqli_fetch_assoc($q)){
  $msg = "نحيطكم علمًا بغياب الطالب {$row['student_name']} اليوم عن مركز رؤية لتحفيظ القرآن الكريم";
  $msg = urlencode($msg);
  $phone = preg_replace('/[^0-9]/', '', $row['phone']);

  echo "
  <p>
    {$row['student_name']} —
    <a target='_blank'
    href='https://wa.me/2{$phone}?text={$msg}'>
    📱 إرسال تنبيه واتساب
    </a>
  </p>
  ";
}
