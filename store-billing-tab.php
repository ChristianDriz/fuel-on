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

    // $orderID = "ORD-1090463931";
    // $shopID = 5;
    // $customerID = 10;

    $orderID = $_GET['orderId'];
    $shopID = $_GET['shopID'];
    $customerID = $_GET['customerID'];

    $shop = $data->oneShop($shopID);
    $shopdetails = $shop[0];

    $customer = $data->oneCustomer($customerID);
    $customerdetails = $customer[0];

    $records = $data->getShopBillingReceipt($orderID);
    $customerName = $records[0]['customerName'];
    $grandTotal = 0;

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
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-billing-tab.css">
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
                <li class="nav-item" id="mail">
                    <p class="badge message-counte"></p>
                    <a class="nav-link" href="chat-list.php"><i class="fas fa-envelope"></i></a>
                </li>
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
                <li class="sidebar-brand"> <a href="store-home.php"><i class="fas fa-home"></i><span class="icon-name">Dashboard</span></a></li>
                <li class="sidebar-brand"> <a href="store-location.php"><i class="fas fa-map-marked-alt"></i><span class="icon-name">Location</span></a></li>
                <li class="sidebar-brand"> <a class="actives" href="store-orders-all.php"><i class="fas fa-shopping-basket"></i><span class="icon-name">Orders</span></a></li>
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
                                    <li class="nav-item"><a class="nav-link" href="store-orders-pending.php">Orders</a></li>
                                    <li class="nav-item"><a class="nav-link" href="store-orders-pickup.php">To Pickup</a></li>
                                    <li class="nav-item"><a class="nav-link" href="store-orders-completed.php">Completed</a></li>
                                    <li class="nav-item"><a class="nav-link" href="store-orders-cancelled.php">Cancelled</a></li>
                                    <li class="nav-item active"><a class="nav-link active" href="store-billing-tab.php?orderId=<?=$orderID?>&shopID=<?=$shopID?>&customerID=<?=$customerID?>">Billing</a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
                <div class="print-container">
                    <div class="heading">
                        <div class="logo">
                            <div class="station-dets">
                                <h5><?= $shopdetails['firstname']?></h5>
                                <p class="branch-name"><?= $shopdetails['lastname'] ?></p>
                                <p>TIN: <?= $shopdetails['tin_num']?></p>
                                <p><?= $shopdetails['email']?></p>
                                <p><?= $shopdetails['phone_num']?></p>
                            </div>
                        </div>
                        <div class="logo">
                            <img src="assets/img/profiles/<?= $shopdetails['user_image']?>">
                        </div>
                    </div>
                    <div class="row g-0 customer-seller-details">
                        <?php
                        $date = $records[0]['transac_date']; 
                        $createdate = date_create($date);
                        $new_date = date_format($createdate, "F d, Y h:i a");

                        ?>
                        <div class="col-6 station-col">
                            <h6>Order ID</h6>
                            <p><?= $records[0]['orderID'] ?></p>
                            <h6>Order Status</h6>
                            <p><?= $records[0]['order_status'] ?></p>
                            <h6>Date</h6>
                            <p><?php echo $new_date?></p>
                        </div>
                        <div class="col-6 customer-col">
                            <h6>Customer Details</h6>
                            <p><?= $customerdetails['firstname'].' '.$customerdetails['lastname']?></p>
                            <p><?= $customerdetails['phone_num']?></p>
                        </div>
                    </div>
                    <div>
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th class="col-7">Description</th>
                                    <th class="col-2">Unit Cost</th>
                                    <th class="col-1">Qty</th>
                                    <th class="col-2">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($records as $record) {
                                        $grandTotal += $record['total'];
                                ?>
                                <tr class="item-descript">
                                    <td><?= $record['product_name']?></td>
                                    <td>₱<?= number_format($record['price'], 2)?></td>
                                    <td><?= $record['quantity']?></td>
                                    <td>₱<?= number_format($record['total'], 2)?></td>
                                </tr>
                                <?php
                                    }
                                ?>
                                <tr class="total">
                                    <th colspan="3">Note</th>
                                    <th class="col-2">Total</th>
                                </tr>
                                <tr class="total">
                                    <td colspan="3">The customer must show this billing when picking up the order</td>
                                    <td class="total-price">₱<?= number_format($grandTotal, 2)?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/Table-With-Search.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script>
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