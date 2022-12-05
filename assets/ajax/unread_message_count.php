<?php  

session_start();

# check if the user is logged in
if (isset($_SESSION['userID'])) {
	
	# database connection file
	include '../db.conn.php';
	//include 'classes/dbHandler.php';

	# get the logged in user's username from SESSION
	$userID = $_GET['userID'];

	// $dbh = new Config();

	$sql = "SELECT COUNT(opened) 
    FROM tbl_chat_contents 
    WHERE opened = 0 
    AND receiverID = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$userID]);


    if($stmt->rowCount() > 0){ 
        $count = $stmt->fetchColumn();

        echo $count;
    }

}else {
	header("Location: ../../login.php");
	exit;
}

