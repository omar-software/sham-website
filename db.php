<?php
$servername = "sql210.infinityfree.com";
$username = "if0_38049916";
$password = "4MbG4MegoRU";
$dbname = "if0_38049916_ent_mn_al7ara"; // عدّل هذا السطر

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// يمكنك الآن تنفيذ استعلامات SQL
// ✅ تأكيد الترميز
$conn->set_charset("utf8mb4");
?>
