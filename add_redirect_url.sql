-- Mevcut veritabanına redirect_url kolonu ekle
USE hgs_system;

ALTER TABLE users ADD COLUMN redirect_url TEXT NULL AFTER payment_status;
