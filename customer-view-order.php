<?php
session_start();
if(isset($_SESSION['userID'])){
    $userID = $_SESSION["userID"];
    $username = $_SESSION["fname"];
    $userType = $_SESSION["userType"];
    $userpic = $_SESSION["userPic"];

    if($userType == 2)
    { 
        header('location: index.php');
    }
    elseif($userType == 0)
    { 
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
            <!--Ordered-->
            <?php
                if(empty($orders)){
                    include 'no-data.php';
                }else{
                    $order = $orders[0];
                    $station = $dbh->customerGetShop($orderID);
                    $shopDetails = $station[0];
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
                            <button class="btn cancel-order" href="assets/includes/updateOrder-inc.php?status=cancelled&orderID=<?=$order['orderID']?>&shopID=<?=$order['shopID']?>&customerID=<?= $userID?>">Cancel Order</button>
                        </div>
                        <div class="note-div">
                            <span>Kindly wait for the station to approve your order.</span>
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
                            <a class="btn" target="_blank" href="generate-invoice.php?orderId=<?= $order['orderID']?>"><i class="fas fa-print"></i>Invoice</a>
                        </div>
                        <div class="note-div">
                            <span>Pickup your order within 2 days upon approval, or it will be cancelled</span>
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
                            <a><i class="fas fa-store"></i><?php echo $shopDetails['station_name'].' '. $shopDetails['branch_name']?></a>
                        </div>
                        <div class="chat-div">
                            <a class="btn message" href="chat-box.php?userID=<?=$shopDetails['shopID']?>&userType=<?=$shopDetails['user_type']?>">Message</a>
                            <a class="btn view-station" href="customer-viewstore-timeline.php?stationID=<?php echo $shopDetails['shopID']?>">View Station</a>
                        </div>
                    </div>
                    <?php
                        $grandtotal = 0;
                        foreach($orders as $val){
                            $grandtotal += $val['total'];
                    ?>
                    <div class="sa-products">
                        <a class="product-col" href="customer-view-products.php?prodID=<?php echo $val['productID']?>">
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
    <script>
    $(document).ready(function () {
        //to get back from the previous page
        $('.back').click(function () { 
            window.history.back();
        });

        //cancel order button
        $('.cancel-order').click(function (e) { 
            e.preventDefault();
            const url = $(this).attr('href');

            Swal.fire({
                title: 'Confirmation',
                text: "Do you want to cancel your order?",
                icon: 'question',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    const { value: reason } = Swal.fire({
                        title: 'Please select your reason',
                        input: 'select',
                        inputPlaceholder: 'Select reason',
                        showCancelButton: true,
                        inputOptions: {
                            reason1: 'Need to modify order',
                            reason2: 'Found something else cheaper',
                            reason3: 'Others / Change of mind'
                        },
                        inputValidator: (value) => {
                            return new Promise((resolve) => {
                                if (value) {
                                    resolve()
                                        $.ajax({
                                            type: "GET",
                                            url,
                                            data: "reason=" +value,
                                            success: function (data) {
                                                Swal.fire({
                                                    title: 'Order Cancelled',
                                                    icon: 'success',
                                                    button: true,
                                                }).then(() => {
                                                    location.reload();
                                                });
                                            }
                                        });
                                }else{
                                    resolve('You need to select reason')
                                }
                            })
                        }
                    })
                }
            }) 
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