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

$stars = $data->editFeedback($station, $userID);
if(!empty($stars)){
    $fidback = $stars[0];
}
else{
    $fidback = $stars;
}

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
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/material-icons.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-navigation.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-viewstore-allproducts.css">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand sticky-top" id="top">
        <div class="container"><a class="btn" role="button" id="menu-toggle" href="#menu-toggle"><i class="fa fa-bars"></i></a><a class="navbar-brand"><i class="fas fa-gas-pump"></i>&nbsp;FUEL ON</a>
            <ul class="navbar-nav">
            <?php require_once('notifications-div.php'); ?>
                <li class="nav-item" id="mail">
                    <p class="badge message-counter"></p>
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
            </ul>
        </div>
        <div class="page-content-wrapper">
    <div class="store-profile-div">
    <?php
            $records = $data->shopDetails($station);
            foreach($records as $val){

                //open hour
                $openTime = $val['opening'];
                $createdate = date_create($openTime);
                $Timeopen = date_format($createdate, "h:i a");

                //close hour
                $closeTime = $val['closing'];
                $createdate = date_create($closeTime);
                $Timeclose = date_format($createdate, "h:i a");
        ?>
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-7 col-lg-8 profile-col">
                    <div class="profile-image-div"><img class="img" src="assets/img/profiles/<?= $val['user_image'] ?>"></div>
                    <div class="profile-name-div">
                        <h5><?= $val['station_name'].' '.$val['branch_name'] ?></h5>
                        <p style="font-weight: 600;"><?php echo $val['station_address']?></p>
                        <p class="rating">
                            <i class="far fa-star"></i>Rating:
                            <a href="customer-view-feedback.php?stationID=<?=$val['userID']?>">
                            <span><?= number_format($totalRate, 1)?> (<?= $count ?> Rating)</span></a>
                        </p>
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
                    <div class="message-div">
                        <a class="btn message-btn" type="button" href="chat-box.php?userID=<?=$val['userID']?>&userType=<?=$val['user_type']?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-message-dots">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M4 21v-13a3 3 0 0 1 3 -3h10a3 3 0 0 1 3 3v6a3 3 0 0 1 -3 3h-9l-4 4"></path>
                                <line x1="12" y1="11" x2="12" y2="11.01"></line>
                                <line x1="8" y1="11" x2="8" y2="11.01"></line>
                                <line x1="16" y1="11" x2="16" y2="11.01"></line>
                            </svg>Message
                        </a>
                    </div>
                    <div class="rate-div">
                        <?php
                            $transac = $data->checkTransacUser($station, $userID); 
                            $ratings = $data->checkRatings($userID, $station);
                            
                            if($transac == 0){
                        ?>
                            <a class="btn rate-btn check-rate" role="button">Rate Store</a>
                        <?php
                            }
                            else{
                                if(empty($ratings)){
                        ?>
                            <a class="btn rate-btn" role="button" href="#ratestoremodal" data-bs-toggle="modal">Rate Store</a>
                        <?php
                                }else{
                        ?>
                            <a class="btn rate-btn" role="button" data-bs-toggle="modal" data-bs-target="#editratestoremodal<?php echo $fidback['ratingID']?>">Edit rating</a>
                        <?php
                            include 'customer-edit-feedback.php';
                                }
                            }
                        ?>
                    </div>
                    <div>
                        <!-- ETO PANG ADD NG FEEDBACK MODAL-->
                        <form action="assets/includes/insert-feedback-inc.php?shopID=<?php echo $val['userID']?>" method="post">
                            <div class="modal fade" role="dialog" tabindex="-1" id="ratestoremodal">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <div class="head-title">
                                                <h5>Leave a rating</h5>
                                            </div><button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="star-widget-container">
                                                <div class="star-widget">
                                                    <input type="radio" id="rate-5" name="rate" value="5"><label class="form-label fas fa-star" for="rate-5"></label>
                                                    <input type="radio" id="rate-4" name="rate" value="4"><label class="form-label fas fa-star" for="rate-4"></label>
                                                    <input type="radio" id="rate-3" name="rate" value="3"><label class="form-label fas fa-star" for="rate-3"></label>
                                                    <input type="radio" id="rate-2" name="rate" value="2"><label class="form-label fas fa-star" for="rate-2"></label>
                                                    <input type="radio" id="rate-1" name="rate" value="1"><label class="form-label fas fa-star" for="rate-1"></label>
                                                </div>
                                            </div>
                                            <div class="text-area"><textarea class="form-control" name="feedback" placeholder="Describe your experience..."></textarea></div>
                                        </div>
                                        <div class="modal-footer"><button class="btn" type="submit" name="submit1">Submit</button></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- END -->
                    </div>
                </div>
                <div class="col nav-col">
                    <ul class="nav">
                        <li class="nav-item"><a class="nav-link" href="customer-viewstore-timeline.php?stationID=<?php echo $val['userID']; ?>">Timeline</a></li>
                        <li class="nav-item active"><a class="nav-link active active-name" href="customer-viewstore-allproducts.php?stationID=<?php echo $val['userID']; ?>">All Products</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <?php     
    }
        $withStock = $data->allProductsWithStock($station);
        $noStock = $data->allProductsNoStock($station);

        if(empty($withStock)){
    ?>
    <div class="container" id="no-products">
        <div class="no-prod-div">
            <div class="icons"><i class="fas fa-question"></i><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -64 640 640" width="1em" height="1em" fill="currentColor">
                    <!--! Font Awesome Free 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2022 Fonticons, Inc. -->
                    <path d="M75.23 33.4L320 63.1L564.8 33.4C571.5 32.56 578 36.06 581.1 42.12L622.8 125.5C631.7 143.4 622.2 165.1 602.9 170.6L439.6 217.3C425.7 221.2 410.8 215.4 403.4 202.1L320 63.1L236.6 202.1C229.2 215.4 214.3 221.2 200.4 217.3L37.07 170.6C17.81 165.1 8.283 143.4 17.24 125.5L58.94 42.12C61.97 36.06 68.5 32.56 75.23 33.4H75.23zM321.1 128L375.9 219.4C390.8 244.2 420.5 255.1 448.4 248L576 211.6V378.5C576 400.5 561 419.7 539.6 425.1L335.5 476.1C325.3 478.7 314.7 478.7 304.5 476.1L100.4 425.1C78.99 419.7 64 400.5 64 378.5V211.6L191.6 248C219.5 255.1 249.2 244.2 264.1 219.4L318.9 128H321.1z"></path>
                </svg></div>
            <h5>It seems like they don't have any registered products yet</h5>
        </div>
    </div>
    <?php }
        else{
            $shop = $withStock[0];
    ?>
    <div class="container filter-div">
        <div class="row g-0">
            <div class="col-6 sort">
                <p>Sort by</p>
                <select name="price" class="sortPrice" id="<?php echo $shop['shopID']?>">
                    <option selected disabled>Price</option>
                    <option value="low">Price: Lowest</option>
                    <option value="high">Price: Highest</option>
                </select>
            </div>
            <div class="col-6 search">
                <input type="text" placeholder="Search product here..." id="<?php echo $shop['shopID']?>" class="serts">
            </div>
        </div>
    </div>
    <div class="container products-container">
        <div class="row g-0 pradak">
            <div class="row g-0 with-stock">
                <?php
                    foreach($withStock as $val){
                        $sold = $data->countShopSold($val ['productID']);
                ?>
                <div class="col-6 col-sm-6 col-md-4 col-lg-3 kolum">
                    <a href="customer-view-products.php?prodID=<?php echo $val['productID']?>&stationID=<?php echo $val['shopID'] ?>">
                        <div class="product-div">
                            <div class="product-image-div"><img class="img" src="assets/img/products/<?= $val['prod_image']?>"></div>
                            <div class="product-desc-div">
                                <h6 class="prod-name"><?= $val['product_name']?></h6>
                                <div class="price-sold-div">
                                    <p class="prod-price"><?= "₱".$val['price']?></p>
                                    <p class="sold"><?php echo $sold?> sold</p>
                                </div>
                                <p class="prod-location">Stocks:<?= ' '.$val['quantity']?></p>
                            </div>
                        </div>
                    </a>
                </div>
                <?php } ?>          
            </div>
        </div>
        <!-- SOLD OUT CONTAINER-->
        <?php
            if(!empty($noStock)){
        ?>
        <div class="soldout-div"><h5>Out of stock</h5></div>
        <?php
            }
        ?>
        <div class="row g-0 no-stock">
            <?php
                foreach($noStock as $val){
                    $sold = $data->countShopSold($val ['productID']);
            ?>
            <div class="col-6 col-sm-6 col-md-4 col-lg-3 kolum">
                <a href="customer-view-products.php?prodID=<?php echo $val['productID']?>&stationID=<?php echo $val['shopID'] ?>">
                    <div class="product-div">
                        <div class="product-image-div">
                            <img class="img no-stock-img" src="assets/img/products/<?= $val['prod_image']?>">
                            <div class="sold-out-div"><p>Out of stock</p></div>
                        </div>
                        <div class="product-desc-div">
                            <h6 class="prod-name"><?= $val['product_name']?></h6>
                            <div class="price-sold-div">
                                <p class="prod-price"><?= "₱".$val['price']?></p>
                                <p class="sold"><?php echo $sold?> sold</p>
                            </div>
                            <p class="prod-location">Stocks:<?= ' '.$val['quantity']?></p>
                        </div>
                    </div>
                </a>
            </div>
            <?php } ?>          
        </div>  
    </div>
    </div>
    </div>
    <?php    
    }
    ?>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/editproduct.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/popup.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/Table-With-Search.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <script>
        $(document).ready(function() {
            $(".serts").on("input", function() {
                var searchText = $(this).val();
                var id = $(this).attr('id');

                if (searchText == "") return;
                $.ajax({
                    type: "POST",
                    url: "assets/ajax/productsearchperstore.php",
                    data: {key: searchText, shopID: id},
                    success: function (data, status) {
                        $(".pradak").html(data);
                    }
                });
            });


            $(".sortPrice").on('change', function(){
                var value = $(this).val();
                var id = $(this).attr('id');
                // alert(value);
                // alert(id);
                $.ajax({
                    url: "assets/ajax/sortProductperstore.php",
                    type: "POST",
                    data: {request: value, shopID: id},
                    beforeSend:function(){
                        $(".pradak").html("<span>Loading...</span>");
                    },
                    success:function(data){
                        $(".pradak").html(data);
                    }
                });
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


        $('.check-rate').click(function () { 
            Swal.fire({
                title: 'Oops...',
                text: 'You must complete at least 1 transaction with this station.',
                icon: 'info',
                button: true
            });
        });
    </script>
</body>

</html>