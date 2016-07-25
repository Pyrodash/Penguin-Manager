<?php

include 'config.php';

session_start();

$strUsername = $_SESSION['login_user'];

$mysql = mysqli_connect($strDBHost, $strDBUser, $strDBPass, $strDBName);

$resQuery = mysqli_query($mysql, "SELECT username FROM users WHERE username = '$strUsername'");

$arrResults = mysqli_fetch_assoc($resQuery);

$resSession = $arrResults['username'];

if (!isset($resSession)) {
    mysqli_close($mysql);
    header('Location: index.php');
}

?>
