<?php 

function getConversation($userid, $conn){
    /**
      Getting all the conversations 
      for current (logged in) user
    **/
    $sql = "SELECT * FROM tbl_chats
            WHERE user_1 = ? OR user_2 = ?
            ORDER BY chatID DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$userid, $userid]);

    if($stmt->rowCount() > 0){
        $conversations = $stmt->fetchAll();

        /**
          creating empty array to 
          store the user conversation
        **/
        $user_data = [];
        
        # looping through the conversations
        foreach($conversations as $conversation){
            # if conversations user_1 row equal to userid
            if ($conversation['user_1'] == $userid) {
            	$sql2  = "SELECT *
            	          FROM tbl_users WHERE userID = ?";
            	$stmt2 = $conn->prepare($sql2);
            	$stmt2->execute([$conversation['user_2']]);

            }else {
            	$sql2  = "SELECT *
            	          FROM tbl_users WHERE userID = ?";
            	$stmt2 = $conn->prepare($sql2);
            	$stmt2->execute([$conversation['user_1']]);

            }

            $allConversations = $stmt2->fetchAll();

            # pushing the data into the array 
            array_push($user_data, $allConversations[0]);
        }

        return $user_data;

    }else {
    	$conversations = [];
    	return $conversations;
    }  

}