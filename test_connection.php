<?php
// Veritabanı bağlantısını test et

$host = 'mysql.railway.internal';
$dbname = 'railway';
$username = 'root';
$password = 'qTVHsjDrZHeqDDctlKVVeGFXrTVbdatw';

echo "<h2>Veritabanı Bağlantı Testi</h2>";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✓ Veritabanı bağlantısı başarılı!</p>";
    
    // Tabloyu kontrol et
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✓ 'users' tablosu mevcut!</p>";
        
        // Tablo yapısını göster
        $stmt = $pdo->query("DESCRIBE users");
        echo "<h3>Tablo Yapısı:</h3>";
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Alan</th><th>Tip</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$row['Field']}</td>";
            echo "<td>{$row['Type']}</td>";
            echo "<td>{$row['Null']}</td>";
            echo "<td>{$row['Key']}</td>";
            echo "<td>{$row['Default']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Kayıt sayısını göster
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        echo "<p>Toplam kayıt sayısı: <strong>{$count}</strong></p>";
        
    } else {
        echo "<p style='color: red;'>✗ 'users' tablosu bulunamadı! Lütfen database.sql dosyasını çalıştırın.</p>";
    }
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>✗ Bağlantı hatası: " . $e->getMessage() . "</p>";
    echo "<p>Lütfen şunları kontrol edin:</p>";
    echo "<ul>";
    echo "<li>MySQL servisi çalışıyor mu?</li>";
    echo "<li>Veritabanı adı doğru mu? (hgs_system)</li>";
    echo "<li>Kullanıcı adı ve şifre doğru mu?</li>";
    echo "<li>database.sql dosyası çalıştırıldı mı?</li>";
    echo "</ul>";
}
?>
