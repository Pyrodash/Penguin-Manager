<?php //Credits to ShawnTD
 
header('Content-Type: image/png');
 
header('Cache-Control: public, max-age=31556926 , pre-check=31556926');
 
class penguinAvatar {
 
  public function createAvatar(array $avatarArr = [], $avatarSize = null){
           $avatarMainHeaders = get_headers('http://mobcdn.clubpenguin.com/game/items/images/paper/image/' . $avatarSize . '/' . $avatarArr[0] . '.png');
           if ($avatarMainHeaders[0] == 'HTTP/1.1 200 OK'){
               $avatarMain = imagecreatefrompng('http://mobcdn.clubpenguin.com/game/items/images/paper/image/' . $avatarSize . '/' . $avatarArr[0] . '.png');
               foreach($avatarArr as $avatarItem) {
                            $avatarItemHeaders = get_headers('http://mobcdn.clubpenguin.com/game/items/images/paper/image/' . $avatarSize . '/' . $avatarItem . '.png');       
                            if ($avatarItemHeaders[0] == 'HTTP/1.1 200 OK') {
                                if ($avatarItem !== $avatarArr[0]) {
                                    $avatarMainItem = imagecreatefrompng('http://mobcdn.clubpenguin.com/game/items/images/paper/image/' . $avatarSize . '/' . $avatarItem . '.png');
                                    imagecopy($avatarMain, $avatarMainItem, 0, 0, 0, 0, $avatarSize, $avatarSize);
                                }
                           }
               }
               imagesavealpha($avatarMain, true);
               imagepng($avatarMain);
               imagedestroy($avatarMain);
         }
  }
}
 
$avatarArr = explode('|', $_GET['avatarInfo']);
$avatarSize = $_GET['avatarSize'];
 
$penguinAvatar = new penguinAvatar();
$penguinAvatar->createAvatar($avatarArr, $avatarSize);

?>
