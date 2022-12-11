<?php
session_start();

if(isset($_SESSION['userID'])){
    $userID = $_SESSION['userID'];
    $username = $_SESSION['fname'];
    $userpic = $_SESSION['userPic'];
    $userType = $_SESSION['userType'];

    if($userType == 2)
    { 
        header('location: index.php');
    }
    elseif($userType == 0)
    { 
        header('location: index.php');
    }
}
else{
    header('location: index.php');
}

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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Share+Tech+Mono&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-home.css">
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
                <li class="sidebar-brand"> <a class="actives" href="customer-home.php"><i class="fas fa-home"></i><span class="icon-name">Home</span></a></li>
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
                <li class="sidebar-brand"> 
                    <a href="customer-my-order.php">
                        <i class="fas fa-shopping-bag"></i><span class="icon-name">My Orders</span>
                    </a>
                    <?php
                    $orderCounter = $data->AllOrdersCountCustomer($userID);
                    if($orderCounter != 0){?>
                        <sup style="margin-left: 52px;"><?php echo $orderCounter ?></sup>
                    <?php
                    }?>
                </li>
                <li class="sidebar-brand"> <a href="customer-account-settings.php"><i class="fas fa-user-cog"></i><span class="icon-name">My Account</span></a></li>
            </ul>
        </div>
        <div class="page-content-wrapper">
            <div class="container contener">
                <div class="sliders">
                    <div class="slider-head">
                        <a href="#">
                            <h5>Gasoline Stations</h5>
                        </a>
                    </div>
                    <div class="slider-body">
                        <div class="slider-wrapper">
                        <?php
                            $records = $data->allShops();
                            foreach($records as $val){
                        ?>
                            <div class="inside-body-slider">
                                <a href="customer-viewstore-timeline.php?stationID=<?php echo $val['userID']; ?>">
                                    <div class="image-div">
                                        <img src="assets/img/profiles/<?php echo $val['user_image'] ?>" alt="Gas Station Logo">
                                    </div>
                                    <div class="name-box">
                                        <p><?php echo $val['station_name'] ?> <?php echo $val['branch_name'] ?></p>
                                    </div>
                                </a>
                            </div>            
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
                <div class="search-div">
                    <div class="input-group">
                        <input class="form-control autocomplete" type="text" placeholder="Search fuel name or fuel type here..." id="search">
                        <button class="btn" type="button" id="searchbtn"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <div class="dito">
                <div class="sort-div"><span>Sort by</span>
                    <div class="sort-options">
                        <button class="btn sort-btn active" type="button" id="all" value="all">All types</button>
                        <button class="btn sort-btn" type="button" id="diesel" value="diesel">Diesel</button>
                        <button class="btn sort-btn" type="button" id="unleaded" value="unleaded">Unleaded</button>
                        <button class="btn sort-btn" type="button" id="premium" value="premium">Premium</button>
                    </div>
                </div>
                <div class="timeline-container">
                    <div class="display">
                    <?php
                        $allfuels = $data->productsFuelAll();
                            foreach($allfuels as $fuel){ 
                    ?>
                        <div class="row feed-row">
                            <div class="col-12 feed-head-col">
                                <div class="feed-head-img-div"><img src="assets/img/profiles/<?php echo $fuel['user_image'] ?>"></div>
                                <div class="feed-head-name-div">
                                    <a href="customer-viewstore-timeline.php?stationID=<?php echo $fuel['shopID']; ?>">
                                        <?= $fuel['station_name'].' '.$fuel['branch_name'] ?>
                                    </a>
                                </div>
                            </div>  
                            <div class="col-12 feed-body-col">
                                <div class="feed-body-div">
                                    <img class="fuel-img" src="assets/img/products/<?php echo $fuel['fuel_image'] ?>">
                                    <div class="fuel-details-div">
                                        <h1 class="fuel-name"><?php echo $fuel['fuel_type'] ?></h1>
                                        <?php
                                            if(empty($fuel['old_price'])){
                                        ?>
                                        <div class="price-div">
                                            <h1>₱<?php echo $fuel['new_price'] ?></h1>
                                        </div>
                                        <?php
                                            }
                                            else{
                                        ?>
                                        <div class="price-div">
                                            <h1>₱<?php echo $fuel['old_price'] ?></h1>
                                            <i class="icon ion-arrow-right-a"></i>
                                            <h1>₱<?php echo $fuel['new_price'] ?></h1>
                                            <?php
                                                if($fuel['new_price'] > $fuel['old_price']){
                                            ?>
                                            <div class="price-change-div up"><i class="icon ion-arrow-up-a arrow-up"></i>
                                                <p>+<?php echo number_format($fuel['new_price'] - $fuel['old_price'], 2) ?></p>
                                            </div>
                                            <?php
                                                }elseif($fuel['new_price'] < $fuel['old_price']){
                                            ?>
                                            <div class="price-change-div down"><i class="icon ion-arrow-down-a arrow-up"></i>
                                                <p><?php echo number_format($fuel['new_price'] - $fuel['old_price'], 2) ?></p>
                                            </div>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                        <?php
                                            }
                                            $date = $fuel['date_updated'];
                                            $createdate = date_create($date);
                                            $new_date = date_format($createdate, "F d, Y");
                                        ?>
                                        <p class="date-p">Price as of <?php echo $new_date ?></p>
                                        <p class="status-p"><span>Status:</span><?php echo $fuel['fuel_status']?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                        }
                    ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var stars = document.querySelectorAll(".sort-btn");
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
    <script src="assets/js/sortFuel.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <!-- <script src="assets/js/Search.js"></script> -->
    <script>
    $(document).ready(function(){
       $("#search").on("input", function(){
       	 var searchText = $(this).val();
        //  alert (searchText);

         if(searchText == "") return;
         $.post('assets/ajax/homesearch.php', 
         	     {
         	     	key: searchText
         	     },
         	   function(data, status){
                  $(".display").html(data);
         	});
       });
    
       $("#searchbtn").on("click", function(){
       	 var searchText = $("#search").val();
        // alert (searchText);
         if(searchText == "") return;
         $.post('assets/ajax/homesearch.php', 
         	     {
         	     	key: searchText
         	     },
         	   function(data, status){
                  $(".display").html(data);
         	 });
        });
    });

    <?php
    if (isset($_SESSION['message'])) { ?>
        const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
        })

        Toast.fire({
        icon: 'success',
        title: '<?php echo $_SESSION['message'] ?>'
        })
    <?php
        unset($_SESSION['message']);
    }
    if (isset($_SESSION['verify_message'])) {
    ?>
        const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
        })

        Toast.fire({
        icon: 'success',
        title: '<?php echo $_SESSION['verify_message'] ?>'
        })
    <?php
        unset($_SESSION['verify_message']);
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