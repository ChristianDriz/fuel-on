<?php
session_start();

if(isset($_SESSION['userID'])){
    $userID = $_SESSION['userID'];
    $username = $_SESSION['fname'];
    $userpic = $_SESSION['userPic'];
    $userType = $_SESSION['userType'];

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


$get = $data->getFeedback($userID);
$count = $data->getRatings($userID);
if(!empty($get) || !empty($count)){
$rateSum = 0;
foreach($get as $rate){
    $rateSum += $rate['rating'];
}
$totalRate = $rateSum / $count;
}else{
    $totalRate = 0;
}

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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Share+Tech+Mono&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/fonts/material-icons.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-mytimeline.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-navigation.css">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand sticky-top" id="top">
        <div class="container"><a class="btn" role="button" id="menu-toggle" href="#menu-toggle"><i class="fa fa-bars"></i></a><a class="navbar-brand">&nbsp;<i class="fas fa-gas-pump"></i>&nbsp;FUEL ON</a>
            <ul class="navbar-nav">
            <?php require_once('notifications-div.php'); ?>
                <li class="nav-item" id="mail">
                    <p class="badge message-counter"></p>
                    <a class="nav-link" href="chat-list.php"><i class="fas fa-envelope"></i></a>
                </li>
                <li class="nav-item dropdown" id="user"><a class="nav-link" data-bs-toggle="dropdown">
                        <div class="profile-div">
                            <img src="assets/img/profiles/<?php echo $userpic ?>">
                        </div>
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
                <li class="sidebar-brand"> <a href="store-orders-all.php"><i class="fas fa-shopping-basket"></i><span class="icon-name">Orders</span></a></li>
                <li class="sidebar-brand"> <a class="actives"href="store-mytimeline.php"><i class="fas fa-store"></i><span class="icon-name">Profile</span></a></li>
                <li class="sidebar-brand"> <a href="store-myproducts.php"><i class="fas fa-shopping-bag"></i><span class="icon-name">Products</span></a></li>
                <li class="sidebar-brand"> <a href="store-view-sales.php"><i class="fas fa-chart-bar"></i><span class="icon-name">View Sales</span></a></li>
                <li class="sidebar-brand"> <a href="store-view-feedback.php"><i class="fas fa-star-half-alt"></i><span class="icon-name">Reviews</span></a></li>
                <li class="sidebar-brand"> <a href="store-account-settings.php"><i class="fas fa-user-cog"></i><span class="icon-name">Settings</span></a></li>
            </ul>
        </div>
        <div class="page-content-wrapper">
    <?php
        $records = $data->shopDetails($userID);
        foreach($records as  $val){

            //open hour
            $openTime = $val['opening'];
            $createdate = date_create($openTime);
            $Timeopen = date_format($createdate, "h:i a");

            //close hour
            $closeTime = $val['closing'];
            $createdate = date_create($closeTime);
            $Timeclose = date_format($createdate, "h:i a");
    ?>
    <div class="store-profile-div">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-7 col-lg-8 profile-col">
                    <div class="profile-image-div"><img class="img" src="assets/img/profiles/<?= $val['user_image'] ?>"></div>
                    <div class="profile-name-div">
                        <h5><?= $val['station_name'].' '.$val['branch_name'] ?></h5>
                        <p style="font-weight: 600;"><?php echo $val['station_address']?></p>
                        <p class="rating"><i class="far fa-star"></i>Rating:<span<?=$val['userID']?>"><span><?= number_format($totalRate, 1)?> (<?= $count ?> Rating)</span></p>
                        <div class="sched-div 24hrs"><i class="far fa-clock"></i>
                            <p class="open">
                                <span style="font-weight: 500;">
                                <?php
                                    if($val['opening'] == "00:00:00" && $val['closing'] == "00:00:00"){
                                        echo "24 hours Open"; 
                                    }else{
                                        echo $Timeopen . ' to ' . $Timeclose;
                                    }
                                ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-5 col-lg-4 profile-button">
                    <div class="message-div"><a class="btn message-btn" role="button" href="store-view-feedback.php?stationID=<?php echo $val['userID']; ?>">View Ratings</a></div>
                    <div class="rate-div"><a class="btn rate-btn" role="button" href="store-account-settings.php"><i class="fas fa-pen"></i>Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php 
        } 
    ?>
    <div class="container timeline-container">
        <div class="add-fuel-div"><a class="btn" role="button" href="store-add-fuel.php"><i class="fas fa-plus"></i>Add Fuel</a></div>
        <?php
            $records = $data->allFuelPerStation($userID);
            if(empty($records)){
        ?>
        <div class="no-post">
            <p>You don't have post any fuel in your timeline</p>
        </div>
        <?php
            }
            else{
                foreach($records as $bal){
        ?>
        <div class="row feed-row">
            <div class="col-12 feed-head-col">
                <div class="feed-head-img-div">
                    <img src="assets/img/profiles/<?= $bal['user_image'] ?>">
                </div>
                <div class="feed-head-name-div">
                    <p><?= $bal['station_name'].' '.$bal['branch_name'] ?></p>
                </div>
                <div class="feed-head-settings-div"><i class="fas fa-ellipsis-h" data-bs-toggle="dropdown"></i>
                    <div class="dropdown-menu drapdawn">
                        <a class="btn dropdown-item" role="button" href="store-update-myfuel.php?fuelID=<?php echo $bal['fuelID']?>">Edit</a>
                        <button class="btn dropdown-item DeleteFuel" value="<?php echo $bal['fuelID']?>">Delete</button>
                    </div>
                </div>
            </div>
            <div class="col-12 feed-body-col">
                <div class="feed-body-div">
                    <img class="fuel-img" src="assets/img/products/<?php echo $bal['fuel_image'] ?>">
                    <div class="fuel-details-div">
                        <h1 class="fuel-name"><?php echo $bal['fuel_type'] ?></h1>
                        <?php
                            if(empty($bal['old_price'])){
                        ?>
                        <div class="price-div">
                            <h1>₱<?php echo $bal['new_price'] ?></h1>
                        </div>
                        <?php
                            }else{
                        ?>
                        <div class="price-div">
                            <h1>₱<?php echo $bal['old_price'] ?></h1>
                            <i class="icon ion-arrow-right-a"></i>
                            <h1>₱<?php echo $bal['new_price'] ?></h1>
                            <?php
                                if($bal['new_price'] > $bal['old_price']){
                            ?>
                            <div class="price-change-div up">
                                <i class="icon ion-arrow-up-a arrow-up"></i>
                                <p>+<?php echo number_format($bal['new_price'] - $bal['old_price'], 2) ?></p>
                            </div>
                            <?php
                                }elseif($bal['new_price'] < $bal['old_price']){
                            ?>
                            <div class="price-change-div down">
                                <i class="icon ion-arrow-down-a arrow-up"></i>
                                <p><?php echo number_format($bal['new_price'] - $bal['old_price'], 2) ?></p>
                            </div>
                            <?php
                                }
                            ?>
                        </div>
                        <?php
                            }
                            $date = $bal['date_updated'];
                            $createdate = date_create($date);
                            $new_date = date_format($createdate, "F d, Y");
                        ?>
                        <p class="date-p">Price as of <?php echo $new_date ?></p>
                        <p class="status-p"><span>Status:</span><?php echo $bal['fuel_status']?></p>
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
    <script src="assets/js/Profile-edit-form.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <script>    
        <?php 
        if(isset($_SESSION['message'])) {?>
        //SUCCESS
        Swal.fire({
            title: 'Successfully!',
            text: '<?php echo $_SESSION['message']?>',
            icon: 'success',
            button: true
        });
        <?php 
        unset($_SESSION['message']);
        }

        if(isset($_SESSION['info_message'])) 
        { ?>
    
        //NOTIFY 
        Swal.fire({
            title: 'Oops...',
            text: '<?php echo $_SESSION['info_message']?>',
            icon: 'info',
            button: true
        });

        <?php 
        unset($_SESSION['info_message']);
        }?>

        //CONFIRMATION TO DELETE A FUEL
        $('.DeleteFuel').click(function (e) { 
            e.preventDefault();
            var value = $(this).val();
            Swal.fire({
                title: 'Confirmation',
                text: "Do you want to delete this fuel in your timeline?",
                icon: 'question',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    // alert(value);
                    $.ajax({
                        type: "GET",
                        url: "assets/includes/deleteFuel-inc.php",
                        data: "fuelID=" + value,
                        success: function (data) {
                            Swal.fire({
                                title: 'Successfully!',
                                text: 'Fuel deleted successfully!',
                                icon: 'success',
                                button: true
                            }).then(() => {
                                location.reload();
                            });
                        }
                    });
                }
            })        
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