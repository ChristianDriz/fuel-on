<?php
class Notifications
{
    const TYPE_NEW_ORDER = 0;
    const TYPE_CANCEL_ORDER = 1;
    // const TYPE_ACCEPT_ORDER = 2; 
    const TYPE_DECLINE_ORDER = 3;
    const TYPE_APPROVE_ORDER = 4;
    const TYPE_RECEIVED_ORDER = 5;

    public function getTypeDesc($notification)
    {
        $type = $notification['type'];
        $userName = $notification['firstname'] . ' ' . $notification['lastname'];
        $shopName = $notification['shopname'];
        $orderID = $notification['orderID'];
        $msg = "";
        switch ($type) {
            case $this::TYPE_NEW_ORDER:
                $msg = "You received a new order from $userName";
                break;
            case $this::TYPE_CANCEL_ORDER:
                $msg = "$userName cancelled the order";
                break;
            case $this::TYPE_APPROVE_ORDER:
                $msg = "$shopName approved your order";
                break;
            case $this::TYPE_DECLINE_ORDER:
                $msg = "$shopName declined your order";
                break;
            case $this::TYPE_RECEIVED_ORDER:
                $msg = "$userName received their order";
                break;
        }
        return $msg;
    }


    //to change the notif time like facebook
    public function NotifTime($timestamp)  
    {  
        date_default_timezone_set('Asia/Manila');
        $time_ago = strtotime($timestamp);  
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
                if($minutes == 1)  
                {  
                    return "a minute ago";  
                }  
                else  
                {  
                    return "$minutes minutes ago";  
                }  
            } 

            //Hours
            else if($hours <=24)  
            {  
                if($hours == 1)  
                {  
                    return "an hour ago";  
                }  
                else  
                {  
                    return "$hours hours ago";  
                }  
            }  

            //days
            else if($days <= 7)  
            {  
                if($days == 1)  
                {  
                    return "yesterday";  
                }  
                else  
                {  
                    return "$days days ago";  
                }  
            }  

            //weeks
            else if($weeks <= 4.3) //4.3 == 52/12  
            {  
                if($weeks==1)  
                {  
                    return "a week ago";  
                }  
                else  
                {  
                    return "$weeks weeks ago";  
                }  
            }  

            //months
            else if($months <=12)  
            {  
                if($months == 1)  
                {  
                    return "a month ago";  
                }  
                else  
                {  
                    return "$months months ago";  
                }  
            }  

            //year
            else  
            {  
                if($years == 1)  
                {  
                    return "one year ago";  
                }  
                else  
                {  
                    return "$years years ago";  
                }  
            }  
        }
    }
