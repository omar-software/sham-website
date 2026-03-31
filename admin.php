<?php
include_once 'db.php';

session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$uploadDir = "gallery_images/";
$headerDir = "uploads/header";

if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
if (!file_exists($headerDir)) mkdir($headerDir, 0777, true);

$message = "";

// حذف صورة الهيدر
if (isset($_GET['delete_header'])) {
    array_map('unlink', glob($headerDir . "/*"));
    $message = "تم حذف صورة الهيدر بنجاح!";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// حذف صورة من معرض الصور
if (isset($_GET['delete'])) {
    $fileToDelete = basename($_GET['delete']);
    $filePath = $uploadDir . $fileToDelete;
    if (file_exists($filePath)) {
        unlink($filePath);
        $message = "تم حذف الصورة بنجاح!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // رفع صورة الهيدر
    if (isset($_FILES['header_image']) && $_FILES['header_image']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $uploadedType = $_FILES['header_image']['type'];

        if (in_array($uploadedType, $allowedTypes)) {
            if (!is_dir($headerDir)) {
                mkdir($headerDir, 0755, true);
            }

            // حذف الصور السابقة للهيدر
            array_map('unlink', glob($headerDir . "/*"));

            $extension = pathinfo($_FILES['header_image']['name'], PATHINFO_EXTENSION);
            $targetFile = $headerDir . "/header." . strtolower($extension);

            if (move_uploaded_file($_FILES['header_image']['tmp_name'], $targetFile)) {
                $message = "تم رفع صورة الهيدر بنجاح!";
            } else {
                $message = "حدث خطأ أثناء رفع صورة الهيدر.";
            }
        } else {
            $message = "صيغة ملف الهيدر غير مدعومة. استخدم JPG أو PNG أو GIF.";
        }
    }

    // رفع صورة معرض عادية
    elseif (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileName = basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $fileName;
        if (in_array($_FILES['image']['type'], $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $message = "تم رفع الصورة بنجاح!";
            } else {
                $message = "حدث خطأ أثناء رفع الصورة.";
            }
        } else {
            $message = "صيغة الملف غير مدعومة. استخدم JPG أو PNG أو GIF.";
        }
    }
    
    // تحديث النص الترحيبي
    elseif (isset($_POST['title']) && isset($_POST['content'])) {
        $newTitle = trim($_POST['title']);
        $newContent = trim($_POST['content']);
        if ($newTitle !== '' && $newContent !== '') {
            $titleEscaped = mysqli_real_escape_string($conn, $newTitle);
            $contentEscaped = mysqli_real_escape_string($conn, $newContent);

            $query = "UPDATE welcome_section SET title='$titleEscaped', content='$contentEscaped' WHERE id=1";
            if (mysqli_query($conn, $query)) {
                $message = "تم تحديث النص الترحيبي بنجاح.";
            } else {
                $message = "فشل في تحديث قاعدة البيانات.";
            }
        } else {
            $message = "يرجى ملء عنوان ونص الترحيب.";
        }
    }

    // تحديث نص الاقتباس
    if (isset($_POST['quote_text'])) {
        $quoteTextPost = trim($_POST['quote_text']);
        $quoteTextEscaped = mysqli_real_escape_string($conn, $quoteTextPost);
        // تحقق من وجود الاقتباس id=1
        $result = mysqli_query($conn, "SELECT id FROM quotes WHERE id=1");
        if (mysqli_num_rows($result) > 0) {
            $updateQuote = "UPDATE quotes SET text='$quoteTextEscaped' WHERE id=1";
            mysqli_query($conn, $updateQuote);
        } else {
            $insertQuote = "INSERT INTO quotes (id, text) VALUES (1, '$quoteTextEscaped')";
            mysqli_query($conn, $insertQuote);
        }
        $message = "تم تحديث نص الاقتباس بنجاح.";
    }

    // تحديث روابط التواصل الاجتماعي
    if (isset($_POST['update_social']) && isset($_POST['social']) && is_array($_POST['social'])) {
        foreach ($_POST['social'] as $id => $data) {
            $platform = mysqli_real_escape_string($conn, $data['platform']);
            $url = mysqli_real_escape_string($conn, $data['url']);
            $icon = mysqli_real_escape_string($conn, $data['icon']);

            $updateQuery = "UPDATE social_links SET platform='$platform', url='$url', icon='$icon' WHERE id=$id";
            mysqli_query($conn, $updateQuery);
        }
        $message = "تم تحديث روابط وسائل التواصل الاجتماعي بنجاح.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// قراءة روابط التواصل من DB (قبل عرض الصفحة)
$socialLinks = [];
$socialQuery = "SELECT * FROM social_links ORDER BY id ASC";
$socialResult = mysqli_query($conn, $socialQuery);
if ($socialResult && mysqli_num_rows($socialResult) > 0) {
    while ($row = mysqli_fetch_assoc($socialResult)) {
        $socialLinks[] = $row;
    }
}

// قراءة النص الترحيبي
$welcomeQuery = "SELECT title, content FROM welcome_section WHERE id=1";
$welcomeResult = mysqli_query($conn, $welcomeQuery);
if ($welcomeResult && mysqli_num_rows($welcomeResult) > 0) {
    $welcomeData = mysqli_fetch_assoc($welcomeResult);
    $title = $welcomeData['title'];
    $content = $welcomeData['content'];
} else {
    $title = "مرحباً بكم في الموقع";
    $content = "لم يتم العثور على نص ترحيبي.";
}

// قراءة صور المعرض وصورة الهيدر
$images = array_filter(scandir($uploadDir), function($item) {
    return !in_array($item, ['.', '..']);
});
$headerImage = glob($headerDir . "/*")[0] ?? null;

// قراءة نص الاقتباس
$quoteText = '';
$quoteId = 1;
$result = mysqli_query($conn, "SELECT text FROM quotes WHERE id = $quoteId");
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $quoteText = $row['text'];
} else {
    $defaultQuote = 'الشام جنة الله على الأرض.';
    mysqli_query($conn, "INSERT INTO quotes (id, text) VALUES ($quoteId, '" . mysqli_real_escape_string($conn, $defaultQuote) . "')");
    $quoteText = $defaultQuote;
}


?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>لوحة تحكم إدارة الصور والنصوص</title>
    <link rel="stylesheet" href="admin.css" />
</head>
<body>
<div class="container">
    <div class="top-buttons" style="margin-bottom: 20px;">
            <nav class="top-nav">
                <a href="admin2.php">ادارة المعالم</a>
                <a href="index.php">العودة إلى الموقع</a>
                <a href="logout.php">تسجيل الخروج</a>
            </nav>
        </div>
    <h1>لوحة تحكم إدارة الصور والنصوص</h1>

    <?php if ($message): ?>
        <div class="alert"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <h2>إدارة صورة الهيدر</h2>
    <div class="header-image">
        <?php if ($headerImage): ?>
            <img src="<?= htmlspecialchars($headerImage) ?>" alt="صورة الهيدر" />
            <br />
            <a href="?delete_header=1" onclick="return confirm('هل أنت متأكد من حذف صورة الهيدر؟')">حذف صورة الهيدر</a>
        <?php else: ?>
            <p>لا توجد صورة هيدر حالياً.</p>
        <?php endif; ?>
    </div>
    <form action="" method="post" enctype="multipart/form-data" class="clearfix">
        <label for="header_image">اختر صورة جديدة للهيدر:</label>
        <input type="file" name="header_image" id="header_image" required />
        <button type="submit">رفع صورة الهيدر</button>
    </form>

    <hr />

    <h2>إدارة الصور العادية</h2>
    <form action="" method="post" enctype="multipart/form-data" class="clearfix">
        <label for="image">اختر صورة لرفعها:</label>
        <input type="file" name="image" id="image" required />
        <button type="submit">رفع الصورة</button>
    </form>

    <div class="images">
        <?php foreach ($images as $image): ?>
            <div class="image-card">
                <img src="gallery_images/<?= htmlspecialchars($image) ?>" alt="صورة" />
                <a href="?delete=<?= urlencode($image) ?>" onclick="return confirm('هل أنت متأكد من حذف هذه الصورة؟')">حذف</a>
            </div>
        <?php endforeach; ?>
    </div>

    <hr />

    <h2>تعديل النصوص</h2>
    <form action="" method="post">
        <label for="title">العنوان:</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($title) ?>" required />

        <label for="content">النص:</label>
        <textarea id="content" name="content" rows="4" required><?= htmlspecialchars($content) ?></textarea>

        <hr />

        <label for="quote_text">نص الاقتباس:</label>
        <textarea id="quote_text" name="quote_text" rows="3"><?= htmlspecialchars($quoteText) ?></textarea>

        <hr />

        <h2>تعديل روابط وسائل التواصل الاجتماعي</h2>
        <?php if (!empty($socialLinks)): ?>
            <?php foreach ($socialLinks as $social): ?>
                <div class="social-edit-item">
                    <label>المنصة:
                        <input type="text" name="social[<?= $social['id'] ?>][platform]" value="<?= htmlspecialchars($social['platform']) ?>" required>
                    </label>
                    <label>الرابط:
                        <input type="url" name="social[<?= $social['id'] ?>][url]" value="<?= htmlspecialchars($social['url']) ?>" required>
                    </label>
                    <label>مسار الأيقونة:
                        <input type="text" name="social[<?= $social['id'] ?>][icon]" value="<?= htmlspecialchars($social['icon']) ?>" required>
                    </label>
                </div>
                <hr>
            <?php endforeach; ?>
        <?php else: ?>
            <p>لا توجد بيانات لوسائل التواصل الاجتماعي حالياً.</p>
        <?php endif; ?>
            <button type="submit" name="update_social">حفظ التعديلات</button>
        <hr />
        
    </form>

    
</div>
</body>
</html>
