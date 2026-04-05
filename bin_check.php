<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) && !isset($_SESSION['userIdentifier'])) {
    http_response_code(403);
    echo json_encode(['error' => true]);
    exit;
}

require_once 'config.php';

$bin = preg_replace('/\D/', '', $_GET['bin'] ?? '');
if (strlen($bin) < 6) {
    echo json_encode(['error' => true]);
    exit;
}

header('Content-Type: application/json');

$pdo = getDbConnection();
$pdo->exec("SET NAMES utf8mb4");

// Bozuk karakterleri düzelten yardımcı fonksiyon
function fixEncoding($str) {
    if (!$str) return $str;
    // Latin-1 olarak yanlış decode edilmişse düzelt
    if (!mb_check_encoding($str, 'UTF-8')) {
        $str = mb_convert_encoding($str, 'UTF-8', 'ISO-8859-9');
    }
    // Hala bozuksa (örn. Ş → ¿ gibi) windows-1254 dene
    if (preg_match('/[\x{00C0}-\x{00FF}]/u', $str)) {
        $try = mb_convert_encoding($str, 'UTF-8', 'Windows-1254');
        if (mb_check_encoding($try, 'UTF-8')) $str = $try;
    }
    return $str;
}

// 1) Önce yerel bins tablosuna bak (8→6 hane)
$result = null;
for ($len = min(8, strlen($bin)); $len >= 6; $len--) {
    $try = substr($bin, 0, $len);
    $stmt = $pdo->prepare("SELECT * FROM bins WHERE bin = ? LIMIT 1");
    $stmt->execute([$try]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $result = [
            'bank'    => fixEncoding($row['banka_adi'] ?? ''),
            'type'    => fixEncoding($row['type']      ?? ''),
            'brand'   => fixEncoding($row['brand']     ?? ''),
            'country' => 'Türkiye',
        ];
        break;
    }
}

// 2) Yerel'de yoksa handyapi.com'dan çek (ücretsiz, limitsiz)
if (!$result) {
    $bin6    = substr($bin, 0, 6);
    $apiUrl  = 'https://data.handyapi.com/bin/' . $bin6;
    $raw     = null;

    if (function_exists('curl_init')) {
        $ch = curl_init($apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 6,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER     => ['User-Agent: Mozilla/5.0'],
        ]);
        $raw      = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode !== 200) $raw = null;
    }

    if (!$raw) {
        $ctx = stream_context_create([
            'http' => ['timeout' => 6, 'ignore_errors' => true,
                       'header'  => "User-Agent: Mozilla/5.0\r\n"],
            'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false],
        ]);
        $raw = @file_get_contents($apiUrl, false, $ctx);
    }

    if ($raw) {
        // Encoding düzelt
        if (!mb_check_encoding($raw, 'UTF-8')) {
            $raw = mb_convert_encoding($raw, 'UTF-8', 'ISO-8859-9');
        }
        $api = json_decode($raw, true);
        if ($api && ($api['Status'] ?? '') === 'SUCCESS') {
            $bank    = $api['Issuer']    ?? '';
            $brand   = ucfirst(strtolower($api['Scheme'] ?? ''));
            $type    = ucfirst(strtolower($api['Type']   ?? ''));
            $country = $api['Country']['Name'] ?? '';

            // Bozuk karakterleri temizle
            $bank = preg_replace('/[^\x{0020}-\x{024F}\x{0400}-\x{04FF} ]/u', '', $bank);
            $bank = trim($bank);

            $result = [
                'bank'    => $bank,
                'type'    => $type,
                'brand'   => $brand,
                'country' => $country,
            ];

            // Yerel tabloya cache'le — bir daha API'ye gitmesin
            try {
                $ins = $pdo->prepare(
                    "INSERT IGNORE INTO bins (bin, banka_adi, type, brand, sub_type) VALUES (?,?,?,?,?)"
                );
                $ins->execute([$bin6, $bank, $type, $brand, $api['CardTier'] ?? '']);
            } catch (Exception $e) {}
        }
    }
}

if (!$result) {
    echo json_encode(['error' => true, 'message' => 'BIN bulunamadı']);
    exit;
}

echo json_encode($result);
