<?php
session_start();

# check if the user is logged in
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $userType = $_SESSION['userType'];

    require_once '../classes/dbHandler.php';
    $dbh = new Config();
    
    $orderID = $_GET['orderID'];
    $notifDate = $_GET['time'];

    echo $dbh->updateNotifReadStatus($orderID, $notifDate);


}else {
	header("Location: ../../login.php");
	exit;
}
