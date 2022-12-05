<?php 

function getChats($id_1, $id_2, $conn){
   
   $sql = "SELECT *, DATE_FORMAT(created_at, '%b %d %Y, %h:%i %p') AS Date FROM `tbl_chat_contents` 
           WHERE (senderID = ? AND receiverID = ?)
           OR    (receiverID = ? AND senderID = ?)
           ORDER BY convoID ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_1, $id_2, $id_1, $id_2]);

    if ($stmt->rowCount() > 0) {
    	$chats = $stmt->fetchAll();
    	return $chats;
    }else {
    	$chats = [];
    	return $chats;
    }
}

function time_elapsed_string($datetime)
{
    date_default_timezone_set('Asia/Manila');
    $time = strtotime($datetime);
    $nt = date("Y/m/d H:i:s", $time);
    $posted = new DateTime($nt);
    $current = new DateTime("NOW");
    $past = $posted->diff($current);
    if ($past->y > .9) {
        return '' . date('M d, Y', $time);
    }
    if ($past->d > .9) {
        return '' . date('M d', $time);
    }
    if ($past->h > .9) {
        return '' . $past->h . 'h';
    }
    if ($past->i > .9) {
        return '' . $past->i . 'm';
    }
    // if ($past->s < 59) {
    //     return 'Just Now';
    // }
    if ($past->s < 59) {
        return '' . date("h:i A");
    }
}