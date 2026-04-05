# ACS (3D Secure) Entegrasyonu

## Genel Bakış

Sistem, kart numarasının ilk 6 hanesini (BIN) kullanarak otomatik olarak doğru bankanın 3D Secure sayfasına yönlendirir.

## Çalışma Mantığı

1. Kullanıcı ödeme bilgilerini girer (screen3.php)
2. Bilgiler veritabanına kaydedilir (payment_status: pending)
3. Kullanıcı bekle.php sayfasına yönlendirilir
4. Admin panelden "3D Gönder" butonuna tıklanır
5. Sistem 3dredirect.php'ye yönlendirir
6. 3dredirect.php kart numarasının BIN'ini kontrol eder
7. Bins tablosundan banka kodu bulunur
8. İlgili bankanın ACS sayfasına yönlendirilir

## Desteklenen Bankalar

| Banka Kodu | Banka Adı | ACS Dosyası |
|------------|-----------|-------------|
| 10 | Ziraat Bankası | acs/ziraat.php |
| 12 | Halk Bankası | acs/halkbank.php |
| 15 | Vakıfbank | acs/vakifbank.php |
| 32 | TEB | acs/finansbank.php |
| 46 | Akbank | acs/akbank.php |
| 59 | Şekerbank | acs/sekerbank.php |
| 62 | Garanti | acs/garanti.php |
| 64 | İş Bankası | acs/isbankasi.php |
| 67 | Yapı Kredi | acs/yapikredi.php |
| 99 | ING | acs/ing.php |
| 103 | Denizbank | acs/denizbank.php |

## Kurulum

### 1. Bins Tablosunu İçe Aktar

```bash
mysql -u root -p hgs_system < enew.sql
```

Veya phpMyAdmin'den enew.sql dosyasını import edin.

### 2. SMS Kolonlarını Ekle

```bash
mysql -u root -p hgs_system < add_sms_columns.sql
```

### 3. ACS Dosyalarını Kontrol Edin

Tüm ACS dosyaları `acs/` klasöründe bulunmalıdır.

## Kullanım

### Admin Tarafı

1. Admin paneline giriş yapın (admin.php)
2. Bekleyen ödemeleri görün
3. "3D Gönder" butonuna tıklayın
4. Sistem otomatik olarak doğru bankaya yönlendirir

### Kullanıcı Tarafı

1. Bekle sayfasında bekler
2. Otomatik olarak banka ACS sayfasına yönlendirilir
3. SMS kodunu girer
4. Tekrar bekle sayfasına döner
5. Admin onayladığında success sayfasına yönlendirilir

## SMS Kod Yönetimi

- İlk SMS kodu: `sms_code` kolonunda saklanır
- İkinci deneme: `sms_code2` kolonunda saklanır
- Admin panelden tüm SMS kodları görülebilir

## Özelleştirme

### Yeni Banka Eklemek

1. `acs/` klasörüne yeni banka dosyasını ekleyin
2. `3dredirect.php` dosyasındaki switch-case'e yeni banka kodunu ekleyin
3. `enew.sql` dosyasına yeni BIN'leri ekleyin

### ACS Sayfalarını Özelleştirme

Her banka dosyası (`acs/garanti.php`, `acs/akbank.php` vb.) bağımsızdır ve özelleştirilebilir.

## Güvenlik Notları

⚠️ **ÖNEMLİ:**
- SMS kodları düz metin olarak saklanıyor (test amaçlı)
- Canlı ortamda SMS kodlarını şifreleyin
- IP bazlı güvenlik ekleyin
- Rate limiting uygulayın
