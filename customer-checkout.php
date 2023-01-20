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
    <title>Fuel ON | Checkout</title>
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
    <?php
        //top navigation
        include 'top-navigation.php';
    ?>
    <div id="wrapper">
        <?php
            //side navigation
            include 'side-navigation.php';
        ?>
        <div class="page-content-wrapper">
            <form action="assets/includes/checkout-inc.php" method="post" enctype="multipart/form-data">
            <div id="kart-container">
                <div class="title-div">
                    <a class="back">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h4>Checkout</h4>
                </div>
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
                    $subtotal = 0;
                    $taxrate = 0.12;
                    $vat = 0;
                    $count = 0;
                    
                    foreach($stations as $station){
                        $shopID = $station['shopID'];
                        $records = $data->sortCartCheckOut($userID, $shopID);
                        $sellers = $data->cartGetShop($shopID, $userID);
                        $shops = $sellers[0];
                        $count = $data->countCartProductsCheckout($shopID, $userID);
                        $ordtotal = 0;
                    ?>
                    <div class="prodak">
                        <div class="seller-name">
                            <a>
                                <i class="fas fa-store"></i>
                                <?=$shops['station_name'].' '.$shops['branch_name'];?>
                            </a>
                        </div>
                        <?php
                                foreach($records as $val){
                                $ordtotal += $val['price'] * $val['quantity'];
                        ?>
                        <div class="sa-products">     
                            <div class="product-col">
                                <div class="imeyds-div">
                                    <a><img class="product-img" src="assets/img/products/<?=$val['prod_image'];?>"></a>
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
                    <?php
                        $subtotal += $ordtotal;
                        $vat = $subtotal * $taxrate;
                        $grandtotal = $subtotal + $vat; 
                    }
                    ?>
                    <div class="row g-0" id="checkout">
                        <div class="col-12">
                            <div class="row g-0 total-row">
                                <div class="col-7 col-sm-9 left-col subtotal"><span>Order Subtotal</span></div>
                                <div class="col-5 col-sm-3 subtotal">
                                    <p>₱<?=number_format($subtotal, 2)?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row g-0 total-row">
                                <div class="col-7 col-sm-9 left-col"><span>VAT (12%)</span></div>
                                <div class="col-5 col-sm-3">
                                    <p>₱<?=number_format($vat, 2)?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row g-0 total-row">
                                <div class="col-7 col-sm-9 left-col"><span>Total Payment</span></div>
                                <div class="col-5 col-sm-3">
                                    <p class="order-total-p">₱<?=number_format($grandtotal, 2)?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col place-order-div">
                            <div class="payment-method-div"><span>Payment Method:</span>
                                <p>Cash upon pickup</p>
                            </div>
                            <?php
                                if($count != 0){
                            ?>
                            <button class="btn" type="submit" name="checkout">Place Order</button>
                            <?php
                                }else{
                            ?>
                            <button class="btn disabled" type="submit" name="checkout">Place Order</button>
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

        $('.back').click(function () { 
            window.history.back();
        });
    </script>
</body>
</html>