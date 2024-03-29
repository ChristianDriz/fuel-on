<?php  

session_start();

# check if the user is logged in
if (isset($_SESSION['userID'])) {
	
}else {
	header("Location: ../../login.php");
	exit;
}

	include '../db.conn.php';
	require_once("../classes/dbHandler.php");
    require_once("../classes/Notifications.php");

	$data = new Config();
    $notificationClass = new Notifications();

	$userID = $_GET['userID'];
	$userType = $_GET['userType'];

    $notif = $data->getUserUnreadNotif($userID, $userType);

    ?>
        <div class="notifs">
        <?php
            if(empty($notif)){
        ?>
            <div class="no-notifs-div">
                <p>No notifications yet</p>
            </div>
        <?php
            }else{
                foreach($notif as $notifs){
                    $notifDetails = $data->getDetailsNotif($notifs['orderID'], $userType);
                    $DetailsNotif = $notifDetails[0];

                    $notifTime = $notificationClass->NotifTime($notifs['notif_date']);
        ?>
            <div class="notifs-div">
                <?php
                    //for customer
                    if($userType == 1) {

                        //When the order of the customer is approved
                        if($notifs['notif_type'] == "To Pickup"){
                ?> 
                    <button class="d-flex align-items-start notif-link" id="<?= $notifs['orderID']?>" value="<?= $notifs['notif_date']?>" href="customer-view-order.php?orderID=<?php echo $notifs['orderID']?>">
                        <img class="notif-img" src="assets/img/products/<?php echo $DetailsNotif['prod_image']?>">
                        <div class="notifs-details">
                            <p class="name"><?php echo $DetailsNotif['name']?> Approved your order</p>
                            <p class="order-id"><?php echo $notifs['orderID']?></p>
                            <p class="notif-date unread"><?php echo $notifTime ?></p>
                        </div>
                    </button>
                    <?php
                        //When the order of the customer is declined    
                        }elseif($notifs['notif_type'] == "Declined"){
                    ?>
                    <button class="d-flex align-items-start notif-link" id="<?= $notifs['orderID']?>" value="<?= $notifs['notif_date']?>" href="customer-view-order.php?orderID=<?php echo $notifs['orderID']?>">
                        <img class="notif-img" src="assets/img/products/<?php echo $DetailsNotif['prod_image']?>">
                        <div class="notifs-details">
                            <p class="name"><?php echo $DetailsNotif['name']?> Declined your order</p>
                            <p class="order-id"><?php echo $notifs['orderID']?></p>
                            <p class="notif-date unread"><?php echo $notifTime ?></p>
                        </div>
                    </button>
                    <?php
                        //When the order of the customer is completed
                        }elseif($notifs['notif_type'] == "Completed"){
                    ?>
                    <button class="d-flex align-items-start notif-link" id="<?= $notifs['orderID']?>" value="<?= $notifs['notif_date']?>" href="customer-view-order.php?orderID=<?php echo $notifs['orderID']?>">
                        <img class="notif-img" src="assets/img/products/<?php echo $DetailsNotif['prod_image']?>">
                        <div class="notifs-details">
                            <p class="name">Your order is completed</p>
                            <p class="order-id"><?php echo $notifs['orderID']?></p>
                            <p class="notif-date unread"><?php echo $notifTime ?></p>
                        </div>
                    </button>
                    <?php
                        //When the order of the customer is completed
                        }elseif($notifs['notif_type'] == "Pickup Failed"){
                    ?>
                    <button class="d-flex align-items-start notif-link" id="<?= $notifs['orderID']?>" value="<?= $notifs['notif_date']?>" href="customer-view-order.php?orderID=<?php echo $notifs['orderID']?>">
                        <img class="notif-img" src="assets/img/products/<?php echo $DetailsNotif['prod_image']?>">
                        <div class="notifs-details">
                            <p class="name">Your order has been cancelled because it was not picked up</p>
                            <p class="order-id"><?php echo $notifs['orderID']?></p>
                            <p class="notif-date unread"><?php echo $notifTime ?></p>
                        </div>
                    </button>
                <?php
                        }
                    //for shop
                    }elseif($userType == 2) {

                        //When new order is placed 
                        if($notifs['notif_type'] == "Ordered"){
                ?>
                    <button class="d-flex align-items-start notif-link" id="<?= $notifs['orderID']?>" value="<?= $notifs['notif_date']?>" href="store-view-order.php?orderID=<?php echo $notifs['orderID']?>">
                        <img class="notif-img" src="assets/img/products/<?php echo $DetailsNotif['prod_image']?>">
                        <div class="notifs-details">
                            <p class="name">You received a new order from <?php echo $DetailsNotif['name']?></p>
                            <p class="order-id"><?php echo $notifs['orderID']?></p>
                            <p class="notif-date unread"><?php echo $notifTime ?></p>
                        </div>
                    </button>
                    <?php
                        //When the order is cancelled
                        }elseif($notifs['notif_type'] == "Cancelled"){
                    ?>
                    <button class="d-flex align-items-start notif-link" id="<?= $notifs['orderID']?>" value="<?= $notifs['notif_date']?>" href="store-view-order.php?orderID=<?php echo $notifs['orderID']?>">
                        <img class="notif-img" src="assets/img/products/<?php echo $DetailsNotif['prod_image']?>">
                        <div class="notifs-details">
                            <p class="name"><?php echo $DetailsNotif['name']?> Cancelled the Order</p>
                            <p class="order-id"><?php echo $notifs['orderID']?></p>
                            <p class="notif-date unread"><?php echo $notifTime ?></p>
                        </div>
                    </button>
                    <?php 
                        //When order is completed
                        }elseif($notifs['notif_type'] == "Completed"){
                    ?>
                    <button class="d-flex align-items-start notif-link" href="store-view-order.php?orderID=<?php echo $notifs['orderID']?>">
                        <img class="notif-img" src="assets/img/products/<?php echo $DetailsNotif['prod_image']?>">
                        <div class="notifs-details">
                            <p class="name"><?php echo $DetailsNotif['name']?> has received the order</p>
                            <p class="order-id"><?php echo $notifs['orderID']?></p>
                            <p class="notif-date unread"><?php echo $notifTime ?></p>
                        </div>
                    </button>
                <?php
                        }
                    }
                ?>
                <div class="notif-dot">
                    <i class="fas fa-circle"></i>
                </div>

            </div>
        <?php
                }
            }
        ?>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function () {
                $('.d-flex').click(function (e) { 
                    e.preventDefault();

                    var link = $(this).attr('href');
                    var id = $(this).attr('id');
                    var time = $(this).val();
                    
                    $.ajax({
                        type: "GET",
                        url: "assets/includes/update-notif-status.php",
                        data: {orderID: id, time: time},
                        success: function (data) {
                            console.log(data);
                            document.location = link; //will go to the link after updating the data from notif-status
                        },
                        error: function (e) {
                            console.log('di nagana');
                        }
                    });
                });
            });
        </script>
       
                