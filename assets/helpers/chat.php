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

// function time_elapsed_string($datetime)
// {
//     date_default_timezone_set('Asia/Manila');
//     $time_ago = strtotime($datetime);  
//     $current_time = time();  
//     $time_difference = $current_time - $time_ago;  
//     $seconds = $time_difference;  
//     $minutes = round($seconds / 60 ); // value 60 is seconds  
//     $hours = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec  
//     $days = round($seconds / 86400); //86400 = 24 * 60 * 60;  
//     $weeks = round($seconds / 604800); // 7*24*60*60;  
//     $months = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60  
//     $years = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60  


//     if ($hours <= 24){
//         $createdate = date_create($datetime);
//         $new_date = date_format($createdate, "h:i A");

//         return $new_date;
//     }
//     else {
//         $createdate = date_create($datetime);
//         $new_date = date_format($createdate, "F d, Y h:i A");

//         return $new_date;
//     }
// }

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