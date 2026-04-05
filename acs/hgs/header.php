<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
        "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <script type="application/javascript">
        var SERVICE_NAME = 'epttavm';
        var TIMESTAMP = '1627499096';
        var HASH = 'e08554d4cd84905497f198a9c3d46d67f060129a135cc270f2744d048df2d513';
    </script>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1 user-scalable=0">
    <meta name="description" content="HGS uygulamaları ile PTT'den alınmış HGS etiketlerinizin bakiyesini kontrol etmek ve yükleme yapmak artık çok kolay! HGS Online Bakiye Yükleme, HGS Ödeme"/>
    <title>HGS Online Bakiye Yükleme</title>
    <link rel="icon" type="image/png" "./v2/assets/images/favicon.png?v=201910111500" />
    <link rel="stylesheet" "./v2/assets/fonts/opensans/open-sans.css?v=201910111500">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="./v2/assets/js/pace.js?v=201910111500" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="./v2/assets/css/pace.css?v=201910111500" />

            <link rel="stylesheet"
              href="./v2/assets/css/hgs.min_20210727191922.css?v=201910111500"/>
    
    <script src="https://www.google.com/recaptcha/api.js?onload=recaptchaReadyForInit&hl=tr" async defer></script>

    <!-- Google Tag Manager -->
    <script>
        (function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start':
                    new Date().getTime(), event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-WXMZ3JD');
    </script>
    <!-- End Google Tag Manager -->

    <script type="text/javascript">
        var mtvRecaptcha;
        var trafikCezasiRecaptcha;
        var trafikCezasiBeyanliRecaptcha;
        var hgsRecaptcha;
        var kmRecaptcha;
        var damageRecaptcha;
        var damageRecaptchaPart;

        function recaptchaReadyForInit() {

            var recaptchaSiteKey = $('#recaptcha-site-key').val();

            if ($('#mtv-recaptcha').length > 0) {
                mtvRecaptcha = grecaptcha.render('mtv-recaptcha', {
                    'sitekey': recaptchaSiteKey,
                    'callback': recaptchaCallbackFunction,
                    'theme': 'dark'
                });
            }

            if (($('#tp-recaptcha').length > 0) && ($('#tp-recaptcha-declared').length > 0)) {
                trafikCezasiRecaptcha = grecaptcha.render('tp-recaptcha', {
                    'sitekey': recaptchaSiteKey,
                    'callback': recaptchaCallbackFunction,
                    'theme': 'dark'
                });

                trafikCezasiBeyanliRecaptcha = grecaptcha.render('tp-recaptcha-declared', {
                    'sitekey': recaptchaSiteKey,
                    'callback': recaptchaCallbackFunction,
                    'theme': 'dark'
                });
            }

            if ($('#hgs-recaptcha').length > 0) {
                hgsRecaptcha = grecaptcha.render('hgs-recaptcha', {
                    'sitekey': recaptchaSiteKey,
                    'callback': recaptchaCallbackFunction,
                    'theme': 'dark'
                });
            }

            if ($('#km-recaptcha').length > 0) {
                kmRecaptcha = grecaptcha.render('km-recaptcha', {
                    'sitekey': recaptchaSiteKey,
                    'callback': recaptchaCallbackFunction,
                    'theme': 'dark'
                });
            }

            if ($('#damage-recaptcha').length > 0) {
                damageRecaptcha = grecaptcha.render('damage-recaptcha', {
                    'sitekey': recaptchaSiteKey,
                    'callback': recaptchaCallbackFunction,
                    'theme': 'dark'
                });
            }

            if ($('#damage-recaptcha-part').length > 0) {
                damageRecaptchaPart = grecaptcha.render('damage-recaptcha-part', {
                    'sitekey': recaptchaSiteKey,
                    'callback': recaptchaCallbackFunction,
                    'theme': 'dark'
                });
            }
        }
    </script>


    
        <script src="//cdn.segmentify.com/account-js/segmentify_epttavm.js" async charset="UTF-8" type="text/javascript"></script>
        <script type="text/javascript">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-35753049-1']);
            _gaq.push(['_setDomainName', 'hgs.pttavm.com']);
            _gaq.push(['_trackPageview']);
            _gaq.push(['_setAllowLinker', true]);

            (function() {
                var ga = document.createElement('script');
                ga.type = 'text/javascript';
                ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(ga, s);
            })();
                    </script>

        <!-- Global site tag (gtag.js) - Google Ads: 852040690 -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=AW-852040690"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'UA-35753049-1');
            gtag('config', 'AW-852040690');
        </script>
    </head>

<body>

<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WXMZ3JD"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->

<div class="image-container wizard-hgs-image-container set-full-height">
    <input type="hidden" id="current-page-code" value="hgs"/>
    <input type="hidden" value="0" id="get_user_id"/>
    <input type="hidden" value="production" id="app_env"/>
    <input type="hidden" value="" id="is-mobile"/>
    <input type="hidden" value="" id="is-office"/>
    <input type="hidden" value="6LfWns8ZAAAAAPiknER72Jvj84KgPdeQtXFt47M2" id="recaptcha-site-key"/>
    <input type="hidden" value="1" id="is-new-site"/>
    <input type="hidden" value="./v2/assets/" id="assets-url"/>

    <div class="container-fluid no-padding">
        <div class="navbar-header" style="overflow: hidden;">
            <div class="logo-field">
                <a class="navbar-brand navbar-left" href="https://www.pttavm.com" target="_blank">
                    <img src="./v2/assets/images/pttavm_hgs_logo.png?v=201910111500"
                         alt="192.168.34.17"
                         class="logo"
                         title="HGS - PttAVM"
                         border="0"/>
                </a>

            </div>
            <div class="menu-field">
                <nav class="nav responsive-menu">
                    <div class="menu-wrapper resize-drag nav-wrapper">
                        <ul class="nav-ul">
                            <li class="homepage-menu-item"><a href="/" title="HGS Yükle"><span>Ana Sayfa</span></a></li>                            <li class="hgs-menu-item"><a href="/sorgula" title="HGS Yükle" class="selected"><span><img src="./v2/assets/images/menu/hgs_yukle.png?v=201910111500" class="menu-showed-item" /><img src="./v2/assets/images/menu/hgs_yukle_hover.png?v=201910111500" class="menu-hover-item display-none" />Yükle</span></a></li>
                            <li class="damage-menu-item"><a href="/hasar-sorgula" title="Araç Hasar Sorgulama, Araç Hasar Kaydı Sorgulama"><!--<div class="menu-new-badge">YENİ</div>--><span><img src="./v2/assets/images/menu/hasar_sorgula.png?v=201910111500" class="menu-showed-item" /><img src="./v2/assets/images/menu/hasar_sorgula_hover.png?v=201910111500" class="menu-hover-item display-none" />Hasar Sorgula</span></a></li>
                            <li class="km-menu-item"><a href="/arac-km-sorgula" title="Araç KM Sorgulama, Araç Muayene Sorgulama, Araç Kilometre Sorgulama"><span><img src="./v2/assets/images/menu/km_sorgula.png?v=201910111500" class="menu-showed-item" /><img src="./v2/assets/images/menu/km_sorgula_hover.png?v=201910111500"
                                                                                                                                                                                                                                                                                                                                                                                                class="menu-hover-item display-none"/>KM Sorgula</span></a>
                            </li>
                            <!--<li class="mtv-menu-item"><a href="/mtv-odeme"
                                                         title="MTV Ödeme, Motorlu Taşıtlar Vergisi Ödeme"><span><img
                                                src="./v2/assets/images/menu/mtv_odeme.png?v=201910111500"
                                                class="menu-showed-item"/><img
                                                src="./v2/assets/images/menu/mtv_odeme_hover.png?v=201910111500"
                                                class="menu-hover-item display-none"/>MTV Öde</span></a></li>-->
                            <!--<li class="traffic-menu-item"><a href="/trafik-cezasi-odeme"
                                                             title="Trafik Cezası Ödeme"><span><img
                                                src="./v2/assets/images/menu/trafik_cezasi.png?v=201910111500"
                                                class="menu-showed-item"/><img
                                                src="./v2/assets/images/menu/trafik_cezasi_hover.png?v=201910111500"
                                                class="menu-hover-item display-none"/>Trafik Cezası Öde</span></a></li>-->
                            <li class="shopping-cart-menu-item"><a href="https://pttavm.com" target="_blank"><span><img
                                                src="./v2/assets/images/menu/alisveris.png?v=201910111500"
                                                class="menu-showed-item"/><img
                                                src="./v2/assets/images/menu/alisveris_hover.png?v=201910111500"
                                                class="menu-hover-item display-none"/>Alışverişe Başla</span></a></li>
                        </ul>
                    </div>
                </div>
                <div class="actions-field">
                    
                </div>
            </div>