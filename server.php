<?php

include 'session.php';

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Penguin - Server</title>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
</head>
<body>

<div class="overlay">
<ul>
<div class="default-li">
<li><a class="links" href="home.php"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
<li><a class="links" href="search.php"><i class="fa fa-search" aria-hidden="true"></i> Search</a></li>
<?php
if ($_SESSION['isStaff'] == true) {
?>
<li><a class="links" href="moderator.php">Mod Panel</a></li>
<?php if ($_SESSION['isAdmin'] == true) { ?>
<li><a class="links" href="admin.php">Admin Panel</a></li>
<?php } } ?>
<li><a class="links" href="store.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Store</a></li>
<li><a class="active" href="server.php"><i class="fa fa-server" aria-hidden="true"></i> Server</a></li>
<li><a class="links" href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
</div>
<div class="profile-li">
<li class="profile-li">   <div class="dropdown-content">
      <a class="test2" href="settings.php"><font color="white"><i class="fa fa-cog" aria-hidden="true"></i> Settings<font></a>
      <a class="test" href="logout.php"><font color="white"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</font></a>
    </div><a href="profile.php"><i class="fa fa-user" aria-hidden="true"></i>
<?php

include 'config.php';

$strUsername = $_SESSION['login_user'];

$mysql = mysqli_connect($strDBHost, $strDBUser, $strDBPass, $strDBName);

$resQuery = mysqli_query($mysql, "SELECT * FROM users WHERE username = '$strUsername'");


echo ' ' . $arrResults['username'] . '';
?>
</a>
</li></li>
</ul>

<div class="container">
<div class="login-page">
<div class="form">
<?php


include '../config.php';

$mysql = mysqli_connect($strDBHost, $strDBUser, $strDBPass, $strDBName);

$resQuery = mysqli_query($mysql, "SELECT * FROM users");

$intRegistered = mysqli_num_rows($resQuery);

$resQueryTwo = mysqli_query($mysql, "SELECT curPop FROM servers WHERE servPort = '$intGamePort'");

$arrData = mysqli_fetch_assoc($resQueryTwo);

echo '<center>';
echo ($resCon = @fsockopen($strServerHost, $intLoginPort)) ? "Login Server: <font color=\"green\">Online</font>" : "Login Server: <font color=\"red\">Offline</font>";
echo '<br>';
echo ($resCon = @fsockopen($strServerHost, $intGamePort)) ? "Game Server: <font color=\"green\">Online</font>" : "Game Server: <font color=\"red\">Offline</font>";
@fclose($resCon);
echo '<p>Users Registered: ' . $intRegistered . '</p>';
echo '<p>Users Online: ' . $arrData['curPop'] . '</p>';
echo "</center>";

?>
</div>
</div>

</div>
<div class="footer">Designed by Jack. Coded by Lynx.</div> <!--Please don't remove this line.-->
</div>
</body>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/index.js"></script>
</html>
