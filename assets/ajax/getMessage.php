<?php 

session_start();

# check if the user is logged in
if (isset($_SESSION['userID'])) {

	if (isset($_POST['id_2'])) {
	
	# database connection file
	include '../db.conn.php';
	// include '../classes/dbHandler.php';

	// $conn = new DBHandler();

	$id_1  = $_SESSION['userID'];
	$id_2  = $_POST['id_2'];
	$opend = 0;

	$sql = "SELECT * FROM tbl_chat_contents
	        WHERE receiverID = ?
	        AND   senderID = ?
	        ORDER BY convoID ASC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id_1, $id_2]);

	
	define('TIMEZONE', 'Asia/Manila');
	date_default_timezone_set(TIMEZONE);

	if ($stmt->rowCount() > 0) {
	    $chats = $stmt->fetchAll();

	    # looping through the chats
	    foreach ($chats as $chat) {

			$time = $chat['created_at'];
			$newtime = date("h:i A");

	    	if ($chat['opened'] == 0) {
	    		$opened = 1;
	    		$chat_id = $chat['convoID'];

	    		$sql2 = "UPDATE tbl_chat_contents
	    		         SET opened = ?
	    		         WHERE convoID = ?";
	    		$stmt2 = $conn->prepare($sql2);
	            $stmt2->execute([$opened, $chat_id]); 
	            ?>
                  	<div class="incoming-chat">
                        <p class="message"><?=$chat['message']?></p>
                        <p class="send-date"><?=$newtime?></p>
                    </div>
	            <?php
	    	}
	    }
	}

 }

}else {
	header("Location: ../../index.php");
	exit;
}