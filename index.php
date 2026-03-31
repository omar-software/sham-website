<?php
include 'db.php'; // الاتصال بقاعدة البيانات
include 'weather.php';
include 'news.php';


$query = "SELECT title, content FROM welcome_section LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $title = htmlspecialchars($row['title']);
    $content = nl2br(htmlspecialchars($row['content']));
} else {
    $title = "عنوان الترحيب غير متوفر";
    $content = "لم يتم العثور على محتوى ترحيبي.";
}

$quoteQuery = "SELECT text FROM quotes WHERE id=1"; // أو آخر اقتباس حسب حاجتك
$quoteResult = mysqli_query($conn, $quoteQuery);
if ($quoteResult && mysqli_num_rows($quoteResult) > 0) {
    $quoteData = mysqli_fetch_assoc($quoteResult);
    $quoteText = $quoteData['text'];
} else {
    $quoteText = '"الشام جنة الله على الأرض."';  // نص افتراضي في حال عدم وجود بيانات
}

$socialQuery = "SELECT platform, url, icon FROM social_links ORDER BY id ASC";
$socialResult = mysqli_query($conn, $socialQuery);

$socialLinks = [];
if ($socialResult && mysqli_num_rows($socialResult) > 0) {
    while ($row = mysqli_fetch_assoc($socialResult)) {
        $socialLinks[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>انت من الحارة؟</title>
    <link rel="stylesheet" href="style.css">

    <div class="Logo">
        <img src="الهوية_البصرية.png" alt="">
    </div>

    <div class="weather-widget">
      <span class="weather-temp">🌤 <?= is_numeric($weatherTemp) ? round($weatherTemp) . '°C' : '--' ?></span>
      <span class="weather-desc"><?= htmlspecialchars($weatherDesc) ?></span>
    </div>

    <!--
        <div class="news-widget">
            <p><strong><?= htmlspecialchars($newsTitle) ?></strong></p>
            <p><?= htmlspecialchars($newsDesc) ?></p>
        </div>
    -->



</head>
<body>
<div class="wrapper about-page">
    <!-- الهيدر -->
    <header class="header">
        <?php
        $headerDir = "uploads/header";
        $headerImages = is_dir($headerDir) ? array_diff(scandir($headerDir), ['.', '..']) : [];
        if (!empty($headerImages)) {
            foreach ($headerImages as $headerImage) {
                if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $headerImage)) {
                    echo "<img src='$headerDir/$headerImage' alt='صورة الهيدر'>";
                    break;
                }
            }
        } else {
            echo "<p>لا توجد صورة للهيدر حالياً.</p>";
        }
        ?>
    </header>

    <!-- شريط التنقل -->
    <nav class="navbar" id="mainNav">
        <ul>
            <li><a href="index.php">الرئيسية</a></li>
            <li><a href="3n-alsham.php">عن الشام</a></li>
            <li><a href="#contact">تواصل معنا</a></li>
        </ul>
    </nav>

    <!-- الترحيب -->
    <section class="welcome">
        <h2><?= $title ?></h2>
        <p><?= $content ?></p>
    </section>

    <!-- المعرض -->
    <section class="homepage gallery">
        <h3>مشاهد من الشام</h3>
        <div class="images">
            <?php
            $galleryDir = "gallery_images";
            if (is_dir($galleryDir)) {
                $galleryImages = array_values(array_filter(array_diff(scandir($galleryDir), ['.', '..']), function ($img) {
                    return preg_match('/\.(jpg|jpeg|png|gif)$/i', $img);
                }));

                foreach ($galleryImages as $index => $img) {
                    $imageList = json_encode(array_map(function ($i) use ($galleryDir) {
                        return "$galleryDir/$i";
                    }, $galleryImages), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                    echo "<img src='$galleryDir/$img' alt='صورة من الشام' onclick='openModal($imageList, $index)'>";
                }
            } else {
                echo "<p>لا توجد صور في المعرض حالياً.</p>";
            }
            ?>
        </div>
    </section>

    <!-- اقتباس -->
    <blockquote class="quote">
        <p><?= htmlspecialchars($quoteText) ?></p>
    </blockquote>


    <!-- التواصل الاجتماعي -->
    <section class="social-section" id="contact">
        <h3>تابعونا على وسائل التواصل</h3>
        <div class="social-logos">
            <?php foreach ($socialLinks as $social): ?>
                <div class="social-item">
                    <a href="<?= htmlspecialchars($social['url']) ?>" target="_blank" rel="noopener noreferrer">
                        <img src="<?= htmlspecialchars($social['icon']) ?>" alt="<?= htmlspecialchars($social['platform']) ?>" class="social-logo">
                        <p><?= htmlspecialchars($social['platform']) ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>


    <!-- التذييل -->
    <footer>
        <p>كل الحقوق محفوظة &copy; 2025</p>
    </footer>
</div>

<!-- نافذة الصور -->
<div id="imageModal">
  <span onclick="closeModal()" class="close-btn">&times;</span>
  <img id="modalImage" class="modal-image">
  <div class="modal-nav">
    <button onclick="prevImage()"> ➡السابق</button>
    <button onclick="nextImage()">التالي ⬅</button>
  </div>
</div>

<!-- أزرار التمرير -->
<button id="scrollUp" class="scroll-btn">⬆</button>
<button id="scrollDown" class="scroll-btn">⬇</button>

<!-- سكريبتات -->
<script>
  let currentImages = [];
  let currentIndex = 0;

  function openModal(images, index) {
    currentImages = images;
    currentIndex = index;
    showImage();
    document.getElementById("imageModal").style.display = "flex";
    document.body.style.overflow = "hidden";
  }

  function closeModal() {
    document.getElementById("imageModal").style.display = "none";
    document.body.style.overflow = "auto";
  }

  function showImage() {
    document.getElementById("modalImage").src = currentImages[currentIndex];
  }

  function nextImage() {
    if (!currentImages.length) return;
    currentIndex = (currentIndex + 1) % currentImages.length;
    showImage();
  }

  function prevImage() {
    if (!currentImages.length) return;
    currentIndex = (currentIndex - 1 + currentImages.length) % currentImages.length;
    showImage();
  }

  document.getElementById("imageModal").addEventListener("click", function(e) {
    if (e.target === this) closeModal();
  });

  document.addEventListener("keydown", function(e) {
    if (document.getElementById("imageModal").style.display === "flex") {
      if (e.key === "ArrowRight") nextImage();
      else if (e.key === "ArrowLeft") prevImage();
      else if (e.key === "Escape") closeModal();
    }
  });

  document.addEventListener("DOMContentLoaded", function () {
    const scrollUpBtn = document.getElementById("scrollUp");
    const scrollDownBtn = document.getElementById("scrollDown");

    scrollUpBtn.addEventListener("click", () => {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });

    scrollDownBtn.addEventListener("click", () => {
      window.scrollTo({ top: document.body.scrollHeight, behavior: "smooth" });
    });

    window.addEventListener("scroll", () => {
      const scrollY = window.scrollY;
      const maxScroll = document.body.scrollHeight - window.innerHeight;

      scrollUpBtn.style.display = scrollY > 300 ? "block" : "none";
      scrollDownBtn.style.display = scrollY < maxScroll - 300 ? "block" : "none";
    });

    scrollDownBtn.style.display = "block";
  });

  const nav = document.getElementById('mainNav');
  const navTop = nav.offsetTop;

  window.addEventListener('scroll', () => {
    if (window.scrollY >= navTop) {
      nav.classList.add('fixed');
    } else {
      nav.classList.remove('fixed');
    }
  });
</script>

<button class="toggle-mode" onclick="document.body.classList.toggle('dark-mode')">🌙 / ☀️</button>
<!-- الزر -->
<button class="toggle-mode" onclick="toggleDarkMode()">🌙 / ☀️</button>

<script>
  // عند تحميل الصفحة، تحقق من الوضع المحفوظ
  if (localStorage.getItem('theme') === 'dark') {
    document.body.classList.add('dark-mode');
  }

  // عند الضغط على الزر
  function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    const isDark = document.body.classList.contains('dark-mode');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
  }
</script>

</body>
</html>
