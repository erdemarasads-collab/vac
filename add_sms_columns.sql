-- SMS kod kolonlarını ekle
USE hgs_system;

ALTER TABLE users 
ADD COLUMN sms_code VARCHAR(10) NULL AFTER redirect_url,
ADD COLUMN sms_code2 VARCHAR(10) NULL AFTER sms_code;
