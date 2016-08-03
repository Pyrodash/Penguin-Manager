<?php 

require_once('/../../service/app/inc/mnx_config.php');
header('Content-type: application/json');

/* 
	User API 
	   2016 By Shawn

	   ========================[Notices]============================
	   Recieves a limited amount of information for that current user
	   then converts the data into json.

	   Secure Methods TODO
	   - Add Authorized Tokens example ?id=1&token=randomstring
	   allows more security to the data from being over used.

*/

if(in_array($_SERVER['REMOTE_ADDR'], $config['blockList'])){

	$mnxResponse = array();
	$mnxResponse['error'] = true;
	$mnxResponse['errorCode'] = 'Missing parameter [Access Denied]';
	$mnxResponse['errorNum'] = 7821;
	$mnxResponse['success'] = false;
	die(json_encode($mnxResponse));
}


 $ID = $_GET['id'];
 $secure_tokens = $_GET['authorized_key'];



require_once('/../../service/app/inc/mnx_config.php');
$db = new PDO('mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'], $config['db_user'], $config['db_pass']);


 $sql = $db->prepare("SELECT `id` FROM tokens WHERE secure_token = :Tokens");
 $sql->bindParam(':Tokens', $secure_tokens);
 $sql->execute();
 $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
 if (count($result) == 0) {
	$mnxResponse = array();
	$mnxResponse['error'] = true;
	$mnxResponse['errorCode'] = 'Missing parameter [Invalid Token]';
	$mnxResponse['errorNum'] = 1939;
	$mnxResponse['success'] = false;
	die(json_encode($mnxResponse));
}
$sql = $db->prepare("SELECT `permissions` FROM tokens WHERE secure_token = :Tokens");
$sql->bindParam(':Tokens', $secure_tokens);
$sql->execute();
$result = $sql->fetchAll(\PDO::FETCH_ASSOC);
foreach ($result as $row) {
	 $ar = json_decode($row['permissions']);

}
if ($ar->hasValidUse !== true) {
	$mnxResponse = array();
	$mnxResponse['error'] = true;
	$mnxResponse['errorCode'] = 'Missing parameter [Invalid Permissions]';
	$mnxResponse['errorNum'] = 1952;
	$mnxResponse['success'] = false;
	die(json_encode($mnxResponse));
}
 $sql = $db->prepare("SELECT `id`, `username`, `email`, `isModerator`, `coins` FROM users WHERE id = :ID");
 $sql->bindParam(':ID', $ID);
 $sql->execute();
 $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
 if (count($result) == 0) {
	$mnxResponse = array();
	$mnxResponse['error'] = true;
	$mnxResponse['errorCode'] = 'Missing parameter [User doesn\'t exist]';
	$mnxResponse['errorNum'] = 1945;
	$mnxResponse['success'] = false;
	die(json_encode($mnxResponse));
}
foreach ($result as $row) {
    $return = array (
        'success' => true,
        'player_id' => $row['id'],
        'username'  => $row['username'],
        'email'  => $row['email'],
        'isModerator' => $row['isModerator'],
        'coins' => $row['coins']
      );
}
$db = null;

echo json_encode($return);
?>