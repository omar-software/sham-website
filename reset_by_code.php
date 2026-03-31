<?php
include 'db.php';
session_start();

$secret_code = "entmn_reset_2024"; // عدل الكود كما تريد

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code']);
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $new_password = $_POST['password'];

    if ($code !== $secret_code) {
        $message = "❌ الكود السري غير صحيح!";
    } else {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
            $stmt->bind_param("ss", $hashed, $email);
            $stmt->execute();
            $message = "✅ تم تحديث كلمة المرور بنجاح.";
        } else {
            $message = "❌ المستخدم غير موجود.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إعادة تعيين كلمة المرور</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="box">
        <h2>إعادة تعيين كلمة المرور</h2>
        <?php if ($message): ?>
            <div class="msg <?= str_starts_with($message, '✅') ? 'success' : '' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="code" placeholder="الكود السري" required>
            <input type="email" name="email" placeholder="البريد الإلكتروني" required>
            <input type="password" name="password" placeholder="كلمة المرور الجديدة" required>
            <button type="submit">تحديث كلمة المرور</button>
        </form>
        <p><a href="login.php">تسجيل الدخول</a></p>
    </div>
</body>
</html>
