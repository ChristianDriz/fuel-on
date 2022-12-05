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

require_once("assets/classes/dbHandler.php");
$data = new Config();


if(isset($_GET['stationID'])){
    $station = $_GET['stationID'];
}
else{
    $station = $userID;
}

$get = $data->getFeedback($station);
$count = $data->getRatings($station);
if(!empty($get) || !empty($count)){
$rateSum = 0;
foreach($get as $rate){
    $rateSum += $rate['rating'];
}
$totalRate = $rateSum / $count;
}else{
    $totalRate = 0;
}

$stations = $data->oneShop($station);
$shop = $stations[0];

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
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-feedback.css">
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
                <li class="sidebar-brand"> <a class="actives" href="customer-home.php"><i class="fas fa-home"></i><span class="icon-name">Home</span></a></li>
                <li class="sidebar-brand"> <a href="customer-map.php"><i class="fas fa-map-pin"></i><span class="icon-name">Map</span></a></li>
                <li class="sidebar-brand"> <a href="customer-products.php"><i class="fas fa-tags"></i><span class="icon-name">Products</span></a></li>
                <li class="sidebar-brand"> <a href="customer-cart.php"><i class="fas fa-shopping-cart"></i><span class="icon-name">Cart</span></a></li>
                <li class="sidebar-brand"> <a href="customer-purchases-pending.php"><i class="fas fa-shopping-bag"></i><span class="icon-name">My Orders</span></a></li>
                <li class="sidebar-brand"> <a href="customer-account-settings.php"><i class="fas fa-user-cog"></i><span class="icon-name">My Account</span></a></li>
            </ul>
        </div>
        <div class="page-content-wrapper"></div>
    </div>

    <div class="store-profile-div">
        <?php
            $records = $data->oneShop($station);
            foreach($records as $val){
        ?>
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-7 col-lg-8 profile-col">
                    <div class="profile-image-div"><img class="img" src="assets/img//profiles/<?= $val['user_image']?>"></div>
                    <div class="profile-name-div">
                        <h5><?= $shop['firstname'].' '.$shop['lastname'] ?> Branch</h5>
                        <p class="rating"><i class="far fa-star"></i>Rating:<a href="customer-view-feedback.php?stationID=<?= $val['userID'] ?>"><span><?= number_format($totalRate, 1)?> (<?= $count ?> Rating)</span></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="container store-container">
        <div class="row rating-row">
            <div class="col rating-col">
                <div class="header"><a href="customer-viewstore-timeline.php?stationID=<?php echo $val['userID']; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-arrow-left">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <line x1="5" y1="12" x2="11" y2="18"></line>
                            <line x1="5" y1="12" x2="11" y2="6"></line>
                        </svg></a>
                    <h4>Leave a rating</h4>
                </div>
                <form action="assets/includes/feedback-inc.php" method="post">
                <div class="star-widget-container">
                    <div class="star-widget"><input type="radio" id="rate-5" name="rate"><label class="form-label fas fa-star" for="rate-5"></label><input type="radio" id="rate-4" name="rate"><label class="form-label fas fa-star" for="rate-4"></label><input type="radio" id="rate-3" name="rate"><label class="form-label fas fa-star" for="rate-3"></label><input type="radio" id="rate-2" name="rate"><label class="form-label fas fa-star" for="rate-2"></label><input type="radio" id="rate-1" name="rate"><label class="form-label fas fa-star" for="rate-1"></label></div>
                </div>
                <div class="bottom">
                    <div class="text-area"><textarea placeholder="Describe your experience..."></textarea></div>
                    <div class="btn-div"><button class="btn" type="submit">Submit</button></div>
                </div>
            </form>
            </div>
        </div>
    </div>
    <!-- <div id="popup-container" class="popup-container">
        <div id="popup" class="popup"><i class="fas fa-check-circle"></i>
            <h3>Thank You!</h3>
            <p>Your feedback has been successfully submitted.</p><a class="btn" role="button" onclick="closePopup()" href="customer-view-feedback.php">Close</a>
        </div>
    </div> -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/popup.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
</body>
</html>