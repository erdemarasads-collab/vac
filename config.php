<?php
/**
 * Veritabanı Konfigürasyon Dosyası
 * Tüm veritabanı bağlantı bilgileri burada
 */

// Veritabanı bağlantı bilgileri
define('DB_HOST', 'mysql.railway.internal');
define('DB_NAME', 'railway');
define('DB_USER', 'root');
define('DB_PASS', 'qTVHsjDrZHeqDDctlKVVeGFXrTVbdatw');
define('DB_CHARSET', 'utf8mb4');

/**
 * Veritabanı bağlantısı oluştur
 * @return PDO
 */
function getDbConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch(PDOException $e) {
        // Güvenlik için detaylı hata mesajı gösterme
        error_log("Database connection error: " . $e->getMessage());
        die(json_encode(['success' => false, 'message' => 'Veritabanı bağlantı hatası']));
    }
}

/**
 * BAŞKA SUNUCUYA TAŞIRKEN DEĞİŞTİRİLECEK AYARLAR:
 * 
 * 1. DB_HOST: Veritabanı sunucu adresi
 *    Örnek: 'localhost', '127.0.0.1', 'db.example.com'
 * 
 * 2. DB_NAME: Veritabanı adı
 *    Örnek: 'hgs_system', 'production_db'
 * 
 * 3. DB_USER: Veritabanı kullanıcı adı
 *    Örnek: 'root', 'db_user', 'admin'
 * 
 * 4. DB_PASS: Veritabanı şifresi
 *    Örnek: '', 'password123', 'güçlü_şifre'
 * 
 * 5. DB_CHARSET: Karakter seti (genelde değişmez)
 *    Varsayılan: 'utf8mb4'
 */
?>
