<?php
session_start();

require_once('../classes/dbHandler.php');
$dbh = new Config();

if(isset($_SESSION['userID'])){
    $customerID = $_SESSION['userID'];
}

if(isset($_GET['type'])){
    $type = $_GET['type'];
}

if ($type == 'checked'){
    $dbh->deleteAllinCart($customerID, 'checked');
    $dbh->success("../../customer-cart.php", "Product removed successfully!");
}
elseif ($type == 'no-stock'){
    $dbh->deleteAllinCart($customerID, 'no-stock');
    $dbh->success("../../customer-cart.php", "Product removed successfully!");
}
