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
<title>Penguin - Admin Panel</title>
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
<li><a href="moderator.php">Mod Panel</a></li>
<?php if ($_SESSION['isAdmin'] == true) { ?>
<li><a class="active" href="admin.php">Admin Panel</a></li>
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

$strMessage = '';
$strMessageTwo = '';

$strError = '';
$strErrorTwo = '';

$mysql = mysqli_connect($strDBHost, $strDBUser, $strDBPass, $strDBName);

if (isset($_POST['update_rank'])) {
    $strUsername = $_POST['username'];
    $intRank = $_POST['rank'];
    if (isset($strUsername) && isset($intRank)) {
        $strUsername = mysqli_real_escape_string($mysql, $strUsername);
        $intRank = mysqli_real_escape_string($mysql, $intRank);
        $strUsername = addslashes($strUsername);
        $intRank = addslashes($intRank);
        $resQuery = mysqli_query($mysql, "SELECT username FROM users WHERE username = '$strUsername'");
        $intPExists = mysqli_num_rows($resQuery);
        if ($intPExists == 1) {
            $arrRanks = array(
                1 => 'Member',
                2 => 'VIP',
                3 => 'Mediator',
                4 => 'Moderator',
                5 => 'Administrator',
                6 => 'Owner'
           );
           $arrStaffRanks = array(
                3 => 'Mediator',
                4 => 'Moderator',
                5 => 'Administrator',
                6 => 'Owner'
           );
           $strRank = $arrRanks[$intRank];
           if (array_key_exists($intRank, $arrStaffRanks)) {
               mysqli_query($mysql, "UPDATE users SET rank = '$intRank' WHERE username = '$strUsername'");
               mysqli_query($mysql, "UPDATE users SET isStaff = '1' WHERE username = '$strUsername'");
           } else {
               mysqli_query($mysql, "UPDATE users SET rank = '$intRank' WHERE username = '$strUsername'");
           }
           $strMessage = "You have successfuly updated $strUsername rank to $strRank";
        } else {
            $strError = "$strUsername does not exist in the database";
        }
    } else {
        $strError = "Please enter a username and select a rank";
    }
}

if (isset($_POST['update_active'])) {
    $strUsernameTwo = $_POST['username_two'];
    $intAction = $_POST['action_type'];
    if (isset($strUsernameTwo) && isset($intAction)) {
        $strUsernameTwo = mysqli_real_escape_string($mysql, $strUsernameTwo);
        $intAction = mysqli_real_escape_string($mysql, $intAction);
        $strUsernameTwo = addslashes($strUsernameTwo);
        $intAction = addslashes($intAction);
        $resQueryTwo = mysqli_query($mysql, "SELECT username FROM users WHERE username = '$strUsernameTwo'");
        $intPExistsTwo = mysqli_num_rows($resQueryTwo);
        if ($intPExistsTwo == 1) {
            switch ($intAction) {
                       case 0:
                            mysqli_query($mysql, "UPDATE users SET active = '0' WHERE username = '$strUsernameTwo'");
                            $strMessageTwo = "You have deactivated $strUsernameTwo account";
                       break;
                       case 1:
                            mysqli_query($mysql, "UPDATE users SET active = '1' WHERE username = '$strUsernameTwo'");
                            $strMessageTwo = "You have activated $strUsernameTwo account";
                       break;
            }
        } else {
            $strErrorTwo = "$strUsernameTwo does not exist in the database";
        }
    } else {
        $strErrorTwo  = "Please enter a username and select an action";
    }
}

?>

<form class="form" name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
       <input type="text" name="username" maxlength="10" placeholder="Type a username">
       <div class="select">
       <span class="arr"></span>
       <select name="rank" id="rank">
                <option value="1">Member</option>
                <option value="2">VIP</option>
                <option value="3">Mediator</option>
                <option value="4">Moderator</option>
                <option value="5">Administrator</option>
       </select>
       </div>
       <br>
       <br>
       <input type="submit" id="login-button" name="update_rank" value="Update Rank">
       <span><?php echo $strMessage; ?></span>
       <span><?php echo $strError; ?></span>
</form>         
<br>
<br>
<form class="form" name="form2" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
       <input type="text" name="username_two" maxlength="10" placeholder="Type a username">
       <div class="select">
       <span class="arr"></span>
       <select name="action_type" id="action_type">
                <option value="0">Deactivate</option>
                <option value="1">Activate</option>
       </select>
       </div>
       <br>
       <br>
       <input type="submit" id="login-button" name="update_active" value="Update Active">
       <span><?php echo $strMessageTwo; ?></span>
       <span><?php echo $strErrorTwo; ?></span>
</form>         
</div>
</div>

</div>
<div class="footer">Designed by Jack. Coded by Lynx.</div> <!--Please don't remove this line.-->
</div>
</body>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/index.js"></script>
</html>
