<?php
session_start();
if(isset($_SESSION['userID'])){
    $customerID = $_SESSION['userID'];
}
else{
    $customerID = NULL;
    //header('location: index.php');
}

require_once('../classes/dbHandler.php');
$dbh = new Config();

if($customerID != NULL){
    $dbh->deleteAllinCart($customerID);
    $dbh->success("../../customer-cart.php", "Product removed successfully!");
}

