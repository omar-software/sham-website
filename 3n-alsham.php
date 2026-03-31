<?php include 'db.php'; 
include 'weather.php';


$quoteQuery = "SELECT text FROM quotes WHERE id=1";
$quoteResult = mysqli_query($conn, $quoteQuery);
if ($quoteResult && mysqli_num_rows($quoteResult) > 0) {
    $quoteData = mysqli_fetch_assoc($quoteResult);
    $quoteText = $quoteData['text'];
} else {
    $quoteText = '"الشام جنة الله على الأرض."';
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
    <meta charset="UTF-8" />
    <title>عن الشام</title>
    <link rel="stylesheet" href="style.css" />

    <div class="Logo">
        <img src="الهوية_البصرية.png" alt="">
    </div>

    <div class="weather-widget">
      <span class="weather-temp">🌤 <?= is_numeric($weatherTemp) ? round($weatherTemp) . '°C' : '--' ?></span>
      <span class="weather-desc"><?= htmlspecialchars($weatherDesc) ?></span>
    </div>
</head>
<body>
    <div class="wrapper">
        <!-- الهيدر -->
        <header class="header">
            <?php
            $headerDir = "uploads/header";
            if (is_dir($headerDir)) {
                $headerImages = array_diff(scandir($headerDir), ['.', '..']);
                $found = false;
                foreach ($headerImages as $headerImage) {
                    if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $headerImage)) {
                        echo "<img src='$headerDir/$headerImage' alt='صورة الهيدر' class='header-image'>";
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    echo "<p>لا توجد صورة للهيدر حالياً.</p>";
                }
            } else {
                echo "<p>مجلد الصور غير موجود.</p>";
            }
            ?>
        </header>

        <!-- شريط التنقل -->
        <nav id="mainNav" class="navbar">
            <ul>
                <li><a href="index.php">الرئيسية</a></li>
                <li><a href="3n-alsham.php">عن الشام</a></li>
                <li><a href="#contact">تواصل معنا</a></li>
            </ul>
        </nav>

        <!-- مقدمة عن الشام -->
        <section class="about-page intro">
            <h2>عن الشام</h2>
            <p>الشام منطقة تاريخية غنية بالثقافة والتراث، تجمع بين عراقة الماضي وروح الحاضر. تتميز الشام بمدنها العريقة، وحاراتها الضيقة التي تحمل بين جدرانها قصص الأجداد والحكايات الجميلة.</p>
        </section>

        <!-- أبرز المعالم -->
       <?php
            $highlightsQuery = "SELECT * FROM highlights ORDER BY id DESC";
            $highlightsResult = mysqli_query($conn, $highlightsQuery);
        ?>

        <section class="about-page highlights">
            <h3>أبرز معالم الشام</h3>
            <div class="cards">
                <?php if ($highlightsResult && mysqli_num_rows($highlightsResult) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($highlightsResult)): ?>
                        <div class="card">
                            <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>" />
                            <h4><?= htmlspecialchars($row['title']) ?></h4>
                            <p><?= htmlspecialchars($row['description']) ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>لا توجد معالم مضافة حالياً.</p>
                <?php endif; ?>
            </div>
        </section>


        <!-- الثقافة -->
        <section class="about-page culture">
            <h3>الثقافة في الشام</h3>
            <ul>
                <li>الشعر الشعبي والحكايات التي تناقلها الأجيال.</li>
                <li>الأطعمة التقليدية مثل الكبّة والمقلوبة.</li>
                <li>الأزياء التراثية والمناسبات الاجتماعية.</li>
                <li>الفنون والحرف اليدوية مثل التطريز والخزف.</li>
            </ul>
        </section>

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

        <!-- الفوتر -->
        <footer>
            <p>كل الحقوق محفوظة &copy; 2025</p>
        </footer>
    </div>

    <!-- مودال الصور -->
    <div id="imageModal" style="display: none; align-items: center; justify-content: center; position: fixed; z-index: 1000; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8);">
        <img id="modalImage" style="max-width: 90%; max-height: 90%;" />
    </div>

    <!-- أزرار التمرير -->
    <button id="scrollUp" class="scroll-btn">⬆</button>
    <button id="scrollDown" class="scroll-btn">⬇</button>

    <!-- سكريبتات -->
    <script>
        const nav = document.getElementById('mainNav');
        const navTop = nav.offsetTop;

        window.addEventListener('scroll', () => {
            if (window.scrollY >= navTop) {
                nav.classList.add('fixed');
            } else {
                nav.classList.remove('fixed');
            }
        });

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
