<?php
session_start();
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $username = $_SESSION['fname'];
    $userpic = $_SESSION['userPic'];
    $userType = $_SESSION['userType'];

    if ($userType == 1) {
        header('location: index.php');
    }
} else {
    header('location: index.php');
}

require_once("assets/classes/dbHandler.php");
$data = new Config();

$shop = $data->shopDetails($userID);
$shopDetails = $shop[0];

$soldp = 0;
$profit = $data->countShopProfit($userID);
$feedback = $data->countFeedback($userID);
$critical = $data->countCritical($userID);
$nostock = $data->countNoStock($userID);
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sarabun&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-home.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-navigation.css">
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
                <li class="nav-item dropdown" id="user">
                    <a class="nav-link" data-bs-toggle="dropdown">
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
                <li class="sidebar-brand"> <a class="actives" href="store-home.php"><i class="fas fa-home"></i><span class="icon-name">Dashboard</span></a></li>
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
            </ul>
        </div>
        <div class="page-content-wrapper">
            <?php
            foreach ($profit as $sold) {
                $soldp += $sold['total'];
            }
            ?>
            <div class="container home-container">
                <h4>Station Overview</h4>
                <div class="row g-0">
                    <div class="col-lg-6">
                        <div class="dashboard sales">
                            <div><img src="assets/img/1.png"></div>
                            <div class="text">
                                <h2>₱<?= number_format($soldp, 2)?></h2>
                                <p>total profit from orders</p>
                                <a class="btn" href="store-view-sales.php">View Sales</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="dashboard reviews">
                            <div><img src="assets/img/2.png"></div>
                            <div class="text">
                                <h2>
                                <?php 
                                if (!empty($feedback)) {
                                    echo $feedback;
                                } else {
                                    echo '0';
                                } ?>
                                    reviews
                                </h2>
                                <p>about your station</p>
                                <a class="btn" href="store-view-feedback.php">View Reviews</a>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="dashboard inventory">
                            <div><img src="assets/img/3.png"></div>
                            <div class="text">
                                <h2>
                                <?php 
                                if (!empty($critical)) {
                                    echo $critical;
                                } else {
                                    echo '0';
                                } ?>
                                    Products from inventory are low in stock,
                                </h2>
                                <h2>
                                <?php
                                if (!empty($nostock)) {
                                    echo $nostock;
                                } else {
                                    echo '0';
                                } ?>
                                    Product is out of stock
                                </h2>
                                <a class="btn" href="store-myproducts.php">View Inventory</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- JS Values -->

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/popup.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/Table-With-Search.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <script>
    <?php
    if (isset($_SESSION['message'])) { ?>
    Swal.fire({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        icon: 'success',
        title: '<?php echo $_SESSION['message'] ?>',
    })
    <?php
        unset($_SESSION['message']);
    }
    ?>
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