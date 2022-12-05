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

include 'assets/db.conn.php';

include 'assets/helpers/user.php';
include 'assets/helpers/conversations.php';
include 'assets/helpers/timeAgo.php';
include 'assets/helpers/last_chat.php';

require_once("assets/classes/dbHandler.php");
require_once("assets/classes/Notifications.php");

$data = new Config();

$user = getUser($_SESSION['userID'], $conn);

$userID = $user['userID'];
$notificationClass = new Notifications();

// Customer
if ($user['user_type'] == 1)
    $notifications = $data->getUserNotifications($userID);
// Store
else
    $notifications = $data->getUserNotifications($userID, true);

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
    <link rel="stylesheet" href="assets/css/notification.css">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand sticky-top" id="top">
        <div class="container"><a class="btn" role="button" id="menu-toggle" href="#menu-toggle"><i class="fa fa-bars"></i></a><a class="navbar-brand"><i class="fas fa-gas-pump"></i>&nbsp;FUEL ON</a>
            <ul class="navbar-nav">
                <?php require_once('notifications-div.php'); ?>
                <li class="nav-item" id="mail"><a class="nav-link" href="chat-list.php"><i class="fas fa-envelope"></i></a></li>
                <li class="nav-item dropdown" id="user"><a class="nav-link" data-bs-toggle="dropdown">
                        <div class="profile-div">
                            <img src="assets/img/profiles/<?php echo $userpic ?>">
                        </div>
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
                <?php
                if (isset($_SESSION['userType'])) {
                    if ($userType == 1) {
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
                        <li class="sidebar-brand"> <a href="customer-my-order.php"><i class="fas fa-shopping-bag"></i><span class="icon-name">My Orders</span></a></li>
                        <li class="sidebar-brand"> <a href="customer-account-settings.php"><i class="fas fa-user-cog"></i><span class="icon-name">My Account</span></a></li>

                    <?php
                    } else if ($userType == 2) {
                    ?>
                        <li class="sidebar-brand"> <a href="store-home.php"><i class="fas fa-home"></i><span class="icon-name">Dashboard</span></a></li>
                        <li class="sidebar-brand"> <a href="store-location.php"><i class="fas fa-map-marked-alt"></i><span class="icon-name">Location</span></a></li>
                        <li class="sidebar-brand"> <a href="store-orders-all.php"><i class="fas fa-shopping-basket"></i><span class="icon-name">Orders</span></a></li>
                        <li class="sidebar-brand"> <a href="store-mytimeline.php"><i class="fas fa-store"></i><span class="icon-name">Profile</span></a></li>
                        <li class="sidebar-brand"> <a href="store-myproducts.php"><i class="fas fa-shopping-bag"></i><span class="icon-name">Products</span></a></li>
                        <li class="sidebar-brand"> <a href="store-view-sales.php"><i class="fas fa-chart-bar"></i><span class="icon-name">View Sales</span></a></li>
                        <li class="sidebar-brand"> <a href="store-view-feedback.php"><i class="fas fa-star-half-alt"></i><span class="icon-name">Reviews</span></a></li>
                        <li class="sidebar-brand"> <a href="store-account-settings.php"><i class="fas fa-user-cog"></i><span class="icon-name">Settings</span></a></li>

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
            <div class="col-lg-6 mx-auto" id="chat-list">
                <div id="head">
                    <h4>Notifications</h4>
                </div>
                <div id="list">
                    <div class="list-div list-notif">
                        <?php if (!empty($notifications)) { ?>
                            <?php
                            foreach ($notifications as $notification) { ?>
                                <div class="list-item">
                                    <a href="">
                                        <?php
                                        $msg = $notificationClass->getTypeDesc($notification);
                                        ?>
                                        <div class="content">
                                            <div class="">
                                                <img class="img-notification" src="assets/img/products/<?php echo $notification['prod_image']; ?>">
                                            </div>
                                            <div class="details">
                                                <p style="color:black   ">
                                                    <?= $notificationClass->getTypeDesc($notification) ?>
                                                    <br>
                                                    <?= $notification['orderID'] ?>
                                                    <br>
                                                    <?= $notification['transac_date'] ?>
                                                </p>
                                            </div>

                                        </div>
                                    </a>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="no-message-yet">
                                <p>No notifications yet</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>

</body>

</html>