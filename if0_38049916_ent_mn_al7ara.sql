-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql210.infinityfree.com
-- Erstellungszeit: 31. Mrz 2026 um 04:55
-- Server-Version: 11.4.10-MariaDB
-- PHP-Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `if0_38049916_ent_mn_al7ara`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `content_texts`
--

CREATE TABLE `content_texts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `highlights`
--

CREATE TABLE `highlights` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `highlights`
--

INSERT INTO `highlights` (`id`, `title`, `description`, `image`) VALUES
(1, 'جبل قاسيون', 'يُطل جبل قاسيون على دمشق، مانحًا الزوار منظرًا بانوراميًا رائعًا للمدينة.\r\n\r\n', 'uploads/highlights/highlight_6845a1e0c2b5d.jpg'),
(2, 'سوق الحميدية', 'سوق الحميدية هو القلب النابض للتجارة في الشام، يجمع بين عبق الماضي وتنوع الحاضر.\r\n\r\n', 'uploads/highlights/highlight_6845a2bf8d991.jpg'),
(3, 'الجامع الأموي', 'يُعتبر الجامع الأموي من أبرز معالم دمشق وأكثرها شهرة، حيث يجمع بين العمارة الإسلامية العريقة والتاريخ الروحي العميق.\r\n\r\n', 'uploads/highlights/highlight_6845a2d4a332b.jpg'),
(4, 'السيف الدمشقي', ' رمزٌ للفخامة والقوة، عُرف بصلابته ونقوشه المتموجة الفريدة، وجمع بين الحرفية العالية والجمال الأصيل.', 'uploads/highlights/highlight_6845a3530f416.jpg'),
(5, 'قلعة دمشق', 'حصن تاريخي قديم، كان نقطة دفاع استراتيجية عبر العصور.', 'uploads/highlights/highlight_6845a7017f0f7.jpg'),
(6, 'قصر العضم', ' قصر تاريخي فاخر في دمشق، يُعتبر مثالًا رائعًا على العمارة الدمشقية التقليدية بزخارفه الخشبية والفسيفساء، وكان مقرًا لعائلة العظم العريقة.', 'uploads/highlights/highlight_6845a79558734.jpg'),
(7, 'ساحة المرجة', 'ساحة تاريخية في دمشق، تشتهر بعمود التلغراف العثماني الذي بُني عام 1907، وكانت موقعًا لإعدامات السادس من أيار عام 1916، مما جعلها رمزًا للنضال الوطني', 'uploads/highlights/highlight_684afa7f93e6c.jpg'),
(8, 'التكية السليمانية', 'هي معلم عثماني تاريخي في دمشق، بنيت بين عامي 1554 و1559 بأمر من السلطان سليمان القانوني. تضم مسجدًا، مدرسة، وسوقًا للمهن اليدوية، وتقع على ضفاف نهر بردى، مما جعلها محطة مهمة للحجاج والتجار', 'uploads/highlights/highlight_684afc00b9ba2.jpg'),
(9, 'مقام صلاح الدين الأيوبي', 'يقع بجوار **الجامع الأموي** في دمشق، وهو ضريح القائد الذي حرر **القدس** عام 1187. يتميز بتصميمه البسيط ويعد رمزًا لتاريخ **المقاومة الإسلامية** ضد الحملات الصليبية.', 'uploads/highlights/highlight_684b20faeb6d7.jpg');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `image`, `category`, `created_at`) VALUES
(1, 'باب توما', 'يعد باب توما من أشهر أبواب مدينة دمشق القديمة، ويقع في الجهة الشمالية الشرقية منها. يتميز بطرازه المعماري البيزنطي.', 'uploads/posts/bab_touma.jpg', 'معالم', '2025-06-07 15:11:38'),
(2, 'مجدرة شامية', 'أكلة تقليدية مشهورة في الشام، مكونة من العدس والرز أو البرغل، وتُقدّم مع اللبن أو المخلل.', 'uploads/posts/mjadra.jpg', 'أكلات', '2025-06-07 15:11:38');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `quotes`
--

CREATE TABLE `quotes` (
  `id` int(11) NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `quotes`
--

INSERT INTO `quotes` (`id`, `text`) VALUES
(1, 'الشام جنة الله على الأرض.');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `social_links`
--

CREATE TABLE `social_links` (
  `id` int(11) NOT NULL,
  `platform` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `social_links`
--

INSERT INTO `social_links` (`id`, `platform`, `url`, `icon`) VALUES
(1, 'فيسبوك', 'https://www.facebook.com/7ara.ya.7ara', 'social/facebook-logo.png'),
(2, 'إنستغرام', 'https://www.instagram.com/7ara.ya.7ara/', 'social/instgram-logo.png');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `email`, `password`) VALUES
(1, 'mafia.1717@hotmail.com', '$2y$10$8B42gHQeLFWBoS7rfVOHouRP1Ps3x3R/ecQqn0mXmI0oJXt2nnmbe');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `welcome_section`
--

CREATE TABLE `welcome_section` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `welcome_section`
--

INSERT INTO `welcome_section` (`id`, `title`, `content`) VALUES
(1, 'مرحباً بكم في موقع \"انت من الحارة؟\" يسلملي ربك يا خال', 'هنا تجدون عراقة الماضي وروح الحاضر، حيث نأخذكم في رحلة لاستكشاف جمال الشام وسحرها. اكتشفوا معنا التراث، الشعر، الصور، والحكايات التي تعبر عن هوية هذه الأرض المباركة.');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `content_texts`
--
ALTER TABLE `content_texts`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `highlights`
--
ALTER TABLE `highlights`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `social_links`
--
ALTER TABLE `social_links`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indizes für die Tabelle `welcome_section`
--
ALTER TABLE `welcome_section`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `content_texts`
--
ALTER TABLE `content_texts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `highlights`
--
ALTER TABLE `highlights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT für Tabelle `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `quotes`
--
ALTER TABLE `quotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `social_links`
--
ALTER TABLE `social_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `welcome_section`
--
ALTER TABLE `welcome_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
