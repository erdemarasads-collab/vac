<?php
require_once 'security.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Güvenlik Testi - HGS</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
            min-height: 100vh;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        h1 {
            color: white;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2.5rem;
        }
        .test-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
        }
        .test-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .test-card h2 {
            color: #667eea;
            margin-bottom: 1rem;
            font-size: 1.3rem;
            border-bottom: 2px solid #667eea;
            padding-bottom: 0.5rem;
        }
        .test-item {
            margin: 1rem 0;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #ddd;
        }
        .test-item.success {
            border-left-color: #2ecc71;
            background: #d4edda;
        }
        .test-item.error {
            border-left-color: #e74c3c;
            background: #f8d7da;
        }
        .test-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.25rem;
        }
        .test-input {
            color: #718096;
            font-size: 0.9rem;
            font-family: 'Courier New', monospace;
        }
        .test-result {
            color: #2c3e50;
            font-weight: 600;
            margin-top: 0.25rem;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }
        .badge.success { background: #2ecc71; color: white; }
        .badge.error { background: #e74c3c; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔒 Güvenlik Testi</h1>
        
        <div class="test-grid">
            <!-- XSS Testi -->
            <div class="test-card">
                <h2>XSS Koruması</h2>
                <?php
                $xss_tests = [
                    '<script>alert("XSS")</script>',
                    'javascript:alert(1)',
                    '<img src=x onerror=alert(1)>',
                    '<svg onload=alert(1)>',
                    '"><script>alert(String.fromCharCode(88,83,83))</script>'
                ];
                
                foreach ($xss_tests as $test) {
                    $cleaned = Security::xss_clean($test);
                    $safe = ($cleaned !== $test && strpos($cleaned, '<script') === false);
                    ?>
                    <div class="test-item <?= $safe ? 'success' : 'error' ?>">
                        <div class="test-label">Input:</div>
                        <div class="test-input"><?= htmlspecialchars($test) ?></div>
                        <div class="test-result">
                            Output: <?= htmlspecialchars($cleaned) ?>
                            <span class="badge <?= $safe ? 'success' : 'error' ?>">
                                <?= $safe ? '✓ GÜVENLİ' : '✗ TEHLİKELİ' ?>
                            </span>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <!-- Kart Numarası Testi -->
            <div class="test-card">
                <h2>Kart Numarası Validasyonu</h2>
                <?php
                $card_tests = [
                    ['4532015112830366', true, 'Geçerli Visa'],
                    ['5425233430109903', true, 'Geçerli Mastercard'],
                    ['1234567890123456', false, 'Luhn hatası'],
                    ['4532 0151 1283 0366', true, 'Boşluklu format'],
                    ['123', false, 'Çok kısa'],
                    ['abcd1234efgh5678', false, 'Harf içeriyor']
                ];
                
                foreach ($card_tests as $test) {
                    $result = Security::validate_card_number($test[0]);
                    $success = ($result !== false) === $test[1];
                    ?>
                    <div class="test-item <?= $success ? 'success' : 'error' ?>">
                        <div class="test-label"><?= $test[2] ?>:</div>
                        <div class="test-input"><?= htmlspecialchars($test[0]) ?></div>
                        <div class="test-result">
                            Sonuç: <?= $result !== false ? htmlspecialchars($result) : 'GEÇERSİZ' ?>
                            <span class="badge <?= $success ? 'success' : 'error' ?>">
                                <?= $success ? '✓ DOĞRU' : '✗ YANLIŞ' ?>
                            </span>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <!-- Kart SKT Testi -->
            <div class="test-card">
                <h2>Kart SKT Validasyonu</h2>
                <?php
                $expiry_tests = [
                    ['12/25', true, 'Geçerli tarih'],
                    ['01/30', true, 'Gelecek tarih'],
                    ['13/25', false, 'Geçersiz ay'],
                    ['00/25', false, 'Sıfır ay'],
                    ['12/20', false, 'Geçmiş tarih'],
                    ['1225', false, 'Yanlış format']
                ];
                
                foreach ($expiry_tests as $test) {
                    $result = Security::validate_card_expiry($test[0]);
                    $success = ($result !== false) === $test[1];
                    ?>
                    <div class="test-item <?= $success ? 'success' : 'error' ?>">
                        <div class="test-label"><?= $test[2] ?>:</div>
                        <div class="test-input"><?= htmlspecialchars($test[0]) ?></div>
                        <div class="test-result">
                            Sonuç: <?= $result !== false ? htmlspecialchars($result) : 'GEÇERSİZ' ?>
                            <span class="badge <?= $success ? 'success' : 'error' ?>">
                                <?= $success ? '✓ DOĞRU' : '✗ YANLIŞ' ?>
                            </span>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <!-- CVV Testi -->
            <div class="test-card">
                <h2>CVV Validasyonu</h2>
                <?php
                $cvv_tests = [
                    ['123', true, 'Geçerli 3 haneli'],
                    ['1234', true, 'Geçerli 4 haneli (Amex)'],
                    ['12', false, 'Çok kısa'],
                    ['12345', false, 'Çok uzun'],
                    ['abc', false, 'Harf içeriyor']
                ];
                
                foreach ($cvv_tests as $test) {
                    $result = Security::validate_cvv($test[0]);
                    $success = ($result !== false) === $test[1];
                    ?>
                    <div class="test-item <?= $success ? 'success' : 'error' ?>">
                        <div class="test-label"><?= $test[2] ?>:</div>
                        <div class="test-input"><?= htmlspecialchars($test[0]) ?></div>
                        <div class="test-result">
                            Sonuç: <?= $result !== false ? htmlspecialchars($result) : 'GEÇERSİZ' ?>
                            <span class="badge <?= $success ? 'success' : 'error' ?>">
                                <?= $success ? '✓ DOĞRU' : '✗ YANLIŞ' ?>
                            </span>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <!-- TC Kimlik Testi -->
            <div class="test-card">
                <h2>TC Kimlik Validasyonu</h2>
                <?php
                $tc_tests = [
                    ['12345678901', false, 'Geçersiz algoritma'],
                    ['10000000146', true, 'Geçerli TC'],
                    ['01234567890', false, 'İlk rakam 0'],
                    ['123456789', false, 'Çok kısa'],
                    ['1234567890123', false, 'Çok uzun']
                ];
                
                foreach ($tc_tests as $test) {
                    $result = Security::validate_tc($test[0]);
                    $success = ($result !== false) === $test[1];
                    ?>
                    <div class="test-item <?= $success ? 'success' : 'error' ?>">
                        <div class="test-label"><?= $test[2] ?>:</div>
                        <div class="test-input"><?= htmlspecialchars($test[0]) ?></div>
                        <div class="test-result">
                            Sonuç: <?= $result !== false ? 'GEÇERLİ' : 'GEÇERSİZ' ?>
                            <span class="badge <?= $success ? 'success' : 'error' ?>">
                                <?= $success ? '✓ DOĞRU' : '✗ YANLIŞ' ?>
                            </span>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <!-- Telefon Testi -->
            <div class="test-card">
                <h2>Telefon Validasyonu</h2>
                <?php
                $phone_tests = [
                    ['5551234567', true, 'Geçerli 10 haneli'],
                    ['05551234567', true, 'Geçerli 11 haneli'],
                    ['555 123 45 67', true, 'Boşluklu format'],
                    ['123', false, 'Çok kısa'],
                    ['abc1234567', false, 'Harf içeriyor']
                ];
                
                foreach ($phone_tests as $test) {
                    $result = Security::validate_phone($test[0]);
                    $success = ($result !== false) === $test[1];
                    ?>
                    <div class="test-item <?= $success ? 'success' : 'error' ?>">
                        <div class="test-label"><?= $test[2] ?>:</div>
                        <div class="test-input"><?= htmlspecialchars($test[0]) ?></div>
                        <div class="test-result">
                            Sonuç: <?= $result !== false ? htmlspecialchars($result) : 'GEÇERSİZ' ?>
                            <span class="badge <?= $success ? 'success' : 'error' ?>">
                                <?= $success ? '✓ DOĞRU' : '✗ YANLIŞ' ?>
                            </span>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <!-- İsim Testi -->
            <div class="test-card">
                <h2>İsim Validasyonu</h2>
                <?php
                $name_tests = [
                    ['Ahmet Yılmaz', true, 'Geçerli isim'],
                    ['Ayşe Öztürk', true, 'Türkçe karakter'],
                    ['Mehmet123', false, 'Rakam içeriyor'],
                    ['A', false, 'Çok kısa'],
                    ['John@Doe', false, 'Özel karakter']
                ];
                
                foreach ($name_tests as $test) {
                    $result = Security::validate_name($test[0]);
                    $success = ($result !== false) === $test[1];
                    ?>
                    <div class="test-item <?= $success ? 'success' : 'error' ?>">
                        <div class="test-label"><?= $test[2] ?>:</div>
                        <div class="test-input"><?= htmlspecialchars($test[0]) ?></div>
                        <div class="test-result">
                            Sonuç: <?= $result !== false ? htmlspecialchars($result) : 'GEÇERSİZ' ?>
                            <span class="badge <?= $success ? 'success' : 'error' ?>">
                                <?= $success ? '✓ DOĞRU' : '✗ YANLIŞ' ?>
                            </span>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <!-- Tutar Testi -->
            <div class="test-card">
                <h2>Tutar Validasyonu</h2>
                <?php
                $amount_tests = [
                    ['100.50', true, 'Geçerli tutar'],
                    ['1000', true, 'Tam sayı'],
                    ['-50', false, 'Negatif'],
                    ['0', false, 'Sıfır'],
                    ['9999999', false, 'Çok büyük'],
                    ['abc', false, 'Harf içeriyor']
                ];
                
                foreach ($amount_tests as $test) {
                    $result = Security::validate_amount($test[0]);
                    $success = ($result !== false) === $test[1];
                    ?>
                    <div class="test-item <?= $success ? 'success' : 'error' ?>">
                        <div class="test-label"><?= $test[2] ?>:</div>
                        <div class="test-input"><?= htmlspecialchars($test[0]) ?></div>
                        <div class="test-result">
                            Sonuç: <?= $result !== false ? number_format($result, 2) . ' TL' : 'GEÇERSİZ' ?>
                            <span class="badge <?= $success ? 'success' : 'error' ?>">
                                <?= $success ? '✓ DOĞRU' : '✗ YANLIŞ' ?>
                            </span>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
