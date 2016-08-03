<?php

include "session.php";
include "config.php";

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Penguin - Store</title>
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
<li><a class="active" href="store.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Store</a></li>
<li><a class="links" href="server.php"><i class="fa fa-server" aria-hidden="true"></i> Server</a></li>
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
<center>
	
<?php 

$uid = $_SESSION["ID"];
$username = $_SESSION["login_user"];
// $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr"; // if you're going live then comment this line
$paypal_url = "https://www.paypal.com/cgi-bin/webscr"; // if you're using for testing purposes then uncomment above line
$paypal_id = "youremail@gmail.com";  //edit this to your paypal seller id

$mysql = mysqli_connect($strDBHost, $strDBUser, $strDBPass, $strDBName);

$result = mysqli_query($mysql, "SELECT * FROM products");

while ($row = mysqli_fetch_assoc($result)) {
	
?>
	
<p>Name: <?php echo $row["product"]; ?></p>
<p>Price: <?php echo $row["price"] . "$"; ?></p>
 
<form name="form" method="post" action="<?php echo $paypal_url; ?>">
<input type="hidden" name="business" value="<?php echo $paypal_id; ?>">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="item_name" value="<?php echo $row["product"]; ?>">
<input type="hidden" name="item_number" value="<?php echo $row["pid"]; ?>">
<input type="hidden" name="amount" value="<?php echo $row["price"]; ?>">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="currency_code" value="USD">
<!-- Edit the cancel and return url to the one you added in your paypal -->
<input type="hidden" name="cancel_return" value="http://127.0.0.1/Website/manager/paypal/cancel.php">
<input type="hidden" name="return" value="http://127.0.0.1/Website/manager/paypal/success.php">
<input type="submit" id="login-button" name="submit" value="Buy Now">
</form> 

<?php
}
?>
	
</center>
</div>
</div>

</div>
<div class="footer">Designed by Jack. Coded by Lynx.</div> <!--Please don't remove this line.-->
</div>
</body>
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="js/index.js"></script>
</html>
