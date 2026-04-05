# Güvenlik Dokümantasyonu

## 🔒 Uygulanan Güvenlik Önlemleri

### 1. XSS (Cross-Site Scripting) Koruması

#### Nedir?
XSS, saldırganların web sayfalarına kötü amaçlı JavaScript kodu enjekte etmesine izin veren bir güvenlik açığıdır.

#### Koruma Yöntemleri
- **htmlspecialchars()**: Tüm kullanıcı girdileri HTML karakterlerine dönüştürülür
- **ENT_QUOTES**: Tek ve çift tırnaklar da encode edilir
- **UTF-8**: Karakter seti belirtilir
- **Tehlikeli Tag Temizleme**: `<script>`, `javascript:`, `onerror=` gibi tehlikeli içerikler kaldırılır

```php
Security::xss_clean($data);
```

---

### 2. SQL Injection Koruması

#### Nedir?
SQL Injection, saldırganların veritabanı sorgularına kötü amaçlı SQL kodu enjekte etmesine izin veren bir güvenlik açığıdır.

#### Koruma Yöntemleri
- **PDO Prepared Statements**: Tüm veritabanı sorguları prepared statement kullanır
- **Parametre Binding**: Değerler sorguya bind edilir, direkt eklenmez
- **SQL Keyword Kontrolü**: Tehlikeli SQL komutları kontrol edilir

```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
```

---

### 3. Input Validasyonu

#### Kart Numarası
- **Sadece Rakam**: Harf ve özel karakterler kabul edilmez
- **Uzunluk**: 13-19 karakter arası
- **Luhn Algoritması**: Kart numarası matematiksel olarak doğrulanır

```php
Security::validate_card_number($cardNumber);
```

#### Kart SKT (Son Kullanma Tarihi)
- **Format**: MM/YY
- **Ay Kontrolü**: 01-12 arası
- **Geçmiş Tarih**: Geçmiş tarihler kabul edilmez

```php
Security::validate_card_expiry($expiry);
```

#### CVV
- **Sadece Rakam**: 3-4 karakter
- **Uzunluk Kontrolü**: Minimum 3, maksimum 4

```php
Security::validate_cvv($cvv);
```

#### TC Kimlik No
- **11 Rakam**: Tam 11 karakter
- **İlk Rakam**: 0 olamaz
- **Algoritma**: TC Kimlik doğrulama algoritması uygulanır

```php
Security::validate_tc($tc);
```

#### Telefon
- **Sadece Rakam**: 10-11 karakter
- **Format**: Türkiye telefon numarası formatı

```php
Security::validate_phone($phone);
```

#### İsim
- **Sadece Harf**: Rakam ve özel karakterler kabul edilmez
- **Türkçe Karakter**: ğ, ü, ş, ı, ö, ç desteklenir
- **Uzunluk**: 2-100 karakter

```php
Security::validate_name($name);
```

#### Tutar
- **Pozitif Sayı**: Negatif değerler kabul edilmez
- **Maksimum**: 999,999.99 TL
- **Format**: Ondalıklı sayı

```php
Security::validate_amount($amount);
```

---

### 4. Rate Limiting (İstek Sınırlama)

#### Nedir?
Aynı IP adresinden çok fazla istek gelmesini engelleyen mekanizma.

#### Limitler
- **application-moderate.php**: 100 istek / dakika
- **api.php**: 120 istek / dakika
- **track_online.php**: 200 istek / dakika

#### Çalışma Prensibi
```php
if (!Security::rate_limit($ip, 100, 60)) {
    http_response_code(429); // Too Many Requests
    die(json_encode(['success' => false, 'message' => 'Çok fazla istek']));
}
```

---

### 5. Session Güvenliği

#### Session Hijacking Koruması
- **User Agent Kontrolü**: Her istekte user agent kontrol edilir
- **Session Regeneration**: Her 1 saatte bir session ID yenilenir

#### Session Fixation Koruması
- **Created Time**: Session oluşturulma zamanı takip edilir
- **Auto Regenerate**: Belirli süre sonra otomatik yenileme

```php
Security::secure_session();
```

---

### 6. CSRF (Cross-Site Request Forgery) Koruması

#### Token Oluşturma
```php
$token = Security::generate_csrf_token();
```

#### Token Doğrulama
```php
if (!Security::verify_csrf_token($token)) {
    die('CSRF token geçersiz');
}
```

---

### 7. IP Adresi Güvenliği

#### Gerçek IP Tespiti
- **Proxy Desteği**: X-Forwarded-For header'ı kontrol edilir
- **Validasyon**: IP adresi formatı doğrulanır

```php
$ip = Security::get_ip();
```

---

### 8. Güvenli Rastgele String

#### Kullanım Alanları
- Session token
- CSRF token
- API key
- Password reset token

```php
$randomString = Security::generate_random_string(32);
```

---

## 🛡️ Güvenlik Katmanları

### Katman 1: Client-Side (JavaScript)
- Input maskeleme
- Format kontrolü
- Luhn algoritması
- Gerçek zamanlı validasyon

### Katman 2: Server-Side (PHP)
- XSS temizleme
- Input validasyonu
- SQL injection koruması
- Rate limiting

### Katman 3: Database (MySQL)
- Prepared statements
- Parametre binding
- Charset: utf8mb4
- Collation: utf8mb4_unicode_ci

---

## 📋 Güvenlik Kontrol Listesi

### ✅ Tamamlanan
- [x] XSS koruması tüm input'larda
- [x] SQL injection koruması (PDO prepared statements)
- [x] Kart numarası validasyonu (Luhn algoritması)
- [x] Kart SKT validasyonu
- [x] CVV validasyonu
- [x] TC Kimlik validasyonu
- [x] Telefon validasyonu
- [x] İsim validasyonu
- [x] Tutar validasyonu
- [x] Rate limiting (IP bazlı)
- [x] Session güvenliği
- [x] CSRF token sistemi
- [x] Güvenli IP tespiti
- [x] Güvenli rastgele string

### 🔄 Önerilen İyileştirmeler
- [ ] HTTPS zorunluluğu
- [ ] Content Security Policy (CSP) header'ları
- [ ] HTTP Security Headers (X-Frame-Options, X-XSS-Protection, vb.)
- [ ] Password hashing (admin paneli için)
- [ ] 2FA (Two-Factor Authentication)
- [ ] Captcha (bot koruması)
- [ ] Audit logging (güvenlik logları)
- [ ] Encrypted database fields (kart bilgileri için)

---

## 🔍 Test Senaryoları

### XSS Testi
```javascript
// Deneme: <script>alert('XSS')</script>
// Sonuç: &lt;script&gt;alert('XSS')&lt;/script&gt;
```

### SQL Injection Testi
```sql
-- Deneme: ' OR '1'='1
-- Sonuç: Prepared statement ile engellenir
```

### Rate Limiting Testi
```bash
# 100+ istek gönder
for i in {1..150}; do curl http://localhost/api.php; done
# Sonuç: 429 Too Many Requests
```

---

## 📚 Kullanım Örnekleri

### Kart Bilgisi Kaydetme
```php
require_once 'security.php';

// Validasyon
$cardNumber = Security::validate_card_number($_POST['card_number']);
if (!$cardNumber) {
    die('Geçersiz kart numarası');
}

$cardExpiry = Security::validate_card_expiry($_POST['card_expiry']);
if (!$cardExpiry) {
    die('Geçersiz son kullanma tarihi');
}

$cvv = Security::validate_cvv($_POST['cvv']);
if (!$cvv) {
    die('Geçersiz CVV');
}

// Veritabanına kaydet (prepared statement ile)
$stmt = $pdo->prepare("INSERT INTO cards (number, expiry, cvv) VALUES (?, ?, ?)");
$stmt->execute([$cardNumber, $cardExpiry, $cvv]);
```

### API İsteği
```php
require_once 'security.php';

// Rate limiting
$ip = Security::get_ip();
if (!Security::rate_limit($ip, 100, 60)) {
    http_response_code(429);
    die('Çok fazla istek');
}

// XSS temizleme
$action = Security::xss_clean($_POST['action']);

// Validasyon
$userId = Security::validate_numeric($_POST['user_id'], 1, 11);
if (!$userId) {
    die('Geçersiz kullanıcı ID');
}
```

---

## 🚨 Güvenlik İhlali Durumunda

### Adımlar
1. **Sistemi Kapat**: Hemen offline al
2. **Logları İncele**: Saldırı kaynağını tespit et
3. **Veritabanını Kontrol Et**: Veri kaybı var mı?
4. **Güvenlik Açığını Kapat**: Sorunu düzelt
5. **Kullanıcıları Bilgilendir**: Şeffaf ol
6. **Şifreleri Sıfırla**: Tüm şifreleri değiştir
7. **Güvenlik Denetimi**: Profesyonel denetim yaptır

---

## 📞 İletişim

Güvenlik açığı bulursanız lütfen sorumlu bir şekilde bildirin.

**Son Güncelleme**: 12 Mart 2026
**Versiyon**: 1.0
