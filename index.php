<?php

include 'login.php';

if (isset($_SESSION['login_user'])) {
    header('location: profile.php');
}

?>

<!DOCTYPE html>
<html >
<head>
<body onload="myFunction()" style="margin:0;">
<meta charset="UTF-8">
<title>Penguin</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body> 
<div class="loader" id="loader"></div>

<div style="display:none;" id="myDiv" class="animate-bottom">

    <div class="login-page">
  <div class="form">
  <p>Penguin</p>
<form class="register-form" name="form" method="post" action="register.php">
       <input type="text" name="username" maxlength="10" placeholder="username">
       <input type="text" name="email" maxlength="25" placeholder="email">
       <input type="password" name="pass" maxlength="15" placeholder="password">
       <input type="password" name="passtwo" maxlength="15" placeholder="password again">
       <input type="password" name="spin" maxlength="6" placeholder="secret pin">
       <label for="color">Penguin Color</label>
	   <input class="jscolor jscolor-active" type="text" name="color" maxlength="6" autocomplete="off" style="color: rgb(255, 255, 255); background-image: none; background-color: rgb(36, 141, 117);">       <br><br>
       <div class="g-recaptcha" data-sitekey="<?php echo $strSiteKey; ?>"></div>
       <script type="text/javascript" src='https://www.google.com/recaptcha/api.js?hl=en'></script>
       <br>
       <input type="submit" id="login-button" name="submit" value="Sign Up">
	   <p class="message">Already registered? <a href="#">Sign In</a></p>
</form>
<form class="login-form" name="form" method="post" action="">
       <input type="text" name="username" maxlength="10" placeholder="username">
       <input type="password" name="password" maxlength="15" placeholder="password">
       <input type="password" name="spin" maxlength="6" placeholder="secret pin">
       <input type="submit" id="login-button" name="submit" value="Sign In">
       <span><?php echo $strError; ?></span>
      <p class="message">Not registered? <a href="#">Create an account</a></p>
    </form>
  </div>
</div>
</div>
<script>
var myVar;

function myFunction() {
    myVar = setTimeout(showPage, 2000);
}

function showPage() {
  document.getElementById("loader").style.display = "none";
  document.getElementById("myDiv").style.display = "block";
}
</script>

</body>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="index.js"></script>
<script src="../js/jscolor.js"></script>
</html>