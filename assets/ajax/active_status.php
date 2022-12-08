<?php  

session_start();

# check if the user is logged in
if (isset($_SESSION['userID'])) {
	
    date_default_timezone_set('Asia/Manila');
    $date = date("Y-m-d H:i:s");

	# database connection file
	include '../db.conn.php';
	//include 'classes/dbHandler.php';

	# get the logged in user's username from SESSION
	$userID = $_SESSION['userID'];

	// $dbh = new Config();

	// $dbh->updateActive($userID);

	$sql = "UPDATE tbl_users
	        SET active_status = '$date'
	        WHERE userID = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$userID]);

}else {
	header("Location: ../../login.php");
	exit;
}