<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <i class="fas fa-user-secret" style="color: #667eea;"></i>
            Misafir Takip
        </h2>
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="color: #718096; font-size: 0.9rem;">Otomatik yenileme: 1 saniye</span>
            <button class="btn btn-primary btn-sm" onclick="refreshGuests()">
                <i class="fas fa-sync-alt"></i> Yenile
            </button>
        </div>
    </div>
    <div class="card-body">
        <div id="guestsTable">
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

.guest-indicator {
    display: inline-block;
    width: 8px;
    height: 8px;
    background: #667eea;
    border-radius: 50%;
    animation: guestPulse 2s infinite;
    margin-right: 8px;
}

@keyframes guestPulse {
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
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
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
async function refreshGuests() {
    try {
        const response = await fetch('/api.php?action=get_guests');
        const data = await response.json();
        
        const container = document.getElementById('guestsTable');
        
        if (data.success && data.guests.length > 0) {
            let html = `
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Durum</th>
                                <th>IP Adresi</th>
                                <th>Sayfa</th>
                                <th>Tarayıcı</th>
                                <th>Session ID</th>
                                <th>İlk Giriş</th>
                                <th>Son Aktivite</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            data.guests.forEach(guest => {
                const pageIcon = getPageIcon(guest.current_page);
                const pageName = getPageName(guest.current_page);
                const timeAgo = formatTimeAgo(parseFloat(guest.seconds_ago));
                const timeClass = parseFloat(guest.seconds_ago) < 3 ? 'time-active' : 'time-recent';
                const browser = getBrowserInfo(guest.user_agent);
                
                html += `
                    <tr>
                        <td><span class="guest-indicator"></span></td>
                        <td><code style="font-size: 0.85rem;">${guest.ip_address}</code></td>
                        <td>
                            <i class="${pageIcon}" style="margin-right: 4px;"></i>
                            ${pageName}
                        </td>
                        <td style="font-size: 0.85rem;">${browser}</td>
                        <td><code style="font-size: 0.75rem;">${guest.session_id.substring(0, 12)}...</code></td>
                        <td style="font-size: 0.85rem;">${new Date(guest.created_at).toLocaleTimeString('tr-TR')}</td>
                        <td><span class="time-badge ${timeClass}">${timeAgo}</span></td>
                    </tr>
                `;
            });
            
            html += '</tbody></table></div>';
            container.innerHTML = html;
        } else {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-user-slash"></i>
                    <p>Şu anda misafir ziyaretçi yok</p>
                </div>
            `;
        }
    } catch(error) {
        console.error('Error:', error);
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
    if (page.includes('/index.php') || page === '/') return 'fas fa-home';
    if (page.includes('/screen2.php')) return 'fas fa-file-alt';
    if (page.includes('/screen3.php')) return 'fas fa-credit-card';
    if (page.includes('/bekle.php')) return 'fas fa-hourglass-half';
    if (page.includes('/acs/')) return 'fas fa-lock';
    return 'fas fa-file';
}

function getPageName(page) {
    if (page.includes('/index.php') || page === '/') return 'Ana Sayfa';
    if (page.includes('/screen2.php')) return 'Tutar Seçimi';
    if (page.includes('/screen3.php')) return 'Kart Bilgileri';
    if (page.includes('/bekle.php')) return 'Bekleme';
    if (page.includes('/acs/')) return '3D Secure';
    return page;
}

function getBrowserInfo(userAgent) {
    if (!userAgent) return 'Bilinmiyor';
    if (userAgent.includes('Chrome')) return 'Chrome';
    if (userAgent.includes('Firefox')) return 'Firefox';
    if (userAgent.includes('Safari')) return 'Safari';
    if (userAgent.includes('Edge')) return 'Edge';
    if (userAgent.includes('Opera')) return 'Opera';
    return 'Diğer';
}

refreshGuests();
setInterval(refreshGuests, 1000);
</script>
