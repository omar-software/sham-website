<?php
$servername = "";
$username = "";
$password = "";
$dbname = "if0_38049916_ent_mn_al7ara"

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// يمكنك الآن تنفيذ استعلامات SQL
// ✅ تأكيد الترميز
$conn->set_charset("utf8mb4");
?>
