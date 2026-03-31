<?php
include_once 'db.php';
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$uploadDir = "uploads/highlights/";
if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

$message = "";

// حذف معلم
if (isset($_GET['delete_highlight'])) {
    $highlightId = intval($_GET['delete_highlight']);
    $highlight = mysqli_fetch_assoc(mysqli_query($conn, "SELECT image FROM highlights WHERE id=$highlightId"));
    if ($highlight) {
        $imgPath = $highlight['image'];
        if (file_exists($imgPath)) unlink($imgPath);
        mysqli_query($conn, "DELETE FROM highlights WHERE id=$highlightId");
        $message = "تم حذف المعلم بنجاح.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// تعديل معلم
$editHighlight = null;
if (isset($_GET['edit_highlight'])) {
    $editId = intval($_GET['edit_highlight']);
    $result = mysqli_query($conn, "SELECT * FROM highlights WHERE id = $editId");
    if ($result && mysqli_num_rows($result) > 0) {
        $editHighlight = mysqli_fetch_assoc($result);
    }
}

// معالجة POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // تعديل معلم موجود
    if (isset($_POST['highlight_id']) && is_numeric($_POST['highlight_id'])) {
        $highlightId = intval($_POST['highlight_id']);
        $title = mysqli_real_escape_string($conn, $_POST['highlight_title']);
        $desc = mysqli_real_escape_string($conn, $_POST['highlight_desc']);

        if (isset($_FILES['highlight_image']) && $_FILES['highlight_image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['highlight_image']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('highlight_') . '.' . strtolower($ext);
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['highlight_image']['tmp_name'], $targetFile)) {
                $old = mysqli_fetch_assoc(mysqli_query($conn, "SELECT image FROM highlights WHERE id = $highlightId"));
                if ($old && file_exists($old['image'])) unlink($old['image']);

                mysqli_query($conn, "UPDATE highlights SET title='$title', description='$desc', image='$targetFile' WHERE id=$highlightId");
                $message = "تم تحديث بيانات المعلم بنجاح.";
            } else {
                $message = "فشل في رفع الصورة الجديدة.";
            }
        } else {
            mysqli_query($conn, "UPDATE highlights SET title='$title', description='$desc' WHERE id=$highlightId");
            $message = "تم تحديث بيانات المعلم بدون تغيير الصورة.";
        }

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    // إضافة معلم جديد
    elseif (isset($_POST['highlight_title'], $_POST['highlight_desc'], $_FILES['highlight_image'])) {
        $title = mysqli_real_escape_string($conn, $_POST['highlight_title']);
        $desc = mysqli_real_escape_string($conn, $_POST['highlight_desc']);

        if ($_FILES['highlight_image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['highlight_image']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('highlight_') . '.' . strtolower($ext);
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['highlight_image']['tmp_name'], $targetFile)) {
                $query = "INSERT INTO highlights (title, description, image) VALUES ('$title', '$desc', '$targetFile')";
                mysqli_query($conn, $query);
                $message = "تمت إضافة المعلم بنجاح.";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } else {
                $message = "فشل في رفع صورة المعلم.";
            }
        }
    }
}

$highlights = [];
$highlightResult = mysqli_query($conn, "SELECT * FROM highlights ORDER BY id DESC");
if ($highlightResult) {
    while ($row = mysqli_fetch_assoc($highlightResult)) $highlights[] = $row;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة المعالم</title>
        <style>
            body { font-family: 'Cairo', sans-serif; background: #f5f6fa; padding: 20px; }
            h2 { text-align: center; color: #2f3640; }
            form { background: #fff; padding: 70px; border-radius: 10px; max-width: 600px; margin: auto; margin-bottom: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
            input, textarea { width: 100%; padding: 10px; margin: 10px 0; border-radius: 6px; border: 1px solid #ccc; }
            button { background: #00a8ff; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; }
            .card { background: #fff; padding: 15px; border-radius: 10px; margin: 10px; width: 280px; box-shadow: 0 0 5px rgba(0,0,0,0.1); }
            .card img { max-width: 100%; border-radius: 6px; }
            .card-container { display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; }
        </style>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="container">
   
            <nav class="top-nav">
                
                 <a href="admin.php">الادارة</a>
                <a href="index.php">العودة إلى الموقع</a>
                <a href="logout.php">تسجيل الخروج</a>
            <
        </nav>
        
    <h2><?= isset($_GET['edit_highlight']) ? 'تعديل المعلم' : 'إضافة معلم جديد' ?></h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="highlight_id" value="<?= isset($editHighlight['id']) ? $editHighlight['id'] : '' ?>">
        <input type="text" name="highlight_title" placeholder="العنوان" value="<?= $editHighlight['title'] ?? '' ?>" required>
        <textarea name="highlight_desc" placeholder="الوصف" required><?= $editHighlight['description'] ?? '' ?></textarea>
        <input type="file" name="highlight_image" accept="image/*" <?= isset($_GET['edit_highlight']) ? '' : 'required' ?>>
        <button type="submit"> <?= isset($_GET['edit_highlight']) ? 'تحديث' : 'إضافة' ?> </button>

        <?php if (isset($_GET['edit_highlight'])): ?>
            <p style="text-align:center;">
                <a href="admin2.php" class="btn">إلغاء التعديل</a>
            </p>
        <?php endif; ?>
    </form>

    <h2>قائمة المعالم</h2>
    <div class="card-container">
        <?php foreach ($highlights as $highlight): ?>
            <div class="card">
                <img src="<?= htmlspecialchars($highlight['image']) ?>" alt="صورة">
                <h4><?= htmlspecialchars($highlight['title']) ?></h4>
                <p><?= htmlspecialchars($highlight['description']) ?></p>
                <a href="?edit_highlight=<?= $highlight['id'] ?>">تعديل</a> |
                <a href="?delete_highlight=<?= $highlight['id'] ?>" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</a>
                
            </div>
            
        <?php endforeach; ?>
    </div>

    </div>    
</body>
</html>
