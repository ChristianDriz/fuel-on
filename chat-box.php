<?php
    session_start();
    if(isset($_SESSION['userID'])){
        $userID = $_SESSION["userID"];
        $username = $_SESSION["fname"];
        $userpic = $_SESSION["userPic"];
        $userType = $_SESSION["userType"];
    }
    else{
        header('location: index.php');
    }

    include 'assets/db.conn.php';
    // include 'assets/helpers/user.php';
    include 'assets/helpers/chat.php';
    include 'assets/helpers/opened.php';
    include 'assets/helpers/timeAgo.php';

    require_once("assets/classes/dbHandler.php");
    $dbh = new Config();

    //to get the usertype of the user chat's with
    if (isset($_GET['userID']) && isset($_GET['userType'])) {
        $ID = $_GET['userID'];
        $type = $_GET['userType'];
        
        if($type == 1 || $type == 0){
            $chats = $dbh->oneUser($ID);
        }
        else{
            $chats = $dbh->shopDetails($ID);
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | Chatbox</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-messages.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-navigation.css">
</head>

<body>
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
            <?php
                if(empty($chats)){
                    include 'no-data.php';
                }else{
                    $chatWith = $chats[0];
                    $chats = getChats($userID, $chatWith['userID'], $conn);
                    opened($chatWith['userID'], $conn, $chats);
            ?>
            <div class="container chat-container">
                <div class="row chat-row">
                    <div class="col-lg-8 mx-auto card" id="convo">
                        <div class="row head">
                            <div class="col-10 name-sender">
                                <div class="back-button">
                                    <a class="back"><i class="fas fa-arrow-left"></i></a>
                                </div>
                                <div class="image-div"><img src="assets/img/profiles/<?=$chatWith['user_image']?>"></div>
                                <div>
                                    <?php
                                        //if the chatWith usertype is 2, the station name and branch name is used
                                        if($type == 2){
                                    ?>
                                    <p class="kachat-name"><?=$chatWith['station_name'].' '.$chatWith['branch_name']?></p>
                                    <?php
                                        //else the normal one will used.
                                        }else{
                                    ?>
                                    <p class="kachat-name"><?=$chatWith['firstname'].' '.$chatWith['lastname']?></p>
                                    <?php
                                        }
                                        if (last_seen($chatWith['active_status']) == "Active") {
                                    ?>
                                    <p class="active-status">Active Now</p>
                                    <?php }else{ ?>
                                    <p class="active-status"><?=last_seen($chatWith['active_status'], $conn)?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="row body">
                            <div class="col" id="chat-box">
                            <?php 
                                if (!empty($chats)) {
                                    foreach($chats as $chat){
                                    $time = time_elapsed_string($chat['created_at']);

                                    if($chat['senderID'] == $_SESSION['userID'])
                                    { ?>
                                        <div class="outgoing-chat date-hover-div">
                                            <!-- <span class="date-hover sender"><?=$time?></span> -->
                                            <p class="message user-chat"><?=$chat['message']?> </p>
                                            <p class="send-date"><?=$time?></p>  
                                        </div>
                                    <?php }else{ ?>
                                        <div class="incoming-chat date-hover-div">
                                            <!-- <span class="date-hover receiver"><?=$time?></span> -->
                                            <p class="message"><?=$chat['message']?></p>
                                            <p class="send-date"><?=$time?></p>
                                        </div>
                                    <?php } 
                                }	
                                }else{ ?>
                                <div class="no-message-yet"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -64 640 640" width="1em" height="1em" fill="currentColor">
                                        <path d="M208 0C322.9 0 416 78.8 416 176C416 273.2 322.9 352 208 352C189.3 352 171.2 349.7 153.9 345.8C123.3 364.8 79.13 384 24.95 384C14.97 384 5.93 378.1 2.018 368.9C-1.896 359.7-.0074 349.1 6.739 341.9C7.26 341.5 29.38 317.4 45.73 285.9C17.18 255.8 0 217.6 0 176C0 78.8 93.13 0 208 0zM164.6 298.1C179.2 302.3 193.8 304 208 304C296.2 304 368 246.6 368 176C368 105.4 296.2 48 208 48C119.8 48 48 105.4 48 176C48 211.2 65.71 237.2 80.57 252.9L104.1 277.8L88.31 308.1C84.74 314.1 80.73 321.9 76.55 328.5C94.26 323.4 111.7 315.5 128.7 304.1L145.4 294.6L164.6 298.1zM441.6 128.2C552 132.4 640 209.5 640 304C640 345.6 622.8 383.8 594.3 413.9C610.6 445.4 632.7 469.5 633.3 469.9C640 477.1 641.9 487.7 637.1 496.9C634.1 506.1 625 512 615 512C560.9 512 516.7 492.8 486.1 473.8C468.8 477.7 450.7 480 432 480C350 480 279.1 439.8 245.2 381.5C262.5 379.2 279.1 375.3 294.9 369.9C322.9 407.1 373.9 432 432 432C446.2 432 460.8 430.3 475.4 426.1L494.6 422.6L511.3 432.1C528.3 443.5 545.7 451.4 563.5 456.5C559.3 449.9 555.3 442.1 551.7 436.1L535.9 405.8L559.4 380.9C574.3 365.3 592 339.2 592 304C592 237.7 528.7 183.1 447.1 176.6L448 176C448 159.5 445.8 143.5 441.6 128.2H441.6z"></path>
                                    </svg>
                                    <p>No messages yet, Start the conversation</p>
                                </div>
                            <?php } ?>
                            </div>
                        </div>
                        <div class="row foot">
                            <div class="col">
                                <input class="form-control" type="text" id="message" placeholder="Type message here...">
                                <button class="btn btn-primary" id="sendbtn"><i class = "fa fa-send"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                }
            ?>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script>
    $('.back').click(function () { 
        window.history.back();
    });

	var scrollDown = function(){
        let chatBox = document.getElementById('chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;
	}

	scrollDown();

    $(document).ready(function(){
        
        $("#sendbtn").on("click", function(){
            message = $("#message").val();
            if (message == "") return;

            $.post("assets/ajax/insert.php",
                {
                    message: message,
                    to_id: <?=$chatWith['userID']?>
                },
                function(data, status){
                    $("#message").val("");
                    $("#chat-box").append(data);
                    scrollDown();
            });
        });
      
        $("#message").on("keypress", function(e){
            if(e.which == 13){
            message = $("#message").val();
            if (message == "") return;

            $.post("assets/ajax/insert.php",
      		   {
      		   	message: message,
      		   	to_id: <?=$chatWith['userID']?>
      		   },
      		   function(data, status){
                  $("#message").val("");
                  $("#chat-box").append(data);
                  scrollDown();
      		    });
            }
        });


        let fechData = function(){
            $.post("assets/ajax/getMessage.php", 
            {
            id_2: <?=$chatWith['userID']?>
            },
            function(data, status){
                $("#chat-box").append(data);
                if (data != "") scrollDown();
            });
        }
        fechData();
        /** 
        auto update last seen 
        every 0.5 sec
        **/
        setInterval(fechData, 500);

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