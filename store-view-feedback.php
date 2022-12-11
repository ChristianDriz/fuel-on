<?php
session_start();
if(isset($_SESSION['userID'])){
    $userID = $_SESSION["userID"];
    $username = $_SESSION["fname"];
    $userpic = $_SESSION["userPic"];
    $userType = $_SESSION["userType"];

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

if(isset($_GET['stationID'])){
    $station = $_GET['stationID'];
}
else{
    $station = $userID;
}

$feedback = $data->viewRatings($userID);
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

$feedback = $data->viewRatings($station);

$countAll = $data->countRatings($station);
$countOne = $data->countOneStar($station);
$countTwo = $data->countTwoStar($station);
$countThree = $data->countThreeStar($station);
$countFour = $data->countFourStar($station);
$countFive = $data->countFiveStar($station);

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
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-view-feedback.css">
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
                <li class="sidebar-brand"> <a class="actives" href="store-view-feedback.php"><i class="fas fa-star-half-alt"></i><span class="icon-name">Reviews</span></a></li>
                <li class="sidebar-brand"> <a href="store-account-settings.php"><i class="fas fa-user-cog"></i><span class="icon-name">Settings</span></a></li>
            </ul>
        </div>
        <div class="page-content-wrapper">
            <?php
                    $records = $data->oneShop($station);
                    foreach($records as $key => $val){
            ?>
            <div class="container ratings-container">
                <h4>My Store Ratings</h4>
                <div class="div-div">
                    <div id="store-ratings-total" class="ratings-summary">
                        <h2><?= number_format($totalRate, 1)?> out of 5<i class="fas fa-star"></i></h2>
                        <div class="row justify-content-center">
                            <div class="col-auto"><button class="btn ratings-btn active" type="button" id="allStar" value="<?php echo $val['userID']; ?>">All<span>(<?=$countAll?>)</span></button></div>
                            <div class="col-auto"><button class="btn ratings-btn" type="button" id="fiveStar" value="<?php echo $val['userID']; ?>"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><span>(<?=$countFive?>)</span></button></div>
                            <div class="col-auto"><button class="btn ratings-btn" type="button" id="fourStar" value="<?php echo $val['userID']; ?>"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><span>(<?=$countFour?>)</span></button></div>
                            <div class="col-auto"><button class="btn ratings-btn" type="button" id="threeStar" value="<?php echo $val['userID']; ?>"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><span>(<?=$countThree?>)</span></button></div>
                            <div class="col-auto"><button class="btn ratings-btn" type="button" id="twoStar" value="<?php echo $val['userID']; ?>"><i class="fas fa-star"></i><i class="fas fa-star"></i><span>(<?=$countTwo?>)</span></button></div>
                            <div class="col-auto"><button class="btn ratings-btn" type="button" id="oneStar" value="<?php echo $val['userID']; ?>"><i class="fas fa-star"></i><span>(<?=$countOne?>)</span></button></div>
                        </div>
                    </div>

                    <!--Start ng loop-->
                    <div class="dito">
                    <?php
                        if(!empty($feedback)){
                            foreach($feedback as $ratings){   

                            $date = $ratings['rating_date'];
                            $createdate = date_create($date);
                            $new_date = date_format($createdate, "M d, Y h:i:s A");
                    ?>
                    <div class="ratings-div">
                        <div>
                            <div class="rates-pic-div"><img class="rates-img" src="assets/img/profiles/<?=$ratings['user_image']?>"></div>
                        </div>
                        <div class="rates-content">
                            <div class="user-div">
                                <p class="user-name"><?=$ratings['firstname'].' '.$ratings['lastname']?></p>
                            </div>
                            <div class="star-div">
                                <?php
                                    $star = 0;
                                    while($star < $ratings['rating']){
                                ?>
                                <i class="fas fa-star"></i>
                                <?php
                                    $star++;
                                    }
                                ?>
                            </div>
                            <div class="date-div">
                                <p class="rate-date"><?php echo $new_date ?></p>
                            </div>
                            <div class="comment-div">
                                <p><?=$ratings['feedback']?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                            }
                        }else{
                    ?>
                    <div class="no-ratings-div">
                        <p>No Ratings Yet</p>
                    </div>
                    <?php 
                        }
                    ?>
                    </div>
                    <!--End ng loop-->
                </div>
            </div>
            <?php
                }
            ?>
        </div>
    </div>
    <script>
        var stars = document.querySelectorAll(".ratings-btn");

        stars.forEach(button => {
            button.addEventListener("click",()=> {
                resetActive();
                button.classList.add("active");
            })
        })

        function resetActive(){
            stars.forEach(button => {
                button.classList.remove("active");
            })
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/ratings.js"></script>
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