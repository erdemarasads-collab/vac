-- Online kullanıcı takibi için tablo
CREATE TABLE IF NOT EXISTS online_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(255) NOT NULL UNIQUE,
    ip_address VARCHAR(45) NOT NULL,
    current_page VARCHAR(255) NOT NULL,
    user_agent TEXT,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_session (session_id),
    INDEX idx_last_activity (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5 dakikadan eski kayıtları temizlemek için
-- Her gün çalıştırılabilir veya cron job olarak ayarlanabilir
DELETE FROM online_users WHERE last_activity < DATE_SUB(NOW(), INTERVAL 5 MINUTE);
