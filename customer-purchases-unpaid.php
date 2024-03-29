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
}
else{
    header('location: index.php');
}

$statusOne = 'Unpaid';
$statusTwo = 'To Pay';

require_once("assets/classes/dbHandler.php");
$data = new Config();
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
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-mypurchase.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-navigation.css">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand sticky-top" id="top">
        <div class="container">
            <a class="btn" role="button" id="menu-toggle" href="#menu-toggle">
                <i class="fa fa-bars"></i>
            </a>
            <a class="navbar-brand">
                <i class="fas fa-gas-pump"></i>&nbsp;FUEL ON
            </a>
            <ul class="navbar-nav">
                <?php require_once('notifications-div.php'); ?>
                <li class="nav-item" id="mail"><a class="nav-link" href="chat-list.php"><i class="fas fa-envelope"></i></a></li>
                <li class="nav-item dropdown" id="user"><a class="nav-link" data-bs-toggle="dropdown">
                        <div class="profile-div"><img src="assets/img/profiles/<?php echo $userpic ?>"></div>
                        <p><?php echo $username; ?></p>
                    </a>
                    <div class="dropdown-menu user"><a class="dropdown-item" href="assets/includes/logout-inc.php">Logout</a></div>
                </li>
            </ul>
        </div>
    </nav>
    <div id="wrapper">
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand"> <a href="customer-home.php"><i class="fas fa-home"></i><span class="icon-name">Home</span></a></li>
                <li class="sidebar-brand"> <a href="customer-map.php"><i class="fas fa-map-pin"></i><span class="icon-name">Map</span></a></li>
                <li class="sidebar-brand"> <a href="customer-products.php"><i class="fas fa-tags"></i><span class="icon-name">Products</span></a></li>
                <li class="sidebar-brand"> <a href="customer-cart.php"><i class="fas fa-shopping-cart"></i><span class="icon-name">Cart</span></a></li>
                <li class="sidebar-brand"> <a class="actives" href="customer-my-order.php"><i class="fas fa-shopping-bag"></i><span class="icon-name">My Orders</span></a></li>
                <li class="sidebar-brand"> <a href="customer-account-settings.php"><i class="fas fa-user-cog"></i><span class="icon-name">My Account</span></a></li>
            </ul>
        </div>
        <div class="page-content-wrapper"></div>
    </div>
    <div class="container" id="transaction-container">
        <div class="row g-0" id="transaction-row-header">
            <div class="col">
                <nav class="navbar navbar-light navbar-expand">
                    <div class="container">
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link" href="customer-my-order.php">All</a></li>
                            <li class="nav-item"><a class="nav-link" href="customer-purchases-pending.php">Pending</a></li>
                            <li class="nav-item active"><a class="nav-link active" href="customer-purchases-unpaid.php">To Pay</a></li>
                            <li class="nav-item"><a class="nav-link" href="customer-purchases-pickup.php">To Pickup</a></li>
                            <li class="nav-item"><a class="nav-link" href="customer-purchases-completed.php">Completed</a></li>
                            <li class="nav-item"><a class="nav-link" href="customer-purchases-cancelled.php">Cancelled</a></li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <?php
        $orders = $data->customerOrderCount($userID, $statusOne, $statusTwo);
            if(empty($orders)){
        ?>
        <div class="row" id="transaction-no-order-row">
            <div class="col-12" id="no-order">
                <div class="icons"><i class="fas fa-question"></i><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -64 640 640" width="1em" height="1em" fill="currentColor">
                        <path d="M75.23 33.4L320 63.1L564.8 33.4C571.5 32.56 578 36.06 581.1 42.12L622.8 125.5C631.7 143.4 622.2 165.1 602.9 170.6L439.6 217.3C425.7 221.2 410.8 215.4 403.4 202.1L320 63.1L236.6 202.1C229.2 215.4 214.3 221.2 200.4 217.3L37.07 170.6C17.81 165.1 8.283 143.4 17.24 125.5L58.94 42.12C61.97 36.06 68.5 32.56 75.23 33.4H75.23zM321.1 128L375.9 219.4C390.8 244.2 420.5 255.1 448.4 248L576 211.6V378.5C576 400.5 561 419.7 539.6 425.1L335.5 476.1C325.3 478.7 314.7 478.7 304.5 476.1L100.4 425.1C78.99 419.7 64 400.5 64 378.5V211.6L191.6 248C219.5 255.1 249.2 244.2 264.1 219.4L318.9 128H321.1z"></path>
                    </svg></div>
                <p>No orders yet</p>
            </div>
        </div>
        <?php
            }
            else{
                foreach($orders as $key => $row){
                    $station = $data->customerGetShop($row['orderID']);
                    $shopDetails = $station[0];
        ?>
        
        <div class="prodak">
            <div class="seller-name">
                <div class="seller-div">
                    <a><i class="fas fa-store"></i><span>&nbsp;<?php echo $shopDetails['firstname'].' '. $shopDetails['lastname']?> Branch</span><br></a>
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
                        <div class="total-price-div"><span>₱<?php echo $val['total']?></span></div>
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
                            <p><?php echo $row['transac_date']?></p>
                        </div>
                        <!-- <div class="cancel-div"><a class="btn" role="button" href="#myModal" data-bs-toggle="modal">Cancel Order</a></div> -->
                    </div>
                    <div class="right-div">
                        <div class="order-total-div"><span>Order Total:</span>
                            <p>₱<?php echo number_format($grandtotal, 2) ?></p>
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
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/Table-With-Search.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
</body>

</html>