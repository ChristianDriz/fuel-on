<?php 

function opened($id_1, $conn, $chats){
    foreach ($chats as $chat) {
    	if ($chat['opened'] == 0) {
    		$opened = 1;
    		$chat_id = $chat['convoID'];

			
    		$sql = "UPDATE tbl_chat_contents
    		        SET opened = ?
    		        WHERE senderID = ? 
    		        AND convoID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$opened, $id_1, $chat_id]);

    	}
    }
}