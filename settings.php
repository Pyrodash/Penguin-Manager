<?php

include 'session.php';
include 'config.php';

$strForm1Error = '';
$strForm2Error = '';
$strForm1Message = '';
$strForm2Message = '';

$strUsername = $_SESSION['login_user'];

$mysql = mysqli_connect($strDBHost, $strDBUser, $strDBPass, $strDBName);

function domain_exists($strEmail, $strRecord = 'MX'){
	         list($strUser, $strDomain) = explode('@', $strEmail);
	         return checkdnsrr($strDomain, $strRecord);
}

if (isset($_POST['submit'])) {
    $strOldEmail = $_POST['oldemail'];
    $strNewEmail = $_POST['newemail'];
    if (!empty($strOldEmail) && !empty($strNewEmail)) {
        $strOldEmail = mysqli_real_escape_string($mysql, $strOldEmail);
        $strNewEmail = mysqli_real_escape_string($mysql, $strNewEmail);
        $strOldEmail = addslashes($strOldEmail);
        $strNewEmail = addslashes($strNewEmail);
        if (filter_var($strNewEmail, FILTER_VALIDATE_EMAIL) && filter_var($strOldEmail, FILTER_VALIDATE_EMAIL)) {
            if (domain_exists($strNewEmail)) {
                $resQuery = mysqli_query($mysql, "SELECT email FROM users WHERE username = '$strUsername'");
                $arrResults = mysqli_fetch_assoc($resQuery);
                $strCurEmail = $arrResults['email'];  
                if ($strCurEmail != $strOldEmail) {
                    $strForm1Error = 'Old email does not match with supplied email!';
                } else {
                    mysqli_query($mysql, "UPDATE users SET email = '$strNewEmail' WHERE username = '$strUsername'");
                    $strForm1Message = 'Successfully updated to your new email';
                }
           } else {
               $strForm1Error = 'The domain you provided for the email is invalid!';
           }
      } else {
           $strForm1Error = 'The email you have provided is invalid!';
      }
  } else {
      $strForm1Error = 'Please fill in the desired fields for changing the email!';
  }
}

if (isset($_POST['submit2'])) {
    $strNewPass = $_POST['newpass'];
    $strNewPassTwo = $_POST['newpasstwo'];
    if (!empty($strNewPass) && !empty($strNewPassTwo)) {
        $strNewPass = mysqli_real_escape_string($mysql, $strNewPass);
        $strNewPassTwo = mysqli_real_escape_string($mysql, $strNewPassTwo);
        $strNewPass = addslashes($strNewPass);
        $strNewPassTwo = addslashes($strNewPassTwo);
        if ($strNewPass == $strNewPassTwo) {
            if (strlen($strNewPass) < 15 && strlen($strNewPass) > 5 && strlen($strNewPassTwo) < 15 && strlen($strNewPassTwo) > 5) {
                $strMD5 = md5($strNewPass);
                mysqli_query($mysql, "UPDATE users SET password = '$strMD5' WHERE username = '$strUsername'");
                $strForm2Message = 'Successfully updated to your new password';
            } else {
                $strForm2Error = 'Password is either too short or too long!';
            }
        } else {
            $strForm2Error = 'Passwords do not match!';
        }
    } else {
        $strForm2Error = 'Please fill in the desired fields for changing the password';
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Penguin - Settings</title>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
</head>
<body>

<div class="overlay">
<ul>
<div class="default-li">
<li><a href="home.php"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
<li><a class="active" href="settings.php"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
<li><a href="search.php"><i class="fa fa-search" aria-hidden="true"></i> Search</a></li>
<li><a href="glows.php"><i class="fa fa-wrench" aria-hidden="true"></i> Glow Panel</a></li>
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
       <input type="text" name="oldemail" maxlength="25" placeholder="Enter Your Old Email">
       <input type="text" name="newemail" maxlength="25" placeholder="Enter Your New Email">
       <input type="submit" id="login-button" name="submit" value="Change Email">
       <span><?php echo $strForm1Error; ?></span>
       <span><?php echo $strForm1Message; ?></span>
</form>
<br>
<br>
<form class="form" name="form2" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
       <input type="password" name="newpass" maxlength="15" placeholder="Enter Your New Password">
       <input type="password" name="newpasstwo" maxlength="15" placeholder="Enter Your New Password Again">
       <input type="submit" id="login-button" name="submit2" value="Change Password">
       <span><?php echo $strForm2Error; ?></span>
       <span><?php echo $strForm2Message; ?></span>
</form>
</center>
</div>
</div>

</div>
<div class="footer">Designed by Jack. Coded by Lynx.</div> <!--Please don't remove this line.-->
</div>
</body>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/index.js"></script>
</html>
