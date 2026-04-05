<?php
/**
 * Security Helper Functions
 * XSS, SQL Injection ve Input Validation koruması
 */

class Security {
    
    /**
     * XSS koruması - HTML karakterlerini temizle
     */
    public static function xss_clean($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::xss_clean($value);
            }
            return $data;
        }
        
        // HTML karakterlerini encode et
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        
        // Tehlikeli karakterleri temizle
        $data = str_replace(['<script', '</script', 'javascript:', 'onerror=', 'onclick='], '', $data);
        
        return $data;
    }
    
    /**
     * Sadece rakam kontrolü
     */
    public static function validate_numeric($value, $min_length = 0, $max_length = 999) {
        $value = preg_replace('/[^0-9]/', '', $value);
        $length = strlen($value);
        
        if ($length < $min_length || $length > $max_length) {
            return false;
        }
        
        return $value;
    }
    
    /**
     * Kart numarası validasyonu (sadece rakam, 13-19 karakter)
     */
    public static function validate_card_number($card_number) {
        // Sadece rakamları al
        $card_number = preg_replace('/[^0-9]/', '', $card_number);
        
        // Uzunluk kontrolü (13-19 karakter)
        if (strlen($card_number) < 13 || strlen($card_number) > 19) {
            return false;
        }
        
        // Luhn algoritması ile kart numarası doğrulama
        return self::luhn_check($card_number) ? $card_number : false;
    }
    
    /**
     * Luhn algoritması (kart numarası doğrulama)
     */
    private static function luhn_check($number) {
        $sum = 0;
        $length = strlen($number);
        $parity = $length % 2;
        
        for ($i = 0; $i < $length; $i++) {
            $digit = (int)$number[$i];
            
            if ($i % 2 == $parity) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            
            $sum += $digit;
        }
        
        return ($sum % 10) == 0;
    }
    
    /**
     * Kart SKT validasyonu (MM/YY formatı)
     */
    public static function validate_card_expiry($expiry) {
        // Sadece rakam ve / karakteri (boşlukları temizle)
        $expiry = preg_replace('/[^0-9\/]/', '', $expiry);
        
        // MM/YY formatı kontrolü
        if (!preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $expiry, $matches)) {
            return false;
        }
        
        $month = (int)$matches[1];
        $year = (int)$matches[2] + 2000;
        
        // Geçmiş tarih kontrolü
        $current_year = (int)date('Y');
        $current_month = (int)date('m');
        
        if ($year < $current_year || ($year == $current_year && $month < $current_month)) {
            return false;
        }
        
        return $expiry;
    }
    
    /**
     * CVV validasyonu (3-4 rakam)
     */
    public static function validate_cvv($cvv) {
        $cvv = preg_replace('/[^0-9]/', '', $cvv);
        
        if (strlen($cvv) < 3 || strlen($cvv) > 4) {
            return false;
        }
        
        return $cvv;
    }
    
    /**
     * Telefon numarası validasyonu (10-11 rakam)
     */
    public static function validate_phone($phone) {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (strlen($phone) < 10 || strlen($phone) > 11) {
            return false;
        }
        
        return $phone;
    }
    
    /**
     * TC Kimlik No validasyonu (11 rakam)
     */
    public static function validate_tc($tc) {
        $tc = preg_replace('/[^0-9]/', '', $tc);
        
        if (strlen($tc) != 11) {
            return false;
        }
        
        // İlk rakam 0 olamaz
        if ($tc[0] == '0') {
            return false;
        }
        
        // TC Kimlik algoritması
        $odd = $tc[0] + $tc[2] + $tc[4] + $tc[6] + $tc[8];
        $even = $tc[1] + $tc[3] + $tc[5] + $tc[7];
        
        $check1 = ($odd * 7 - $even) % 10;
        $check2 = ($odd + $even + $tc[9]) % 10;
        
        if ($check1 != $tc[9] || $check2 != $tc[10]) {
            return false;
        }
        
        return $tc;
    }
    
    /**
     * İsim validasyonu (sadece harf ve boşluk)
     */
    public static function validate_name($name) {
        // Sadece harf, boşluk ve Türkçe karakterler (unicode destekli)
        $name = preg_replace('/[^\p{L}\s]/u', '', $name);
        $name = trim($name);
        
        if (strlen($name) < 2 || strlen($name) > 100) {
            return false;
        }
        
        return $name;
    }
    
    /**
     * Tutar validasyonu (pozitif sayı)
     */
    public static function validate_amount($amount) {
        // Sadece rakam ve nokta
        $amount = preg_replace('/[^0-9.]/', '', $amount);
        $amount = floatval($amount);
        
        if ($amount <= 0 || $amount > 999999.99) {
            return false;
        }
        
        return $amount;
    }
    
    /**
     * Email validasyonu
     */
    public static function validate_email($email) {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        return $email;
    }
    
    /**
     * URL validasyonu
     */
    public static function validate_url($url) {
        $url = filter_var($url, FILTER_SANITIZE_URL);
        
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }
        
        return $url;
    }
    
    /**
     * SQL Injection koruması için prepared statement kontrolü
     */
    public static function validate_sql_input($input) {
        // Tehlikeli SQL karakterlerini kontrol et
        $dangerous = ['--', ';--', '/*', '*/', 'xp_', 'sp_', 'EXEC', 'EXECUTE', 'UNION', 'SELECT', 'INSERT', 'UPDATE', 'DELETE', 'DROP', 'CREATE', 'ALTER'];
        
        foreach ($dangerous as $pattern) {
            if (stripos($input, $pattern) !== false) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * CSRF Token oluştur
     */
    public static function generate_csrf_token() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * CSRF Token doğrula
     */
    public static function verify_csrf_token($token) {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Rate limiting - IP bazlı istek sınırlama
     */
    public static function rate_limit($ip, $max_requests = 60, $time_window = 60) {
        $cache_file = sys_get_temp_dir() . '/rate_limit_' . md5($ip) . '.txt';
        
        $requests = [];
        if (file_exists($cache_file)) {
            $requests = json_decode(file_get_contents($cache_file), true) ?: [];
        }
        
        // Eski istekleri temizle
        $current_time = time();
        $requests = array_filter($requests, function($timestamp) use ($current_time, $time_window) {
            return ($current_time - $timestamp) < $time_window;
        });
        
        // İstek sayısını kontrol et
        if (count($requests) >= $max_requests) {
            return false;
        }
        
        // Yeni isteği ekle
        $requests[] = $current_time;
        file_put_contents($cache_file, json_encode($requests));
        
        return true;
    }
    
    /**
     * IP adresi al
     */
    public static function get_ip() {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        
        // Proxy arkasındaysa gerçek IP'yi al
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        
        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
    }
    
    /**
     * Güvenli rastgele string oluştur
     */
    public static function generate_random_string($length = 32) {
        return bin2hex(random_bytes($length / 2));
    }
    
    /**
     * Session güvenliği
     */
    public static function secure_session() {
        // Session hijacking koruması
        if (!isset($_SESSION['user_agent'])) {
            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $_SESSION['created'] = time();
            return true;
        }
        
        // User agent değişmişse session'ı yok et
        if ($_SESSION['user_agent'] !== ($_SERVER['HTTP_USER_AGENT'] ?? '')) {
            session_destroy();
            return false;
        }
        
        // Session fixation koruması - her 1 saatte bir yenile
        if (!isset($_SESSION['created'])) {
            $_SESSION['created'] = time();
        } elseif (time() - $_SESSION['created'] > 3600) {
            session_regenerate_id(true);
            $_SESSION['created'] = time();
        }
        
        return true;
    }
}
?>
