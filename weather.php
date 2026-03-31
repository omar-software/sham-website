<?php
$apiKey = '6700769d8925108d97efa3e1f91972ae'; // مفتاحك
$city = 'Damascus';
$url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&units=metric&lang=ar&appid={$apiKey}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // لو صار في مشكلة SSL ممكن تشيلها
$response = curl_exec($ch);

if(curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
    $response = false;
}

curl_close($ch);

if ($response) {
    $data = json_decode($response, true);
    if ($data && isset($data['main']['temp'])) {
        $weatherTemp = $data['main']['temp'];
        $weatherDesc = $data['weather'][0]['description'];
    } else {
        $weatherTemp = '--';
        $weatherDesc = 'غير متوفر';
    }
} else {
    $weatherTemp = '--';
    $weatherDesc = 'حدث خطأ في جلب البيانات';
}

$tempRounded = is_numeric($weatherTemp) ? round($weatherTemp) : '--';
?>

<div class="weather-widget">
    <p>حالة الطقس في دمشق: <?= htmlspecialchars($weatherDesc) ?></p>
    <p>درجة الحرارة: <?= $tempRounded ?>°C</p>
</div>
