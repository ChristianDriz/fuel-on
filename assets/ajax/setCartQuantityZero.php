<?php  

session_start();

# check if the user is logged in
if (isset($_SESSION['userID'])) {
    
}else {
	header("Location: ../../login.php");
	exit;
}

include '../db.conn.php';
include '../classes/dbHandler.php';
$dbh = new Config();

// $userID = $_GET['userID'];
$productID = $_GET['productID'];

// $dbh->setCartQuantityOne($productID, $userID);
//echo "<script>document.location='../../customer-cart.php'</script>";


$dbh->setCartQuantityZero($productID, $userID);