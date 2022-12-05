<?php
session_start();
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION["userID"];
    $username = $_SESSION["fname"];
    $userpic = $_SESSION["userPic"];
    $userType = $_SESSION["userType"];
} else {
    header('location: index.php');
}

    require_once("assets/classes/dbHandler.php");
    require_once("assets/classes/Notifications.php");

    $data = new Config();
    $notificationClass = new Notifications();
        
?>
    <div class="unread-list"></div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {

            let fetchUnreadNotifs = function(){
            $.get("assets/ajax/notification-unread-ajax.php", 
                {
                userID: <?php echo $userID ?>, userType: <?php echo $userType?>
                },
                function(data){
                    $(".unread-list").html(data);
                });
            }
            fetchUnreadNotifs();
            setInterval(fetchUnreadNotifs, 1000);
        });
    </script>
