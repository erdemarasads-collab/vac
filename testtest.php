<?php
header('Content-Type: text/html; charset=utf-8');

echo "<h2>Railway DB Bağlantı Testi</h2>";

// ✅ ENV'leri doğru şekilde al
$host = getenv('MYSQLHOST');
$port = getenv('MYSQLPORT');
$db   = getenv('MYSQLDATABASE');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');

// Debug çıktısı
echo "MYSQLHOST: " . ($host ?: '❌ BOŞ') . "<br>";
echo "MYSQLPORT: " . ($port ?: '❌ BOŞ') . "<br>";
echo "MYSQLDATABASE: " . ($db ?: '❌ BOŞ') . "<br>";
echo "MYSQLUSER: " . ($user ?: '❌ BOŞ') . "<br>";
echo "MYSQLPASSWORD: " . ($pass ? 'VAR (gizli)' : '❌ BOŞ') . "<br><hr>";

// Eksik kontrolü
if (!$host || !$port || !$user || !$pass || !$db) {
    die("<b>❌ Environment variables eksik!</b>");
}

// Bağlantı dene
$conn = new mysqli($host, $user, $pass, $db, (int)$port);

// Hata varsa
if ($conn->connect_error) {
    die("<b>❌ Bağlantı HATASI:</b> " . $conn->connect_error);
}

// Başarılıysa
echo "<b>✅ Bağlantı BAŞARILI!</b><br><br>";
echo "<b>Tablolar:</b><br>";

$result = $conn->query("SHOW TABLES");

if ($result) {
    while ($row = $result->fetch_array()) {
        echo "- " . $row[0] . "<br>";
    }
} else {
    echo "Tablo bulunamadı veya sorgu hatası.";
}

$conn->close();
?>