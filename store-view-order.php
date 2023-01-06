<?php
session_start();
if(isset($_SESSION['userID'])){
    $userID = $_SESSION["userID"];
    $username = $_SESSION["fname"];
    $userType = $_SESSION["userType"];
    $userpic = $_SESSION["userPic"];

    if ($userType == 1 || $userType == 0) {
        header('location: index.php');
    }
}
else{
    header('location: index.php');
}

require_once("assets/classes/dbHandler.php");
$dbh = new Config();

if(isset($_GET['orderID'])){
    $orderID = $_GET['orderID'];
    $orders = $dbh->customerOrders($orderID);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | View Order Details</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-view-order.css">
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
            <?php
                if(empty($orders)){
                    include 'no-data.php';
                }else{
                    $order = $orders[0];
                    $customer = $dbh->shopGetCustomer($orderID);
                    $buyer = $customer[0];
            ?>
            <div class="container view-order-container">
                <div class="header-div">
                    <a class="back" href="#">
                        <i class="fas fa-chevron-left"></i>Back
                    </a>
                    <div>
                        <span><?php echo $order['orderID']?></span>
                        <span class="divider">|</span>
                        <span class="status"><?php echo $order['order_status']?></span>
                    </div>
                </div>
                <?php
                    if($order['order_status'] == "Ordered"){
                ?>
                <div class="transac-date-div">
                    <div class="col-12 col-md-4 details-div">
                        <div class="payment-div">
                            <span>Payment:</span>
                            <p><?php echo $order['payment_method']?></p>
                        </div>
                        <div class="buttons-div">
                            <button class="btn decline-btn decline-order" 
                            href="assets/includes/updateOrder-inc.php?status=declined&orderID=<?=$order['orderID']?>&shopID=<?=$userID?>&customerID=<?= $buyer['userID']?>">Decline</button>
                            <button class="btn accept-btn accept-order" 
                            href="assets/includes/updateOrder-inc.php?status=approved&orderID=<?=$order['orderID']?>&shopID=<?=$userID?>&customerID=<?= $buyer['userID']?>">Accept</button>
                        </div>
                    </div>
                    <div class="col-12 col-md-8 order-first order-md-last timeline-div">
                        <div>
                            <div class="date">
                                <span>Date Ordered:</span>
                                <p><?php echo $dbh->datetimeconverter($order['date_ordered'])?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    }elseif($order['order_status'] == "To Pickup"){
                ?>
                <div class="transac-date-div">
                    <div class="col-12 col-md-4 details-div">
                        <div class="payment-div">
                            <span>Payment:</span>
                            <p><?php echo $order['payment_method']?></p>
                        </div>
                        <div class="buttons-div">
                            <button class="btn decline-btn cancel-order" href="assets/includes/updateOrder-inc.php?status=pickup_failed&orderID=<?=$order['orderID']?>&shopID=<?=$userID?>&customerID=<?= $buyer['userID']?>">Cancel</button>
                            <button class="btn accept-btn complete-order" href="assets/includes/updateOrder-inc.php?status=received&orderID=<?=$order['orderID']?>&shopID=<?=$userID?>&customerID=<?= $buyer['userID']?>">Completed</button>
                            <a class="btn" target="_blank" href="generate-invoice.php?orderId=<?= $order['orderID']?>"><i class="fas fa-print"></i>Invoice</a> 
                        </div>
                        <div class="note-div">
                            <span><i class="fas fa-exclamation-circle"></i>Customer must pickup the order within 2 days upon approval.</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-8 order-first order-md-last timeline-div">
                        <div>
                            <div class="date">
                                <span>Date Approved:</span>
                                <p><?php echo $dbh->datetimeconverter($order['date_approved'])?></p>
                            </div>
                            <div class="date">
                                <span>Date Ordered:</span>
                                <p><?php echo $dbh->datetimeconverter($order['date_ordered'])?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    }elseif($order['order_status'] == "Completed"){
                ?>
                <div class="transac-date-div">
                    <div class="col-12 col-md-4 details-div">
                        <div class="payment-div">
                            <span>Payment:</span>
                            <p><?php echo $order['payment_method']?></p>
                        </div>
                    </div>
                    <div class="col-12 col-md-8 order-first order-md-last timeline-div">
                        <div>
                            <div class="date">
                                <span>Date Completed:</span>
                                <p><?php echo $dbh->datetimeconverter($order['date_completed'])?></p>
                            </div>
                            <div class="date">
                                <span>Date Approved:</span>
                                <p><?php echo $dbh->datetimeconverter($order['date_approved'])?></p>
                            </div>
                            <div class="date">
                                <span>Date Ordered:</span>
                                <p><?php echo $dbh->datetimeconverter($order['date_ordered'])?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    }elseif($order['order_status'] == "Declined" || $order['order_status'] == "Cancelled"){
                ?>
                <div class="transac-date-div">
                    <div class="col-12 col-md-4 details-div">
                        <div class="payment-div">
                            <span>Payment:</span>
                            <p><?php echo $order['payment_method']?></p>
                        </div>
                        <div class="payment-div">
                            <span>Cancellation Reason:</span>
                            <p><?php echo $dbh->cancelreason($order['cancel_reason'])?></p>
                        </div>
                    </div>
                    <div class="col-12 col-md-8 order-first order-md-last timeline-div">
                        <div>
                            <div class="date">
                                <span>Date Cancelled:</span>
                                <p><?php echo $dbh->datetimeconverter($order['date_cancelled'])?></p>
                            </div>
                            <div class="date">
                                <span>Date Ordered:</span>
                                <p><?php echo $dbh->datetimeconverter($order['date_ordered'])?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    }elseif($order['order_status'] == "Pickup Failed"){
                ?>
                <div class="transac-date-div">
                    <div class="col-12 col-md-4 details-div">
                        <div class="payment-div">
                            <span>Payment:</span>
                            <p><?php echo $order['payment_method']?></p>
                        </div>
                        <div class="payment-div">
                            <span>Cancellation Reason:</span>
                            <p><?php echo $dbh->cancelreason($order['cancel_reason'])?></p>
                        </div>
                    </div>
                    <div class="col-12 col-md-8 order-first order-md-last timeline-div">
                        <div>
                            <div class="date">
                                <span>Date Cancelled:</span>
                                <p><?php echo $dbh->datetimeconverter($order['date_cancelled'])?></p>
                            </div> 
                            <div class="date">
                                <span>Date Approved:</span>
                                <p><?php echo $dbh->datetimeconverter($order['date_approved'])?></p>
                            </div>
                            <div class="date">
                                <span>Date Ordered:</span>
                                <p><?php echo $dbh->datetimeconverter($order['date_ordered'])?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    }
                ?>
                
                <div class="prodak">
                    <div class="seller-name">
                        <div class="seller-div">
                            <a><img src="assets/img/profiles/<?php echo $buyer['user_image']?>"><?php echo $buyer['firstname'].' '.$buyer['lastname']?></a>
                        </div>
                        <div class="chat-div">
                            <a class="btn message" href="chat-box.php?userID=<?=$buyer['userID']?>&userType=<?=$buyer['user_type']?>">Message</a>
                        </div>
                    </div>
                    <?php
                        $grandtotal = 0;
                        foreach($orders as $val){
                            $grandtotal += $val['total'];
                    ?>
                    <div class="sa-products">
                        <a class="product-col">
                            <div class="imeds-n-neym">
                                <div class="imeyds-div">
                                    <img class="product-img" src="assets/img/products/<?php echo $val['prod_image']?>">
                                </div>
                                <div>
                                    <div class="neym-div">
                                        <p class="product-name"><?php echo $val['product_name']?></p>
                                    </div>
                                    <div class="unit-price-div"><span>₱<?php echo $val['price']?></span></div>
                                    <div class="quantity-div"><span>Quantity: <?php echo $val['quantity']?></span></div>
                                </div>
                            </div>
                            <div class="total-price-div"><span>₱<?php echo number_format($val['total'], 2)?></span></div>
                        </a>
                    </div>
                    <?php
                        }
                    ?>
                    <div class="sa-products"></div>
                    <div class="total-div"><span>Order Total:</span>
                        <p>₱<?php echo number_format($grandtotal, 2) ?></p>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/Table-With-Search.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/order-script.js"></script>
    <script>
    $(document).ready(function () {
        //to get back from the previous page
        $('.back').click(function () { 
            window.history.back();
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
    </script>
</body>

</html>