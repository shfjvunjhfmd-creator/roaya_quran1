<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول | رؤية</title>

    <style>
        body {
            margin: 0;
            font-family: Tahoma;
            background: linear-gradient(135deg, #1e7f5c, #3ba17c);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background: #fff;
            width: 360px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            text-align: center;
        }

        h2 {
            color: #1e7f5c;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #1e7f5c;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        .error {
            color: red;
            margin-bottom: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>تسجيل الدخول</h2>

    <?php
    if(isset($_GET['error'])){
        echo "<div class='error'>".htmlspecialchars($_GET['error'])."</div>";
    }
    ?>

    <form action="check_login.php" method="POST">
        <input type="text" name="username" placeholder="اسم المستخدم" required autofocus>
        <input type="password" name="password" placeholder="كلمة المرور" required>
        <button type="submit">دخول</button>
    </form>
</div>

</body>
</html>
