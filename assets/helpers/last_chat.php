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
        if ($past->s < 59) {
            return 'Just Now';
        }
    }
