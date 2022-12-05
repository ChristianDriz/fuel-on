<?php

    require_once('assets/db.conn.php');
    require_once('assets/helpers/user.php');
    require_once("assets/classes/dbHandler.php");

    $dbh = new Config();

    $user = getUser($_SESSION['userID'], $conn);
    $userID = $user['userID'];
    $userType = $user['user_type'];

?>
    <link rel="stylesheet" href="assets/css/notification-div.css">
    <li class="nav-item dropdown" id="notif">
        <p class="badge notif"></p>
        <a class="nav-link" data-bs-toggle="dropdown">
            <i class="fas fa-bell"></i>
        </a>
        <div class="dropdown-menu notif-menu">
            <div class="notif-header">
                <p>Order Notifications</p>
            </div>
            <div class="view-all-div">
                <a href="notifications.php">See all</a>
            </div>
            <div class="inside-notif">
            </div>
        </div>
    </li>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

        //for notif counts
        let fetchNotifCount = function(){
      	$.get("assets/ajax/unread_notif_count.php", 
            {
            userID: <?php echo $userID ?>, userType: <?php echo $userType?>
            },
            function(data){
                if (data != 0){
                    $(".notif").html(data);
                }
            });
        }
        fetchNotifCount();
        setInterval(fetchNotifCount, 1000);

        //for notif details
        let fetchNotifDetails = function(){
      	$.get("assets/ajax/notification-div-ajax.php", 
            {
            userID: <?php echo $userID ?>, userType: <?php echo $userType?>
            },
            function(data){
                $(".inside-notif").html(data);
            });
        }
        fetchNotifDetails();
        setInterval(fetchNotifDetails, 1000);
    </script>