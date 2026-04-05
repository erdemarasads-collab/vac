<?php

$url = getenv('DATABASE_URL');

if (!$url) {
    die("❌ DATABASE_URL yok!");
}

$parts = parse_url($url);

$host = $parts['host'];
$port = $parts['port'];
$user = $parts['user'];
$pass = $parts['pass'];
$db   = ltrim($parts['path'], '/');

echo "<pre>";
echo "HOST: $host\n";
echo "PORT: $port\n";
echo "DB: $db\n";
echo "USER: $user\n";
echo "PASS: " . ($pass ? 'VAR' : 'YOK') . "\n";

// bağlantı
$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("❌ Bağlantı hatası: " . $conn->connect_error);
}

echo "\n✅ BAĞLANTI BAŞARILI!\n";

$result = $conn->query("SHOW TABLES");

while ($row = $result->fetch_array()) {
    echo "- " . $row[0] . "\n";
}

$conn->close();