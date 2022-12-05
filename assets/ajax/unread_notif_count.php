<?php  

session_start();

# check if the user is logged in
if (isset($_SESSION['userID'])) {
	
}else {
	header("Location: ../../login.php");
	exit;
}

	include '../db.conn.php';
	include("../classes/dbHandler.php");
	$dbh = new Config();

	$userID = $_GET['userID'];
	$userType = $_GET['userType'];

	$countnotif = $dbh->countUserUnreadNotif($userID, $userType);
	echo $countnotif;
