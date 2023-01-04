<?php
session_start();

require_once("../classes/dbHandler.php");
$dbh = new Config();

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
}

//check per product
if (isset($_POST['prodID'])) {
    $prodID = $_POST['prodID'];

    $checked = $_POST['checkbox'] == 'true' ? 1 : 0;
    $dbh->selectAllFromCart($checked, '', $prodID, $userID, 'product');
}
//check all products per shop
elseif (isset($_POST['shopID'])) {
    $shopID = $_POST['shopID'];

    $checked = $_POST['checked'] == 'true' ? 1 : 0;
    $dbh->selectAllFromCart($checked, $shopID, '', $userID, 'store');
}
//check all
else{
    $checked = $_POST['checked'] == 'true' ? 1 : 0;
    $dbh->selectAllFromCart($checked, '', '', $userID, 'all');
}

// $cart = $dbh->cartAllChecked($userID);
// echo $checked;
