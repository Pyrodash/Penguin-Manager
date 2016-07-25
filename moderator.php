<?php

include 'session.php';

if ($_SESSION['isStaff'] == false) { 
    header('location: index.php');
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Penguin - Moderator Panel</title>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
</head>
<body>

<div class="overlay">
<ul>
<div class="default-li">
<li><a href="home.php"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
<li><a href="settings.php"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
<li><a href="search.php"><i class="fa fa-search" aria-hidden="true"></i> Search</a></li>
<li><a href="glows.php"><i class="fa fa-wrench" aria-hidden="true"></i> Glow Panel</a></li>
<?php
if ($_SESSION['isStaff'] == true) { 
?>
<li><a class="active" href="moderator.php">Mod Panel</a></li>
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
<?php

include '../config.php';

$strError = '';
$strMessage = '';

$mysql = mysqli_connect($strDBHost, $strDBUser, $strDBPass, $strDBName);

if (isset($_POST['submit'])) {
    $strUsername = $_POST['username'];
    $intAction = $_POST['action_type'];
    if (isset($strUsername) && isset($intAction)) {
        $strUsername = mysqli_real_escape_string($mysql, $strUsername);
        $intAction = mysqli_real_escape_string($mysql, $intAction);
        $strUsername = addslashes($strUsername);
        $intAction = addslashes($intAction);
        $resQuery = mysqli_query($mysql, "SELECT username FROM users WHERE username = '$strUsername'");
        $intPExists = mysqli_num_rows($resQuery);
        if ($intPExists == 1) {
            $resQueryTwo = mysqli_query($mysql, "SELECT * FROM users WHERE username = '$strUsername'");
            $arrResult = mysqli_fetch_assoc($resQueryTwo);
            if ($arrResult['rank'] < 3) {
                switch ($intAction) {
                           case 0:
                           if ($arrResult['isBanned'] != 'PERM') {
                               if (!is_numeric($arrResult['isBanned'])) {                      
                                   if ($arrResult['isBanned'] < time()) {
                                       mysqli_query($mysql, "UPDATE users SET isBanned = 'PERM' WHERE username = '$strUsername'");
                                       $strMessage = "You have permanently banned $strUsername";
                                   }
                              } else {
                                  $intRemainingTime = round(($arrResult['isBanned'] - time()) / 3600);
                                  $strError = "This user is already banned for the next $intRemainingTime hours";
                              }
                          } else {
                              $strError = "This user is already banned permanently";
                          }
                          break;
                          case 1:
                          if ($arrResult['isBanned'] != '') {
                              mysqli_query($mysql, "UPDATE users SET isBanned = '' WHERE username = '$strUsername'");
                              $strMessage = "You have successfully unbanned $strUsername";
                          } else {
                              $strError = "This user is already unbanned";
                          }
                          break;
                          case 2:
                          if ($arrResult['isBanned'] != 'PERM') {
                              if (!is_numeric($arrResult['isBanned'])) {                      
                                  if ($arrResult['isBanned'] < time()) {
                                      mysqli_query($mysql, "UPDATE users SET isBanned = '" . (time() + 86400)  . "' WHERE username = '$strUsername'");
                                      $strMessage = "You have banned $strUsername for 24 hours";
                                  }
                              } else {
                                  $intRemainingTime = round(($arrResult['isBanned'] - time()) / 3600);
                                  $strError = "This user is already banned for the next $intRemainingTime hours";
                              }
                          } else {
                              $strError = "This user is already banned permanently";
                          }
                          break;
                          case 3:
                          if ($arrResult['isBanned'] != 'PERM') {
                              if (!is_numeric($arrResult['isBanned'])) {                      
                                  if ($arrResult['isBanned'] > time()) {
                                      mysqli_query($mysql, "UPDATE users SET isBanned = '" . (time() + 172800)  . "' WHERE username = '$strUsername'");
                                      $strMessage = "You have banned $strUsername for 48 hours";
                                  }
                              } else {
                                  $intRemainingTime = round(($arrResult['isBanned'] - time()) / 3600);
                                  $strError = "This user is already banned for $intRemainingTime hours";
                              }
                          } else {
                              $strError = "This user is already banned permanently";
                          }
                          break;
                          case 4:
                          if ($arrResult['isBanned'] != 'PERM') {
                              if (!is_numeric($arrResult['isBanned'])) {                      
                                  if ($arrResult['isBanned'] > time()) {
                                      mysqli_query($mysql, "UPDATE users SET isBanned = '" . (time() + 259200)  . "' WHERE username = '$strUsername'");
                                      $strMessage = "You have banned $strUsername for 72 hours";
                                  }
                              } else {
                                  $intRemainingTime = round(($arrResult['isBanned'] - time()) / 3600);
                                  $strError = "This user is already banned for $intRemainingTime hours";
                              }
                          } else {
                              $strError = "This user is already banned permanently";
                          }
                          break;
                          case 5:
                          if ($arrResult['isMuted'] != true) {
                              mysqli_query($mysql, "UPDATE users SET isMuted = '1' WHERE username = '$strUsername'");
                              $strMessage = "You have muted $strUsername";
                          } else {
                              $strError = "$strUsername is already muted";
                          }
                          break;
                          case 6:
                          if ($arrResult['isMuted'] != false) {
                              mysqli_query($mysql, "UPDATE users SET isMuted = '0' WHERE username = '$strUsername'");
                              $strMessage = "You have unmuted $strUsername";
                          } else {
                              $strError = "$strUsername is already unmuted";
                          }
                          break;
                }
            }
        } else {
            $strError = "$strUsername does not exist in the database";
        }
    } else {
        $strError = "Please enter a username and select an action";
    }
}

?>

<form class="form" name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
       <input type="text" name="username" maxlength="10" placeholder="Type a username">
       <div class="select">
       <span class="arr"></span>
       <select name="action_type" id="action_type">
                <option value="0">Ban</option>
                <option value="1">Unban</option>
                <option value="2">Time Ban - 24h</option>
                <option value="3">Time Ban - 48h</option>
                <option value="4">Time Ban - 72h</option>
                <option value="5">Mute</option>
                <option value="6">Unmute</option>
       </select>
       </div>
       <br>
       <br>
       <input type="submit" id="login-button" name="submit" value="Submit">
       <span><?php echo $strError; ?></span>
       <span><?php echo $strMessage; ?></span>
</form>
</div>
</div>

</div>
<div class="footer">Designed by Jack. Coded by Lynx.</div> <!--Please don't remove this line.-->
</div>
</body>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/index.js"></script>
<script src="index.js"></script>
</html>
