<?php

        
        $datetime = "2022-12-08 01:12:00";
        
        $date = $datetime;
        $createdate = date_create($date);
        $new_date = date_format($createdate, "F d, Y at h:i A");

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


        if ($hours <= 24){
            echo $new_date = date_format($createdate, "h:i A");
        }
        else {
            echo $new_date = date_format($createdate, "F d, Y h:i A");
        }