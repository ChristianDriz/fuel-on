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
    include 'assets/helpers/chat.php';
    include 'assets/helpers/user.php';
    include 'assets/helpers/conversations.php';
    include 'assets/helpers/timeAgo.php';
    include 'assets/helpers/last_chat.php';

    require_once("assets/classes/dbHandler.php");
    $dbh = new Config();

  	// $user = getUser($_SESSION['userID'], $conn);
  	$conversations = getConversation($userID, $conn);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | Chat list</title>
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
            <form>
                <div class="container chat-container">
                    <div class="row chat-row">
                        <div class="col-lg-6 mx-auto" id="chat-list">
                            <div id="head">
                                <h4>Chats</h4>
                            </div>
                            <div id="search-div">
                                <div class="row padMar">
                                    <div class="col padMar">
                                        <div class="input-group">
                                            <?php
                                                if($userType == 0){
                                            ?>
                                            <input class="form-control autocomplete" type="text" placeholder="Enter name of the user to search..." id="searchText">
                                            <button class="btn" type="button" id="searchbtn"><i class="fa fa-search"></i></button>
                                            <?php
                                                }elseif($userType == 1){
                                            ?>
                                            <input class="form-control autocomplete" type="text" placeholder="Enter name of the station to search..." id="searchText">
                                            <button class="btn" type="button" id="searchbtn"><i class="fa fa-search"></i></button>
                                            <?php
                                                }elseif($userType == 2){
                                            ?>
                                            <input class="form-control autocomplete" type="text" placeholder="Enter name of the user or customer to search..." id="searchText">
                                            <button class="btn" type="button" id="searchbtn"><i class="fa fa-search"></i></button>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="list">
                                <div class="list-div">
                                    <div class="dine">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>

    <script>
	$(document).ready(function(){
        $("#searchText").on("input", function(){
       	var searchText = $(this).val();
        if(searchText == "") return;
        $.post('assets/ajax/search.php', 
            {
         	key: searchText
     	    },
         	function(data, status){
            $(".list-div").html(data);
           });
       });

        $("#searchbtn").on("click", function(){
       	var searchText = $("#searchText").val();
        if(searchText == "") return;
        $.post('assets/ajax/search.php', 
         	{
         	key: searchText
     	    },
         	function(data, status){
                $(".list-div").html(data);
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
        
        
        //fetching the chatlist
        let fetchMessageChatlist = function(){
            $.get("assets/ajax/getMessageChatlist.php", 
            function(data){
                $(".dine").html(data);
                
            });
        }
        fetchMessageChatlist();
        //auto update every .5 sec
        setInterval(fetchMessageChatlist, 1000);      
    });
        
</script>
</body>
</html>