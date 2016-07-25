<?php

require 'recaptcha/src/autoload.php';
require 'config.php';

function domain_exists($strEmail, $strRecord = 'MX'){
	         list($strUser, $strDomain) = explode('@', $strEmail);
	         return checkdnsrr($strDomain, $strRecord);
}

function sendError($strErr) {
             $strMsg = "<center><h2>Error: " . $strErr . "</h2></center>"; 
             die($strMsg);
}

$resDBCon= mysqli_connect($strDBHost, $strDBUser, $strDBPass, $strDBName) or sendError('Failed to connect to MySQL: ' . mysqli_connect_error());

if (isset($_POST['submit'])) {
    $strUsername = $_POST['username'];
    $strPassword = $_POST['pass'];
    $strPasswordTwo = $_POST['passtwo'];
    $strColor = $_POST['color'];
    $strEmail = $_POST['email'];
    $intPin = $_POST['spin'];
    
    if (empty($strEmail) || empty($strUsername) || empty($strPassword) || empty($strPasswordTwo) || empty($strColor) || empty($intPin)) {
        sendError('One or more fields has not been completed, please complete them');
    }
    
    $strUsername = mysqli_real_escape_string($resDBCon, $strUsername);
    $strPassword = mysqli_real_escape_string($resDBCon, $strPassword);
    $strPasswordTwo = mysqli_real_escape_string($resDBCon, $strPasswordTwo);
    $strColor = mysqli_real_escape_string($resDBCon, $strColor);
    $strEmail = mysqli_real_escape_string($resDBCon, $strEmail);
    $intPin = mysqli_real_escape_string($resDBCon, $intPin);
    
    $strUsername = addslashes($strUsername);
    $strPassword = addslashes($strPassword);
    $strPasswordTwo = addslashes($strPasswordTwo);
    $strColor = addslashes($strColor);
    $strEmail = addslashes($strEmail);
    $intPin = addslashes($intPin);
     
    if (!filter_var($strEmail, FILTER_VALIDATE_EMAIL)) {
        sendError('Invalid email address! Please recheck your email');
    } elseif (!domain_exists($strEmail)) {
        sendError('Invalid domain for email address! Please use a valid domain');
    } elseif (!ctype_alnum($strUsername) && strlen($strUsername) > 10 && strlen($strUsername) <= 3) {
        sendError('Invalid username! Please make sure the username is alphanumeric and not too long or short');
    } elseif (strlen($strColor) > 6) {
        sendError('Invalid color! Please use a valid color');
    } elseif ($strPassword != $strPasswordTwo) {
        sendError('Password does not match! Please make sure the passwords match');
    } elseif (strlen($strPassword) > 15 && strlen($strPassword)  < 5 && strlen($strPasswordTwo) > 15 && strlen($strPasswordTwo) < 5) {
        sendError('Password is either too long or too short');
    } elseif (!is_numeric($intPin) && $intPin < 6 && $intPin > 6) {
        sendError('Invalid pin number, pin must be 6 digits long');
    }
    
    $strColor = '0x' . $strColor;
    
    $arrExistUsers = mysqli_query($resDBCon, "SELECT username FROM users WHERE username = '$strUsername'");
    $intUsers = mysqli_num_rows($arrExistUsers);
    
    if ($intUsers != 0) {
        sendError('Username already exists, please try another name');
    }
    
    $arrExistEmails = mysqli_query($resDBCon, "SELECT email FROM users WHERE email = '$strEmail'");
    $intEmails = mysqli_num_rows($arrExistEmails);
     
    if ($intEmails != 0) {
        sendError('Email is already in use, please try another email');
    }
     
    $strIP = mysqli_real_escape_string($resDBCon, $_SERVER['REMOTE_ADDR']);
     
    $arrExistIPS = mysqli_query($resDBCon, "SELECT ipAddr FROM users WHERE ipAddr = '$strIP'");
    $intIPS = mysqli_num_rows($arrExistIPS);
     
    if ($intPS >= 2) {
        sendError('You cannot create more than two accounts using this IP');
    }
     
    $strMD5 = md5($strPassword);
     
    $recaptcha = new \ReCaptcha\ReCaptcha($strSecretKey);
    $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $strIP);
     
    if (!$resp->isSuccess()) {
        sendError('You are a bot, get the fuck out');
    } else {
        $resQuery = mysqli_query($resDBCon, "INSERT INTO users (`username`, `nickname`, `email`, `password`, `colour`,  `ipAddr`, `stamps`, `spin`) VALUES ('" . $strUsername . "', '" . $strUsername . "', '" . $strEmail . "', '" . $strMD5 . "', '" . $strColor . "', '" . $strIP . "', '31|7|33|8|32|35|34|36|290|358|448', '" . $intPin . "')");
        $intPID = mysqli_insert_id($resDBCon);
        mysqli_query($resDBCon, "INSERT INTO igloos (`ID`, `username`) VALUES ('" . $intPID . "', '" . $strUsername . "')");
        mysqli_query($resDBCon, "INSERT INTO postcards (`recepient`, `mailerID`, `mailerName`, `postcardType`) VALUES ('" . $intPID . "', '0', 'Luna', '125')");
        echo "<center><h2>You have successfully registered with Luna, $strUsername ! You may now login to the game :-)</h2></center>";
    }
    
} else {}
	
?>