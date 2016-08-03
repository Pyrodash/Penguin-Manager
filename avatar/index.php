<?php
/* 
	Avatar API
	   2016 By Shawn

	   ========================[Notices]============================
*/
function downloadPapers($itemId, $paperSizes = array(60, 88, 120, 600)){
	foreach($paperSizes as $paperSize){
		$avatarPaperUri = sprintf('paper/image/%d/%d.png', $paperSize, $itemId);
		$avatarPaperUrl = 'http://mobcdn.clubpenguin.com/game/items/images/' . $avatarPaperUri;
		$paperCurl = curl_init($avatarPaperUrl);
		curl_setopt($paperCurl, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($paperCurl, CURLOPT_RETURNTRANSFER, true);
		$imageData = curl_exec($paperCurl);
		$statusCode = curl_getinfo($paperCurl, CURLINFO_HTTP_CODE);
		curl_close($paperCurl);
		if($statusCode == 200){
			file_put_contents($avatarPaperUri, $imageData);
		} else {
			return false;
		}
	}
}
function cachePaper($itemId, $paperSize){
	$downloadStatus = downloadPapers($itemId);
	
	if($downloadStatus !== false){
		$paperImage = imagecreatefrompng(sprintf('paper/image/%d/%d.png', $paperSize, $itemId));
		return $paperImage;
	} else {
		return false;
	}
}
function returnPaperResource($itemId, $paperSize = 120){
	$avatarUnbiasUri = sprintf('paper/image/%d/%d.png', $paperSize, $itemId);
	$paperImage = file_exists($avatarUnbiasUri) ? imagecreatefrompng($avatarUnbiasUri) : cachePaper($itemId, $paperSize);
	
	return $paperImage;
}
$validPaperSizes = array(60, 88, 120, 600);
$defaultPaperSize = 120;
if(isset($_GET['id']) === false){
	die();
}
$playerSwid = $_GET['id'];
if(isset($_GET['size']) === false){
	$avatarPaperSize = 120;
} else {
	$avatarPaperSize = $_GET['size'];
}
if(is_dir("paper/image/60/") === false){
	mkdir("paper/image/60/", 0777, true);
}
if(is_dir("paper/image/88/") === false){
	mkdir("paper/image/88/", 0777, true);
}
if(is_dir("paper/image/120/") === false){
	mkdir("paper/image/120/", 0777, true);
}
if(is_dir("paper/image/600/") === false){
	mkdir("paper/image/600/", 0777, true);
}
if(!in_array($avatarPaperSize, $validPaperSizes)){
	$avatarPaperSize = $defaultPaperSize;
}
$connectionString = 'mysql:dbname=Luna;host=127.0.0.1';
$databaseUser = 'root';
$databasePass = '1337h4x0r';
try {
	$database = new PDO($connectionString, $databaseUser, $databasePass);
} catch(PDOException $pdoException){
	echo $pdoException->getMessage(), die();
}
$playerQuery = 'SELECT ID FROM `users` WHERE ID = :ID'; 
$playerStatement = $database->prepare($playerQuery);
$playerStatement->bindValue(':ID', $playerSwid);
$playerStatement->execute();
$rowCount = $playerStatement->rowCount();
$playerStatement->closeCursor();
if($rowCount < 1){
	echo 'Player doesn\'t exist', die();
}
$clothingQuery = 'SELECT head, face, body, neck, hand, feet, photo, flag FROM `users` WHERE ID = :ID';
$clothingStatement = $database->prepare($clothingQuery);
$clothingStatement->bindValue(':ID', $playerSwid);
$clothingStatement->execute();
$playerClothing = $clothingStatement->fetch(PDO::FETCH_ASSOC);
$clothingStatement->closeCursor();
header('Content-type: image/png');
$colorResource = returnPaperResource(4, $avatarPaperSize);
if($playerClothing['photo'] != 0){
	$imageResource = returnPaperResource($playerClothing['photo'], $avatarPaperSize);
	if($imageResource === false) {
		$imageResource = $colorResource;
	} else {
		imagecopyresampled($imageResource, $colorResource, 0, 0, 0, 0, imagesx($imageResource), imagesy($imageResource), imagesx($colorResource), imagesy($colorResource));
	}
} else {
	$imageResource = $colorResource;
}
unset($playerClothing['photo']);
foreach($playerClothing as $clothingPart => $itemId){
	if($itemId != 0){
		$clothingResource = returnPaperResource($itemId, $avatarPaperSize);
		if($clothingResource !== false) {
			imagecopyresampled($imageResource, $clothingResource, 0, 0, 0, 0, imagesx($imageResource), imagesy($imageResource), imagesx($clothingResource), imagesy($clothingResource));
		}
	}
}
imagealphablending($imageResource, false);
imagesavealpha($imageResource, true);
imagepng($imageResource);
imagedestroy($imageResource);
unset($database);
?>
