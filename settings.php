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
<li><a class="links" href="server.php"><i class="fa fa-server" aria-hidden="true"></i> Server</a></li>
</div>
<div class="profile-li">
<li class="profile-li nav">   <div class="dropdown-content">
      <a class="test2" href="settings.php"><font color="white"><i class="fa fa-cog" aria-hidden="true"></i> Settings<font></a>
      <a class="test" href="logout.php"><font color="white"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</font></a>
    </div><a class="active" href="profile.php"><i class="fa fa-user" aria-hidden="true"></i> 
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
<center>
<form class="form" name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<h4>Change Email</h4>
       <input type="text" name="oldemail" maxlength="25" placeholder="Enter your old email">
       <input type="text" name="newemail" maxlength="25" placeholder="Enter your new email">
       <input type="submit" id="login-button" name="submit" value="Change Email">
       <span><?php echo $strForm1Error; ?></span>
       <span><?php echo $strForm1Message; ?></span>
</form>
<br>
<br>
<form class="form" name="form2" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<h4>Change Password</h4>
       <input type="password" name="newpass" maxlength="15" placeholder="Enter your new password">
       <input type="password" name="newpasstwo" maxlength="15" placeholder="Enter your new password again">
       <input type="submit" id="login-button" name="submit2" value="Change Password">
       <span><?php echo $strForm2Error; ?></span>
       <span><?php echo $strForm2Message; ?></span>
</form>
<form class="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<h4>Edit Glows</h4>
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
	   <input id="toHide" name="toHide" class="jscolor jscolor-active" type="text" name="color" maxlength="6" autocomplete="off" style="color: rgb(255, 255, 255); background-image: none; background-color: rgb(36, 141, 117);">       <input type="submit" id="login-button" name="update" value="Update">
       <span><?php echo $strError; ?></span>
       <span><?php echo $strMessage; ?></span>
</form>
<form class="form" name="form2" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<h4>Change Penguin's Speed</h4>
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
<script src="js/index.js"></script>
<script src="../js/jscolor.js"></script>
<script>
$(document).ready(function()
                  {
                  $("#gtype").change(function()
        {
            if($(this).val() == "null")
        {
            $("#toHide").hide();
        }
        else
        {
            $("#toHide").show();
        }
            });
});

</script>
</html>
