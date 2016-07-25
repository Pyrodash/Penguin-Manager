<?php

require '.../session.php';

$username = $_SESSION['login_user'];

echo "<center><h2>Welcome, $username</h2></center>";
echo "<center><h2>Payment Canceled</h2></center>";

?>