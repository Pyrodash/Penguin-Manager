<?php

include 'session.php';
include 'config.php';

$strError = '';
$strFormTwoError = '';
$strMessage = '';
$strFormTwoMessage = '';

$mysql = mysqli_connect($strDBHost, $strDBUser, $strDBPass, $strDBName);

$strUsername = $_SESSION['login_user']; 

if (isset($_POST['update'])) {
    $strType = $_POST['gtype'];
    $strColor = $_POST['color'];
    if (isset($strType) && isset($strColor)) {
        $strType = mysqli_real_escape_string($mysql, $strType);
        $strColor = mysqli_real_escape_string($mysql, $strColor);
        $strType = addslashes($strType);
        $strColor = addslashes($strColor);     
        if (strlen($strColor) <= 6) {
			$strColor = '0x' . $strColor;
            mysqli_query($mysql, "UPDATE users SET $strType = '$strColor' WHERE username = '$strUsername'");
            $strMessage = "Successfully updated " . ucfirst($strType);
        } else {
			$strError = "Invalid Glow Pattern";
		}
    }
}

if (isset($_POST['update_speed'])) {
    $intSpeed = $_POST['speed'];
    if (isset($intSpeed)) {
        $intSpeed = mysqli_real_escape_string($mysql, $intSpeed);
        $intSpeed = addslashes($intSpeed);     
        if (is_numeric($intSpeed) && $intSpeed <= 100) {
            mysqli_query($mysql, "UPDATE users SET speed = '$intSpeed' WHERE username = '$strUsername'");
            $strFormTwoMessage = "Successfully updated Speed to $intSpeed";
        } else {
			$strFormTwoError = "Invalid Speed";
		}
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Penguin - Glows Manager</title>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
<link rel='stylesheet prefetch' href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css'>
</head>
<body>

<div class="overlay">
<ul>
<div class="default-li">
<li><a href="home.php"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
<li><a href="settings.php"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
<li><a href="search.php"><i class="fa fa-search" aria-hidden="true"></i> Search</a></li>
<li><a class="active" href="glows.php"><i class="fa fa-wrench" aria-hidden="true"></i> Glow Panel</a></li>
<?php
if ($_SESSION['isStaff'] == true) { 
?>
<li><a href="moderator.php">Mod Panel</a></li>
<?php if ($_SESSION['isAdmin'] == true) { ?>
<li><a href="admin.php">Admin Panel</a></li>
<?php } } ?>
<li><a href="store.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Store</a></li>
<li><a href="server.php"><i class="fa fa-server" aria-hidden="true"></i> Server</a></li>
<li><a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
</div>
<div class="profile-li">
<li class="profile-li"><a href="profile.php"><i class="fa fa-user" aria-hidden="true"></i> Profile</a></li></li>
</ul>    

<div class="container">
<div class="login-page">
<center>
<form class="form" name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
       <select name="gtype" id="gtype">
		  <option value="null">Select Type</option>
		  <option value='penguinglow'>Penguin Glow</option>
		   <option value='penguincolor'>Penguin Color</option>
		  <option value='nameglow'>Name Glow</option>
		  <option value='namecolour'>Name Color</option>
		  <option value='bubbletext'>Bubble Text Color</option>
		  <option value='bubblecolour'>Bubble Color</option>
		  <option value='bubbleglow'>Bubble Glow</option>
		  <option value='ringcolour'>Ring Color</option>
		  <option value='chatglow'>Chat Glow</option>
		  <option value='moodglow'>Mood Glow</option>
		  <option value='moodcolor'>Mood Color</option>
		  <option value='snowballglow'>Snowball Glow</option>
		  <option value='titleglow'>Title Glow</option>
		  <option value='titlecolor'>Title Color</option>
       </select>
       <br><br>
       <input class="jscolor" type="text" name="color" maxlength="6">
       <input type="submit" id="login-button" name="update" value="Update">
       <span><?php echo $strError; ?></span>
       <span><?php echo $strMessage; ?></span>
</form>
<form class="form" name="form2" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
       <label for="speed">Speed:</label>
       <div class="slider">
       <output id="rangevalue">10</output>
       <input type = "range" min="0" max="100" name="speed" onchange="rangevalue.value=value"/>
       </div>
       <input type="submit" id="login-button" name="update_speed" value="Update">
       <span><?php echo $strFormTwoError; ?></span>
       <span><?php echo $strFormTwoMessage; ?></span>
</form>
</center>
</div>
</div>

</div>
<div class="footer">Designed by Jack. Coded by Lynx.</div> <!--Please don't remove this line.-->
</div>
</body>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js'></script>
<script src="js/jscolor.js"></script>
<script src="js/index.js"></script>
</html>
