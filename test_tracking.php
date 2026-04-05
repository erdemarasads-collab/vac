<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        h1 { color: #333; }
        .status { 
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        button {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
        }
        button:hover { background: #5568d3; }
    </style>
</head>
<body>
    <div class="card">
        <h1>🔍 Tracking Test Sayfası</h1>
        <p>Bu sayfa tracking sistemini test etmek için oluşturuldu.</p>
        
        <div id="status" class="status"></div>
        
        <h3>Test Butonları:</h3>
        <button onclick="testTracking()">Tracking Test Et</button>
        <button onclick="checkOnline()">Online Kullanıcıları Kontrol Et</button>
        <button onclick="window.location.href='/admin/?page=online'">Admin Panele Git</button>
    </div>
    
    <div class="card">
        <h3>Session Bilgileri:</h3>
        <p><strong>Session ID:</strong> <?= session_id() ?></p>
        <p><strong>IP Address:</strong> <?= $_SERVER['REMOTE_ADDR'] ?? 'unknown' ?></p>
        <p><strong>Current Page:</strong> <?= $_SERVER['REQUEST_URI'] ?></p>
    </div>
    
    <div class="card" id="onlineUsers">
        <h3>Online Kullanıcılar:</h3>
        <p>Yükleniyor...</p>
    </div>
    
    <script src="/tracking.js"></script>
    <script>
        async function testTracking() {
            const status = document.getElementById('status');
            status.className = 'status';
            status.textContent = 'Test ediliyor...';
            
            try {
                const response = await fetch('/track_online.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'page=/test_tracking.php'
                });
                
                if (response.ok) {
                    status.className = 'status success';
                    status.textContent = '✓ Tracking başarılı! Veriler kaydedildi.';
                    checkOnline();
                } else {
                    status.className = 'status error';
                    status.textContent = '✗ Hata: ' + response.status;
                }
            } catch(error) {
                status.className = 'status error';
                status.textContent = '✗ Hata: ' + error.message;
            }
        }
        
        async function checkOnline() {
            try {
                const response = await fetch('/api.php?action=get_online_users');
                const data = await response.json();
                
                const container = document.getElementById('onlineUsers');
                
                if (data.success && data.users.length > 0) {
                    let html = '<h3>Online Kullanıcılar (' + data.users.length + '):</h3>';
                    html += '<table style="width: 100%; border-collapse: collapse;">';
                    html += '<tr style="background: #f5f5f5;"><th style="padding: 8px; text-align: left;">IP</th><th style="padding: 8px; text-align: left;">Sayfa</th><th style="padding: 8px; text-align: left;">Son Aktivite</th></tr>';
                    
                    data.users.forEach(user => {
                        html += `<tr style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 8px;">${user.ip_address}</td>
                            <td style="padding: 8px;">${user.current_page}</td>
                            <td style="padding: 8px;">${user.last_activity}</td>
                        </tr>`;
                    });
                    
                    html += '</table>';
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<h3>Online Kullanıcılar:</h3><p>Aktif kullanıcı yok</p>';
                }
            } catch(error) {
                document.getElementById('onlineUsers').innerHTML = '<h3>Hata:</h3><p>' + error.message + '</p>';
            }
        }
        
        // Otomatik kontrol
        setInterval(checkOnline, 3000);
        checkOnline();
    </script>
</body>
</html>
