<?php
/*
    // ==== بيانات الأخبار ====
    $newsApiKey = 'e62ed9bc8faa06ea84cfefef9b4e35f3'; // مفتاح من mediastack
    $newsUrl = "http://api.mediastack.com/v1/news?access_key={$newsApiKey}&countries=sy&languages=ar";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $newsUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $newsJson = curl_exec($ch);
    curl_close($ch);

    $newsData = json_decode($newsJson, true);

    if ($newsData && isset($newsData['data'][0])) {
        $newsTitle = $newsData['data'][0]['title'];
        $newsDesc  = $newsData['data'][0]['description'];
        $newsUrl   = $newsData['data'][0]['url'];
    } else {
        $newsTitle = 'لا توجد أخبار حالياً';
        $newsDesc  = '';
        $newsUrl   = '#';
    }
*/
?>
