<?php

include 'config.php';

session_start();

$strError = '';

$mysql = mysqli_connect($strDBHost, $strDBUser, $strDBPass, $strDBName);

if (isset($_POST['submit'])) {
    $strName = $_POST['username'];
    $strPass = $_POST['password'];
    $intPin = $_POST['spin'];
    if (empty($strName) || empty($strPass) || empty($intPin)) {
        $strError = 'Please fill in all the information';
    } else {      
        $strName = mysqli_real_escape_string($mysql, $strName);
        $strPass = mysqli_real_escape_string($mysql, $strPass);
        $intPin = mysqli_real_escape_string($mysql, $intPin);
        $strName = addslashes($strName);
        $strPass = addslashes($strPass);
        $intPin = addslashes($intPin);
        $strPass = md5($strPass);
        $resQuery = mysqli_query($mysql, "SELECT username FROM users WHERE username = '$strName' AND password = '$strPass'");
        $intRows = mysqli_num_rows($resQuery);
        if ($intRows == 1) {
            $resQueryTwo = mysqli_query($mysql, "SELECT * FROM users WHERE username = '$strName'");
            $arrInfo = mysqli_fetch_assoc($resQueryTwo);
            if ($arrInfo['spin'] == $intPin) {
                $_SESSION['login_user'] = $strName;
                $_SESSION['ID'] = $arrInfo['ID'];
                $_SESSION['isStaff'] = $arrInfo['isStaff'];
                $_SESSION['isAdmin'] = $arrInfo['isAdmin'];
                header('location: profile.php');
            } else {
                $strError = 'Secret pin is invalid';
            }
        } else {
            $strError = 'Username or Password is invalid';
        }
   }
}
?>
