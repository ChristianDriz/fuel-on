<?php
require_once('../classes/dbHandler.php');

session_start();

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
}

$dbh = new Config();

// Check is select all per store
if (isset($_POST['select-all-shop'])) {
    $shopId = $_POST['select-all-shop-id'];
    $dbh->selectAllFromCart($shopId, $userID, 1, 'store');
}
else{
    $shopId = $_POST['select-all-shop-id'];
    $dbh->selectAllFromCart($shopId, $userID, 0, 'store');
}
echo "<script>document.location='../../customer-cart.php'</script>";