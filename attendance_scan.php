<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>تسجيل الحضور</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://unpkg.com/html5-qrcode"></script>
<style>
body { font-family: Tahoma; background:#f5f5f5; }
.container { margin-top:50px; text-align:center; }
#reader { width: 300px; margin:auto; }
</style>
</head>
<body>

<div class="container">
<h2>تسجيل الحضور بالمسح QR</h2>
<p>وجه كاميرا الموبايل على كود الطالب</p>
<div id="reader"></div>
<div id="result" style="margin-top:20px; font-weight:bold;"></div>
</div>

<script>
const reader = new Html5Qrcode("reader");
reader.start(
    { facingMode: "environment" },
    { fps: 10, qrbox: 250 },
    qrCodeMessage => {
        fetch(`attendance.php?id=${qrCodeMessage}`)
        .then(res => res.text())
        .then(msg => {
            document.getElementById('result').innerText = msg;
            // ممكن توقف المسح بعد تسجيل حضور الطالب
            reader.stop();
        });
    }
);
</script>

</body>
</html>
