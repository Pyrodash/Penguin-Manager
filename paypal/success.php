<?php

require '.../session.php';
require '.../config.php';

$mysql = mysqli_connect($strDBHost, $strDBUser, $strDBPass, $strDBName);

$uid = $_SESSION['ID'];
$username = $_SESSION['login_user'];

$item_no = $_GET['item_number'];
$item_transaction = $_GET['tx']; 

if (isset($item_transaction)) { 
    $item_no = mysqli_real_escape_string($mysql, $item_no);
    $item_transaction = mysqli_real_escape_string($mysql, $item_transaction);
    
    $item_no = addslashes($item_no);
    $item_transaction = addslashes($item_transaction);
    
    $request = curl_init();
    curl_setopt_array($request, 
                               array(
                                      CURLOPT_URL => 'https://www.paypal.com/cgi-bin/webscr', 
                                      CURLOPT_POST => TRUE, 
                                      CURLOPT_POSTFIELDS => http_build_query(
                                      array(
                                            'cmd' => '_notify-synch', 
                                            'tx' => $item_transaction, 
                                            'at' => $identity_token
                                      )),
                                      CURLOPT_RETURNTRANSFER => TRUE,
                                      CURLOPT_HEADER => FALSE
                               )
    );
    $payment_response = curl_exec($request);
    curl_close($request);
    
    $payment_response = substr($payment_response, 7);
    $payment_response = urldecode($payment_response);
    preg_match_all('/^([^=\s]++)=(.*+)/m', $payment_response, $message, PREG_PATTERN_ORDER);
    $payment_response = array_combine($message[1], $message[2]);
    if (isset($payment_response['charset']) && strtoupper($payment_response['charset']) !== 'UTF-8') {
       foreach($payment_response as $key => $value) {
                   $value = mb_convert_encoding($value, 'UTF-8', $payment_response['charset']);
       }
       $payment_response['charset_original'] = $payment_response['charset'];
       $payment_response['charset'] = 'UTF-8';
   }
   ksort($payment_response);

   if (strtoupper($payment_response['payment_status']) == 'COMPLETED') {
       $result = mysqli_query($mysql, "INSERT INTO sales (`pid`, `uid`, `saledate`, `transactionid`) VALUES ('" . $item_no . "', '" . $uid . "', NOW(), '" . $item_transaction . "')");
       $intPaymentID = mysqli_insert_id($mysql);
       mysqli_query($mysql, "UPDATE users SET isVIP = '1' WHERE username = '$username'");
       mysqli_query($mysql, "UPDATE users SET rank = '2' WHERE username = '$username'");
       echo "<center><h2>Welcome, $username</h1></center>";
       echo "<center><h2>Payment Successful</h1></center>";
       echo "<center><p>Your Payment ID - " . $intPaymentID . "</p></center>";
       echo "<center><p>Your rank has been successfully upgraded to VIP</p></center>";
   } else {
       echo "<center><h2>Payment Failed</h2></center>";
   }
}

?>