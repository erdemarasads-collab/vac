<?php
/**
 * Railway uyumlu veritabanı bağlantı dosyası
 */

function getDbConnection() {
    try {

        // Önce MYSQL_URL dene (Railway standardı)
        $url = getenv('MYSQL_URL') ?: $_SERVER['MYSQL_URL'] ?? null;

        if ($url) {
            $parts = parse_url($url);

            $host = $parts['host'] ?? null;
            $port = $parts['port'] ?? 3306;
            $user = $parts['user'] ?? null;
            $pass = $parts['pass'] ?? null;
            $db   = isset($parts['path']) ? ltrim($parts['path'], '/') : null;

        } else {
            // fallback (tek tek env)
            $host = getenv('MYSQLHOST') ?: $_SERVER['MYSQLHOST'] ?? null;
            $port = getenv('MYSQLPORT') ?: $_SERVER['MYSQLPORT'] ?? 3306;
            $user = getenv('MYSQLUSER') ?: $_SERVER['MYSQLUSER'] ?? null;
            $pass = getenv('MYSQLPASSWORD') ?: $_SERVER['MYSQLPASSWORD'] ?? null;
            $db   = getenv('MYSQLDATABASE') ?: $_SERVER['MYSQLDATABASE'] ?? null;
        }

        // Hala yoksa hata ver
        if (!$host || !$user || !$db) {
            throw new Exception("Database environment variables bulunamadı");
        }

        // PDO bağlantısı
        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

        $pdo = new PDO($dsn, $user, $pass);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;

    } catch (Exception $e) {
        error_log("DB ERROR: " . $e->getMessage());
        die("❌ Veritabanı bağlantı hatası");
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
