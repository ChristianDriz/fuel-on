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
    $data = new Config();

    //to get the usertype of the user chat's with
    if (isset($_GET['userID'])) {
        $ID = $_GET['userID'];
        $type = $_GET['userType'];
        
    }else{
        header("Location: chat-list.php");
        exit;
    }
    
    if($type == 1 || $type == 0){
        $chats = $data->oneUser($ID);
    }
    else{
        $chats = $data->shopDetails($ID);
    }
    $chatWith = $chats[0];

    $chats = getChats($userID, $chatWith['userID'], $conn);

  	opened($chatWith['userID'], $conn, $chats);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON</title>
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
    <nav class="navbar navbar-light navbar-expand sticky-top" id="top">
        <div class="container"><a class="btn" role="button" id="menu-toggle" href="#menu-toggle"><i class="fa fa-bars"></i></a><a class="navbar-brand"><i class="fas fa-gas-pump"></i>&nbsp;FUEL ON</a>
            <ul class="navbar-nav">
            <?php 
                if($userType != 0){
                    require_once('notifications-div.php'); 
                }
            ?>
                <li class="nav-item" id="mail">
                    <p class="badge message-counter"></p>
                    <a class="nav-link" href="chat-list.php"><i class="fas fa-envelope"></i></a>
                </li>
                <li class="nav-item dropdown" id="user"><a class="nav-link" data-bs-toggle="dropdown">
                        <div class="profile-div">
                            <img src="assets/img/profiles/<?php echo $userpic ?>">
                        </div>
                        <p>
                        <?php
                            if ($userType == 1 || $userType == 0){
                                echo $username;
                            }else{
                                $shop = $data->shopDetails($userID);
                                $shopDetails = $shop[0];
                                
                                echo $shopDetails['station_name'].' '.$shopDetails['branch_name'];
                            }
                        ?>
                        </p>
                    </a>
                    <div class="dropdown-menu user"><a class="dropdown-item" href="assets/includes/logout-inc.php">Logout</a></div>
                </li>
            </ul>
        </div>
    </nav>
    <div id="wrapper">
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <?php
                    if(isset($_SESSION['userType']))
                    {
                        if($userType == 1)
                        {
                ?>
                <li class="sidebar-brand"> <a href="customer-home.php"><i class="fas fa-home"></i><span class="icon-name">Home</span></a></li>
                <li class="sidebar-brand"> <a href="customer-map.php"><i class="fas fa-map-pin"></i><span class="icon-name">Map</span></a></li>
                <li class="sidebar-brand"> <a href="customer-products.php"><i class="fas fa-tags"></i><span class="icon-name">Products</span></a></li>
                <li class="sidebar-brand"> 
                    <a href="customer-cart.php">
                        <i class="fas fa-shopping-cart"></i><span class="icon-name">Cart</span>
                    </a>
                    <?php 
                    $cartItemCount = $data->cartTotalItems($userID);
                    if($cartItemCount != 0){?>
                        <sup><?php echo $cartItemCount?></sup>
                    <?php
                    }?>
                </li>
                <li class="sidebar-brand"> 
                    <a href="customer-my-order.php">
                        <i class="fas fa-shopping-bag"></i><span class="icon-name">My Orders</span>
                    </a>
                    <?php
                    $orderCounter = $data->AllOrdersCountCustomer($userID);
                    if($orderCounter != 0){?>
                        <sup style="margin-left: 52px;"><?php echo $orderCounter ?></sup>
                    <?php
                    }?>
                </li>
                <li class="sidebar-brand"> <a href="customer-account-settings.php"><i class="fas fa-user-cog"></i><span class="icon-name">My Account</span></a></li>
                
                <?php
                    }
                    else if($userType == 2)
                    {      
                ?>         

                <li class="sidebar-brand"> <a href="store-home.php"><i class="fas fa-home"></i><span class="icon-name">Dashboard</span></a></li>
                <li class="sidebar-brand"> <a href="store-location.php"><i class="fas fa-map-marked-alt"></i><span class="icon-name">Location</span></a></li>
                <li class="sidebar-brand"> 
                    <a href="store-orders-all.php">
                        <i class="fas fa-shopping-basket"></i><span class="icon-name">Orders</span>
                    </a>
                    <?php
                    $orderCounter = $data->AllOrdersCountShop($userID);
                    if($orderCounter != 0){?>
                        <sup><?php echo $orderCounter ?></sup>
                    <?php
                    }?>
                </li>
                <li class="sidebar-brand"> <a href="store-mytimeline.php"><i class="fas fa-store"></i><span class="icon-name">Profile</span></a></li>
                <li class="sidebar-brand"> <a href="store-myproducts.php"><i class="fas fa-shopping-bag"></i><span class="icon-name">Products</span></a></li>
                <li class="sidebar-brand"> <a href="store-view-sales.php"><i class="fas fa-chart-bar"></i><span class="icon-name">View Sales</span></a></li>
                <li class="sidebar-brand"> <a href="store-view-feedback.php"><i class="fas fa-star-half-alt"></i><span class="icon-name">Reviews</span></a></li>
                <li class="sidebar-brand"> <a href="store-account-settings.php"><i class="fas fa-user-cog"></i><span class="icon-name">Settings</span></a></li>

                <?php
                    }else if($userType == 0){ 
                ?>
                <li class="sidebar-brand"> <a href="admin-home-panel.php"><i class="fas fa-home"></i><span class="icon-name">Dashboard</span></a></li>
                <li class="sidebar-brand"> <a href="admin-normal-user-table.php"><i class="fas fa-users"></i><span class="icon-name">Normal Users</span></a></li>
                <li class="sidebar-brand"> <a href="admin-stores-table.php"><i class="fas fa-store"></i><span class="icon-name">Station Owners</span></a></li>
                <li class="sidebar-brand"> <a href="admin-table.php"><i class="fas fa-user-tie"></i><span class="icon-name">Admins</span></a></li>
                <li class="sidebar-brand"> <a href="admin-products-table.php"><i class="fas fa-shopping-basket"></i><span class="icon-name">Products</span></a></li>
                <li class="sidebar-brand"> <a href="admin-fuels-table.php"><i class="fas fa-gas-pump"></i><span class="icon-name">Fuels</span></a></li>
                <li class="sidebar-brand"> <a href="admin-store-locations.php"><i class="fas fa-map-marked-alt"></i><span class="icon-name">Station Locations</span></a></li>
                <li class="sidebar-brand"> 
                    <a href="admin-store-approval.php"><i class="fas fa-user-check"></i><span class="icon-name">Pending Approval</span></a>
                    <?php 
                        $pending = $data->countPending();
                        if ($pending != 0) { ?>
                        <sup><?=$pending ?></sup>
                    <?php
                    } ?>
                </li>
                <li class="sidebar-brand"> <a href="admin-account-settings.php"><i class="fas fa-user-cog"></i><span class="icon-name">Settings</span></a></li>

                <?php
                    }
                }
                ?>
            </ul>
        </div>
        <div class="page-content-wrapper"></div>
    </div>
        <div class="container chat-container">
            <div class="row chat-row">
                <div class="col-lg-8 mx-auto card" id="convo">
                    <div class="row head">
                        <div class="col-10 name-sender">
                            <div class="back-button"><a href="chat-list.php"><i class="fas fa-arrow-left"></i></a></div>
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
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>

    <script>
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