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
                <li class="sidebar-brand"> <a href="store-orders-all.php"><i class="fas fa-shopping-basket"></i><span class="icon-name">Orders</span></a></li>
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
    <div class="container" id="home">
        <div class="row mb-3 top">
            <div class="col-md-4 col-xl-4 mx-auto">
                <h3>Store Overview</h3>
            </div>
        </div>
        <div class="row row-cols-1 dashboard">
            <div class="col-md-12 col-lg-7 sales-col">
                <div class="cont">
                    <div class="row row-cols-sm-2 row-cols-lg-3">
                        <div class="col-lg-6 text-center align-self-center left"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-graph-up icon">
                                <path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0Zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07Z"></path>
                            </svg><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-currency-dollar">
                                <path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718H4zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73l.348.086z"></path>
                            </svg>
                        </div>
                        <div class="col-lg-6 right">
                            <h3>₱<?= number_format($soldp, 2)?></h3>
                            <p>total profit from your store</p><a class="btn btn-primary" role="button" href="store-view-sales.php">View Sales</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-5 reviews-col">
                <div class="cont">
                    <div class="row row-cols-sm-2 row-cols-lg-3">
                        <div class="col-lg-6 text-center align-self-center left"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-hand-thumbs-up icon">
                                <path d="M8.864.046C7.908-.193 7.02.53 6.956 1.466c-.072 1.051-.23 2.016-.428 2.59-.125.36-.479 1.013-1.04 1.639-.557.623-1.282 1.178-2.131 1.41C2.685 7.288 2 7.87 2 8.72v4.001c0 .845.682 1.464 1.448 1.545 1.07.114 1.564.415 2.068.723l.048.03c.272.165.578.348.97.484.397.136.861.217 1.466.217h3.5c.937 0 1.599-.477 1.934-1.064a1.86 1.86 0 0 0 .254-.912c0-.152-.023-.312-.077-.464.201-.263.38-.578.488-.901.11-.33.172-.762.004-1.149.069-.13.12-.269.159-.403.077-.27.113-.568.113-.857 0-.288-.036-.585-.113-.856a2.144 2.144 0 0 0-.138-.362 1.9 1.9 0 0 0 .234-1.734c-.206-.592-.682-1.1-1.2-1.272-.847-.282-1.803-.276-2.516-.211a9.84 9.84 0 0 0-.443.05 9.365 9.365 0 0 0-.062-4.509A1.38 1.38 0 0 0 9.125.111L8.864.046zM11.5 14.721H8c-.51 0-.863-.069-1.14-.164-.281-.097-.506-.228-.776-.393l-.04-.024c-.555-.339-1.198-.731-2.49-.868-.333-.036-.554-.29-.554-.55V8.72c0-.254.226-.543.62-.65 1.095-.3 1.977-.996 2.614-1.708.635-.71 1.064-1.475 1.238-1.978.243-.7.407-1.768.482-2.85.025-.362.36-.594.667-.518l.262.066c.16.04.258.143.288.255a8.34 8.34 0 0 1-.145 4.725.5.5 0 0 0 .595.644l.003-.001.014-.003.058-.014a8.908 8.908 0 0 1 1.036-.157c.663-.06 1.457-.054 2.11.164.175.058.45.3.57.65.107.308.087.67-.266 1.022l-.353.353.353.354c.043.043.105.141.154.315.048.167.075.37.075.581 0 .212-.027.414-.075.582-.05.174-.111.272-.154.315l-.353.353.353.354c.047.047.109.177.005.488a2.224 2.224 0 0 1-.505.805l-.353.353.353.354c.006.005.041.05.041.17a.866.866 0 0 1-.121.416c-.165.288-.503.56-1.066.56z"></path>
                            </svg>
                        </div>
                        <div class="col-lg-6 right">
                            <h3><?php if (!empty($feedback)) {
                                    echo $feedback;
                                } else {
                                    echo '0';
                                } ?> reviews</h3>
                            <p>about your store</p><a class="btn btn-primary" role="button" href="store-view-feedback.php">Read</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 inventory-col">
                <div class="cont">
                    <div class="row row-cols-sm-2 row-cols-lg-3">
                        <div class="col-lg-6 text-center align-self-center left"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-clipboard-data icon">
                                <path d="M4 11a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0v-1zm6-4a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0V7zM7 9a1 1 0 0 1 2 0v3a1 1 0 1 1-2 0V9z"></path>
                                <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"></path>
                                <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"></path>
                            </svg>
                        </div>
                        <div class="col-lg-6 right">
                            <h3><?php if (!empty($critical)) {
                                    echo $critical;
                                } else {
                                    echo '0';
                                } ?> Products from inventory<br>are low in stock</h3>
                            <a class="btn btn-primary" role="button" href="store-myproducts.php">View Inventory</a>
                        </div>
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