<?php 

    function lastChat($id_1, $id_2, $conn){
    
        $sql = "SELECT * FROM tbl_chat_contents
            WHERE (senderID = ? AND receiverID = ?)
            OR    (receiverID = ? AND senderID = ?)
            ORDER BY convoID DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_1, $id_2, $id_1, $id_2]);

        return $stmt->fetchAll();
    }

    function sentTime($datetime)
    {
        date_default_timezone_set('Asia/Manila');
        $time_ago = strtotime($datetime);  
        $current_time = time();  
        $time_difference = $current_time - $time_ago;  
        $seconds = $time_difference;  
        $minutes = round($seconds / 60 ); // value 60 is seconds  
        $hours = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec  
        $days = round($seconds / 86400); //86400 = 24 * 60 * 60;  
        $weeks = round($seconds / 604800); // 7*24*60*60;  
        $months = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60  
        $years = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60  

        //Just now
        if($seconds <= 60)  
        {  
            return "Just Now";  
        }  

        // minutes
        else if($minutes <= 60)  
        {  
            return $minutes."m";  
        } 

        //Hours
        else if($hours <= 24)  
        {  
            return $hours."h";  
        }  

        //days
        else if($days <= 7)  
        {  
            return $days."d";  
        }  

        //weeks
        else{
            return $weeks."w";
        }
    }
