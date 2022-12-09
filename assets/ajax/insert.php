<?php 
session_start();

// setting up the time Zone
// It Depends on your location or your P.c settings
define('TIMEZONE', 'Asia/Manila');
date_default_timezone_set(TIMEZONE);

$date = date("Y-m-d H:i:s");

# check if the user is logged in
if (isset($_SESSION['userID'])) {

	if (isset($_POST['message']) &&
        isset($_POST['to_id'])) {
	
	# database connection file
	include '../db.conn.php';
	include '../classes/dbHandler.php';
	// $conn = new DBHandler();
	$dbh = new Config();

	# get data from XHR request and store them in var
	$message = $_POST['message'];
	$to_id = $_POST['to_id'];

	# get the logged in user's username from the SESSION
	$from_id = $_SESSION['userID'];

	$sql = "INSERT INTO 
	       tbl_chat_contents (senderID, receiverID, message, created_at) 
	       VALUES (?, ?, ?, ?)";
	$stmt = $conn->prepare($sql);
	$res  = $stmt->execute([$from_id, $to_id, $message, $date]);

	//to update chat time sa tbl_chats
	$dbh->updateChatTime($date, $from_id, $to_id);

    
    # if the message inserted
    if ($res) {
    	/**
       check if this is the first
       conversation between them
       **/
       $sql2 = "SELECT * FROM tbl_chats
               WHERE (user_1 = ? AND user_2 = ?)
               OR    (user_2 = ? AND user_1 = ?)";
       $stmt2 = $conn->prepare($sql2);
	   $stmt2->execute([$from_id, $to_id, $from_id, $to_id]);

		$time = date("h:i A");

		if ($stmt2->rowCount() == 0 ) {
			# insert them into conversations table 
			$sql3 = "INSERT INTO 
			        tbl_chats(user_1, user_2)
			        VALUES (?,?)";
			$stmt3 = $conn->prepare($sql3); 
			$stmt3->execute([$from_id, $to_id]);
			$dbh->updateChatTime($date, $from_id, $to_id);
		}
		?>
        <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-messages.css">
		<div class="outgoing-chat">
            <p class="message user-chat"><?=$message?></p>
            <p class="send-date"><?=$time?></p>
        </div>

    <?php 
     }
  }
}else {
	header("Location: ../../login.php");
	exit;
}