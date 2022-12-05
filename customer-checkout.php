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
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-checkout.css">
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
                <li class="sidebar-brand"> <a href="customer-home.php"><i class="fas fa-home"></i><span class="icon-name">Home</span></a></li>
                <li class="sidebar-brand"> <a href="customer-map.php"><i class="fas fa-map-pin"></i><span class="icon-name">Map</span></a></li>
                <li class="sidebar-brand"> <a href="customer-products.php"><i class="fas fa-tags"></i><span class="icon-name">Products</span></a></li>
                <li class="sidebar-brand"> 
                    <a href="customer-cart.php">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="icon-name">Cart</span>
                    </a>
                    <?php
                    $cartRemaining = $data->cartRemainingItems($userID, $userID);
                    if ($cartRemaining != 0) { ?>
                        <sup><?php echo $cartRemaining ?></sup>
                    <?php
                    } ?>
                </li>
                <li class="sidebar-brand"> <a href="customer-my-order.php"><i class="fas fa-shopping-bag"></i><span class="icon-name">My Orders</span></a></li>
                <li class="sidebar-brand"> <a href="customer-account-settings.php"><i class="fas fa-user-cog"></i><span class="icon-name">My Account</span></a></li>
            </ul>
        </div>
        <div class="page-content-wrapper">
            <form action = "assets/includes/checkout-inc.php" method="post">
            <div id="kart-container">
                <div id="product-header">
                    <div class="col-6 head-col">
                        <p class="produkto">Product</p>
                    </div>
                    <div class="col-2 head-col">
                        <p>Unit Price</p>
                    </div>
                    <div class="col-2 head-col">
                        <p>Quantity</p>
                    </div>
                    <div class="col-2 head-col">
                        <p>Total Price</p>
                    </div>
                </div>

                    <?php
                    $stations = $data->divideShopsCheckout($userID);

                    $grandtotal = 0;
                    $count = 0;

                    foreach($stations as $station){
                        $shopID = $station['shopID'];
                        $records = $data->sortCartCheckOut($userID, $shopID);
                        $sellers = $data->cartGetShop($shopID, $userID);
                        $shops = !empty($sellers) ? $sellers[0] : null;
                        $count = $data->countCartProductsCheckout($shopID, $userID);
                        $ordtotal = 0;
                    ?>
                    <div class="prodak">
                        <div class="seller-name">
                            <a href="customer-viewstore-timeline.php?stationID=<?php echo $shops['userID'] ?>">
                                <i class="fas fa-store"></i>
                                <span style="color: rgb(33, 37, 41);">&nbsp;<?=$shops['firstname'].' '.$shops['lastname'];?></span><br>
                            </a>
                        </div>
                        <?php
                                foreach($records as $val){
                                $subtotal = $val['price'] * $val['quantity'];
                                $ordtotal += $val['price'] * $val['quantity'];
                        ?>
                        <div class="sa-products">     
                            <div class="product-col">
                                <div class="imeyds-div">
                                        <a href="customer-view-products.php?stationID=<?=$val['shopID']?>&&prodID=<?=$val['productID']?>"><img class=".product-img" src="assets/img/products/<?=$val['prod_image'];?>"></a>
                                </div>
                                <div class="neym-div">
                                    <p class="product-name"><?=$val['product_name'];?></p>
                                </div>
                                <div class="unit-price-div">
                                    <span>₱<?=number_format($val['price'], 2);?></span>
                                </div>
                                <div class="quantity-div">
                                    <span>x<?=$val['quantity'];?></span>
                                </div>
                                <div class="total-price-div">
                                    <span>₱<?=number_format($subtotal, 2);?></span>
                                </div>
                            </div>   
                        </div>
                        <?php  } ?>
                        <div class="order-total-item">
                            <span>Order Total (<?=$count;?> item): </span>
                            <span class="order-total-span">₱<?=number_format($ordtotal, 2);?></span>
                        </div>
                    </div>
                    <?php $grandtotal += $ordtotal; }?>
            
                
                <div id="checkout">
                    <div class="payment-method-div">
                        <p>Payment Method:</p>
                        <!-- <select name="payment">
                            <option value="Cash Upon Pickup" selected>Cash upon pickup</option>
                        </select> -->
                        <div>
                            <p style="color: black; font-weight: 600;">Cash upon pickup</p>
                        </div>
                    </div>
                    <div class="place-order-div">
                        <div class="total-div">
                            <p>Total Payment:</p>
                            <p class="total-p">₱<?=number_format($grandtotal, 2);?></p>
                        <?php
                            if($count != 0){
                        ?>
                        </div><button class="btn" type="submit" name="checkout">Place Order</button>
                        <?php
                        }
                        else{
                        ?>
                        </div><button class="btn disabled" type="submit" name="checkout">Place Order</button>
                        <?php
                        }
                        ?>
                    </div>
                </div>  
            </div>
            </form>
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