<?php
header('Content-Type: application/json');

echo "<h2>Railway DB Bağlantı Testi</h2>";

$host = $_ENV['MYSQLHOST'] ?? null;
$port = $_ENV['MYSQLPORT'] ?? null;
$db   = $_ENV['MYSQLDATABASE'] ?? null;
$user = $_ENV['MYSQLUSER'] ?? null;
$pass = $_ENV['MYSQLPASSWORD'] ?? null;

echo "MYSQLHOST: " . ($host ?: '❌ BOŞ') . "<br>";
echo "MYSQLPORT: " . ($port ?: '❌ BOŞ') . "<br>";
echo "MYSQLDATABASE: " . ($db ?: '❌ BOŞ') . "<br>";
echo "MYSQLUSER: " . ($user ?: '❌ BOŞ') . "<br>";
echo "MYSQLPASSWORD: " . ($pass ? 'VAR (gizli)' : '❌ BOŞ') . "<br><hr>";

if (!$host || !$port || !$pass) {
    die(json_encode([
        "success" => false,
        "message" => "Environment variables eksik! Railway Variables sekmesini kontrol et."
    ]));
}

$conn = new mysqli($host, $user, $pass, $db, (int)$port);

if ($conn->connect_error) {
    die(json_encode([
        "success" => false,
        "message" => "Bağlantı HATASI: " . $conn->connect_error,
        "host" => $host,
        "port" => $port
    ]));
} else {
    echo json_encode([
        "success" => true,
        "message" => "✅ Bağlantı BAŞARILI! Tablolar aşağıda:",
        "tables" => []
    ]);
    
    $result = $conn->query("SHOW TABLES");
    while ($row = $result->fetch_array()) {
        echo "- " . $row[0] . "<br>";
    }
}

$conn->close();
?>