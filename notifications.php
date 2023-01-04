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

    $dbh = new Config();
    $notificationClass = new Notifications();

    $notif = $dbh->getUsersAllNotif($userID, $userType);
    
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | Order Notifications</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-navigation.css">
    <link rel="stylesheet" href="assets/css/notification.css">
</head>

<body>
    <?php
        //top navigation
        include 'top-navigation.php';
    ?>
    <div id="wrapper">
        <?php
            //side navigation
            include 'side-navigation.php';
        ?>
        <div class="page-content-wrapper">
            <div class="container notif-container">
                <div class="notif-heading">
                    <h3>Order Notifications</h3>
                </div>
                    <div class="filter-notif-div">
                        <a class="btn filter-btn actives" href="notifications.php" type="button">All</a>
                        <a class="btn filter-btn unread" type="button">Unread</a>
                    </div>
                    <div class="boxx">
                        <div class="notif-list"></div>
                    </div> 
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/editproduct.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <script>
        $(document).ready(function () {
       
            var filter = document.querySelectorAll(".filter-btn");

            filter.forEach(button => {
                button.addEventListener("click",()=> {
                    resetActive();
                    button.classList.add("actives");
                })
            })

            function resetActive(){
                filter.forEach(button => {
                    button.classList.remove("actives");
                })
            }

            let fetchAllNotifs = function(){
            $.get("assets/ajax/notification-ajax.php", 
                {
                userID: <?php echo $userID ?>, userType: <?php echo $userType?>
                },
                function(data){
                    $(".notif-list").html(data);
                });
            }
            fetchAllNotifs();
            setInterval(fetchAllNotifs, 1000);

            
            $('.unread').click(function () { 
                $.ajax({
                    type: "GET",
                    url: "notifications-filter.php",
                    beforeSend:function(){
                        $(".boxx").html("<span>Loading...</span>");
                    },
                    success:function(data){
                        $(".boxx").html(data);
                    }
                });
            });

        //for last seen update
        let lastSeenUpdate = function(){
      	$.get("assets/ajax/active_status.php");
        }
        lastSeenUpdate();
        setInterval(lastSeenUpdate, 1000);

        //for message notif
        let fetchMessageNotif = function(){
      	$.get("assets/ajax/unread_message_count.php", 
            {
            userID: <?php echo $userID ?>
            },
            function(data){
                if (data != 0){
                    $(".message-counter").html(data);
                }
            });
        }
        fetchMessageNotif();
        //auto update every .5 sec
        setInterval(fetchMessageNotif, 500);

        });

    </script>
</body>

</html>