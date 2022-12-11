<?php
session_start();
if(isset($_SESSION['userID'])){
    $userID = $_SESSION["userID"];
    $username = $_SESSION["fname"];
    $userType = $_SESSION["userType"];
    $userpic = $_SESSION["userPic"];

    if($userType == 1)
    { 
        header('location: index.php');
    }
}
else{
    header('location: index.php');
}

require_once("assets/classes/dbHandler.php");
$data = new Config();

$pending = "ordered";
$pickup = "To Pickup";

$pendingCounter = $data->OrderCountShop($pending, $userID);
$pickupCounter = $data->OrderCountShop($pickup, $userID);

$shop = $data->shopDetails($userID);
$shopDetails = $shop[0];

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
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-navigation.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-orders.css">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand sticky-top" id="top">
        <div class="container"><a class="btn" role="button" id="menu-toggle" href="#menu-toggle"><i class="fa fa-bars"></i></a><a class="navbar-brand" href="#">&nbsp;<i class="fas fa-gas-pump"></i>&nbsp;FUEL ON</a>
            <ul class="navbar-nav">
            <?php require_once('notifications-div.php'); ?>
                <li class="nav-item" id="mail">
                    <p class="badge message-counter"></p>
                    <a class="nav-link" href="chat-list.php"><i class="fas fa-envelope"></i></a>
                </li>
                <li class="nav-item dropdown" id="user"><a class="nav-link" data-bs-toggle="dropdown">
                        <div class="profile-div"><img src="assets/img/profiles/<?php echo $userpic ?>"></div>
                        <p><?php echo $shopDetails['station_name'].' '.$shopDetails['branch_name']; ?></p>
                    </a>
                    <div class="dropdown-menu user"><a class="dropdown-item" href="assets/includes/logout-inc.php">Logout</a></div>
                </li>
            </ul>
        </div>
    </nav>
    <div id="wrapper">
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand"> <a href="store-home.php"><i class="fas fa-home"></i><span class="icon-name">Dashboard</span></a></li>
                <li class="sidebar-brand"> <a href="store-location.php"><i class="fas fa-map-marked-alt"></i><span class="icon-name">Location</span></a></li>
                <li class="sidebar-brand"> 
                    <a class="actives" href="store-orders-all.php">
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
            </ul>
        </div>
        <div class="page-content-wrapper">
    <div class="container" id="transaction-container">
        <div class="row g-0" id="transaction-row-header">
            <div class="col">
                <nav class="navbar navbar-light navbar-expand">
                    <div class="container">
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link" href="store-orders-all.php">All</a></li>
                            <li class="nav-item">
                                <a class="nav-link" href="store-orders-pending.php">Orders
                                <?php
                                    if($pendingCounter != 0){
                                    echo "(".$pendingCounter.")";
                                    }
                                ?>
                                </a>
                            </li>    
                            <li class="nav-item">
                                <a class="nav-link" href="store-orders-pickup.php">To Pickup
                                <?php
                                    if($pickupCounter != 0){
                                    echo "(".$pickupCounter.")";
                                    }
                                ?>
                                </a>
                            </li>
                            <li class="nav-item active"><a class="nav-link active" href="store-orders-completed.php">Completed</a></li>
                            <li class="nav-item"><a class="nav-link" href="store-orders-cancelled.php">Cancelled</a></li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <?php 
        $status = 'Completed';
        $orders = $data->shopOrderCount($userID, $status);
        if(empty($orders)){
        ?>
        <div class="row g-0" id="transaction-no-order-row">
            <div class="col-12" id="no-order">
                <img src="assets/img/no-order.png">
                <p>No orders yet</p>
            </div>
        </div>
        <?php }else{
                foreach($orders as $row){
                    $customer = $data->shopGetCustomer($row['orderID']);
                    $buyer = $customer[0];
        ?>
        <div class="prodak">
            <div class="seller-name">
                <div class="seller-div"><img src="assets/img/profiles/<?php echo $buyer['user_image']?>">
                    <p><?php echo $buyer['firstname'].' '.$buyer['lastname']?></p>
                    <a data-bs-toggle="tooltip" data-bss-tooltip="" data-bs-placement="bottom" href="chat-box.php?userID=<?=$buyer['userID']?>&userType=<?=$buyer['user_type']?>" title="Message Customer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-message">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M4 21v-13a3 3 0 0 1 3 -3h10a3 3 0 0 1 3 3v6a3 3 0 0 1 -3 3h-9l-4 4"></path>
                            <line x1="8" y1="9" x2="16" y2="9"></line>
                            <line x1="8" y1="13" x2="14" y2="13"></line>
                        </svg>
                    </a>
                </div>
                <div class="order-id-div">
                    <p><?php echo $row['orderID']?></p>
                </div>
            </div>

            <?php
                $grandtotal = 0;
                $records = $data->customerOrders($row['orderID']);
                    foreach($records as $key => $val){
                        $grandtotal += $val['total'];

                        $date = $row['transac_date'];
                        $createdate = date_create($date);
                        $new_date = date_format($createdate, "M d, Y h:i:s A");
            ?>
            <div class="sa-products">
                <div class="product-col">
                    <div class="imeds-n-neym">
                        <div class="imeyds-div"><a href="#"><img class="product-img" src="assets/img/products/<?php echo $val['prod_image']?>"></a></div>
                        <div>
                            <div class="neym-div">
                                <p class="product-name"><?php echo $val['product_name']?></p>
                            </div>
                            <div class="unit-price-div"><span>₱<?php echo $val['price']?></span></div>
                            <div class="quantity-div"><span>Quantity: <?php echo $val['quantity']?></span></div>
                        </div>
                    </div>
                    <div class="total-price-div"><span>₱<?php echo number_format($val['total'], 2)?></span></div>
                </div>
            </div>
            <?php 
                }
            ?>
            <div class="summary">
                <div class="left-div">
                    <div class="payment-div"><span>Payment:</span>
                        <p><?php echo $val['payment_method']?></p>
                    </div>
                    <div class="order-date-div"><span>Order Date:</span>
                        <p><?php echo $new_date?></p>
                    </div>
                </div>
                <div class="right-div">
                    <div class="order-total-div"><span>Order Total:</span>
                        <p>₱<?php echo number_format($grandtotal, 2)?></p>
                    </div>
                    <div class="status-div"><span>Status:</span>
                        <p><?php echo $val['order_status']?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            } 
        } 
        ?>
    </div>
    </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/Table-With-Search.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <script>    
        <?php 
        if(isset($_SESSION['message'])) {?>
        //SUCCESS
        Swal.fire({
            title: '<?php echo $_SESSION['message']?>',
            icon: 'success',
            button: true
        });
        <?php 
        unset($_SESSION['message']);
        }?>


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