-- Online users tablosuna user_identifier kolonu ekle
ALTER TABLE online_users 
ADD COLUMN IF NOT EXISTS user_identifier VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL AFTER session_id,
ADD INDEX IF NOT EXISTS idx_user_identifier (user_identifier);

-- Tüm kolonların collation'ını düzelt
ALTER TABLE online_users 
MODIFY session_id VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
MODIFY user_identifier VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
MODIFY ip_address VARCHAR(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
MODIFY current_page VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

-- Users tablosunun collation'ını kontrol et ve düzelt
ALTER TABLE users 
MODIFY user_identifier VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;

-- Mevcut kayıtları temizle
TRUNCATE TABLE online_users;
