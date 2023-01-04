<?php
session_start();
if (isset($_SESSION['userID'])) {
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
} else {
    header('location: index.php');
}

require_once("assets/classes/dbHandler.php");
$data = new Config();

$cartItemCount = $data->cartTotalItems($userID);
$cartItemCountwithQuantity = $data->cartTotalItemswithQuantity($userID);
$cartChecked = $data->cartAllChecked($userID);
$cartCheckedwithQuant = $data->cartAllCheckedwithQuant($userID);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | Cart</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/material-icons.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-cart.css">
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
            <?php
            $cart= $data->displayCart($userID);
            if (empty($cart)) {
            ?>
                <div class="container" id="no-items-in-cart">
                    <div class="divv">
                        <img src="assets/img/cart2.png"/>
                        <p>Your cart is empty</p>
                        <a class="btn" href="customer-products.php">View Products</a>
                    </div>
                </div>
            <?php
            } else {
            ?>
            <div id="kart-container" class="container product-container">
                <div class="title-div">
                    <h4>Shopping Cart</h4>
                </div>
                <div id="product-header">
                    <div class="col-5 head-col">
                        <p class="produkto">Product</p>
                    </div>
                    <div class="col-2 head-col heder">
                        <p>Unit Price</p>
                    </div>
                    <div class="col-2 head-col heder">
                        <p>Quantity</p>
                    </div>
                    <div class="col-2 head-col heder">
                        <p>Total Price</p>
                    </div>
                    <div class="col head-col heder">
                        <p>Action</p>
                    </div>
                </div>
                <div class="dine">
                    <?php
                    //to divide the shops in cart
                    $stations = $data->divideShops($userID);
                    $grandtotal = 0;
                    $i = 0;
                    $selectAllChecked = 'checked';
                    foreach ($stations as $station) {
                        //to get the station details 
                        $sellers = $data->cartGetShop($station['shopID'], $userID);
                        $shops = $sellers[0];
                        
                        //to display all the added products in cart per shop
                        $records = $data->sortCartwithStock($userID, $station['shopID']);
                    
                        //Check if all shop products are checked
                        $shopAllChecked = 'checked';
                        foreach ($records as $sortcart) {
                            if ($sortcart['checked'] == 0){
                                $shopAllChecked = '';
                                $selectAllChecked = ''; 
                            }
                        }
                
                        if(!empty($records)){
                            //if all products under the shop is out of stock, the seller name div is hidden else, visible
                            $cartdata = $records[0];
                            if($cartdata['stocks'] != 0){
                    ?>
                    <div class="prodak prodak-with-stock">
                        <div class="seller-name">
                            <!-- Select all in shop -->
                            <div class="checkbox-div">
                                <input <?= $shopAllChecked ?> type="checkbox" name="check" class="tsekbox selectall-in-shop">
                                <input type="hidden" class="shopID" value="<?= $shops['shopID'] ?>">
                            </div>
                            <a href="customer-viewstore-timeline.php?stationID=<?php echo $shops['shopID']?>">
                                <i class="fas fa-store"></i>
                                <?php echo $shops['station_name'] . ' ' . $shops['branch_name']; ?>
                            </a>
                            <a class="message-icon" href="chat-box.php?userID=<?=$shops['shopID']?>&userType=<?=$shops['user_type']?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-message">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M4 21v-13a3 3 0 0 1 3 -3h10a3 3 0 0 1 3 3v6a3 3 0 0 1 -3 3h-9l-4 4"></path>
                                    <line x1="8" y1="9" x2="16" y2="9"></line>
                                    <line x1="8" y1="13" x2="14" y2="13"></line>
                                </svg>
                            </a>
                        </div>  
                        <?php
                        //to show all products in cart per shop 
                        foreach ($records as $val) {
                            $subtotal = $val['price'] * $val['quantity'];
                            
                            // Only include to total if checked
                            if ($val['checked'] == 1)
                                $grandtotal += $val['price'] * $val['quantity'];
                                $checked = $val['checked'] == 1 ? 'checked' : '';

                            //set the quantity equal to the avail stocks if the stocks is below or equal to the added quantity of the user
                            if($val['stocks'] < $val['quantity']){
                                $data->setCartQuantityEqualtoStock($val['stocks'], $val['productID'], $userID);
                            }                
                            
                            // set the quantity to 1 if the stocks is returned
                            if($val['quantity'] == 0 && $val['stocks'] != 0){
                                $data->setCartQuantityOne($val['productID']);   
                            }
                        ?>
                        <div class="sa-products product-data">
                            <input type="hidden" class="product-id" value="<?php echo $val['productID']?>">
                            <div class="form">
                                <div class="checkbox-div">
                                    <input type="checkbox" class="tsekbox select-one" name="check" <?= $checked ?>/>
                                </div>
                                <div class="product-col">
                                    <div class="imeyds-div">
                                        <a href="customer-view-products.php?stationID=<?php echo $val['shopID']; ?>&&prodID=<?php echo $val['productID'] ?>">
                                            <img class="product-img" src="assets/img/products/<?php echo $val['prod_image']; ?>">
                                        </a>
                                    </div>
                                    <div class="neym-div">
                                        <a class="product-name" href="customer-view-products.php?stationID=<?php echo $val['shopID']; ?>&&prodID=<?php echo $val['productID'] ?>"><?php echo $val['product_name']; ?></a>
                                    </div>
                                    <div class="unit-price-div">
                                        <span class="price" data-price="<?= $val['price'] ?>">₱<?= number_format($val['price'], 2) ?></span>
                                    </div>
                                    <div class="quantity-container">
                                        <div class="input-group quantity-div">
                                            <button class="btn decrement-btn update-quantity" type="button"><i class="fas fa-minus"></i></button>
                                            <input class="form-control quantity" type="number" value="<?= $val['quantity'] ?>">
                                            <button class="btn increment-btn update-quantity" type="button"><i class="fas fa-plus"></i></button>
                                        </div>
                                        <p class="with-stocks" data-stocks="<?php echo $val['stocks']?>"><?php echo $val['stocks']?> items left</p>
                                    </div>
                                    <div class="total-price-div">
                                        <span class="subtotal">₱<?=number_format($subtotal, 2)?></span>
                                    </div>
                                    <div class="del-prod-div">
                                        <a class="btn deleteOne" href="assets/includes/deleteOneinCart-inc.php?cartID=<?=$val['cartID']?>">
                                            <i class="far fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <?php
                        }
                        ?>
                        <div class="empty-div"></div>
                    </div>
                    <?php
                            }
                        }
                    }
                    $nostock = $data->sortCartnoStock($userID);
                    if(!empty($nostock)){
                    ?>
                    <div class="prodak no-stock">
                        <div class="seller-name">
                            <div class="checkbox-div">
                                <input type="checkbox">
                            </div>
                            <p class="no-stock-p">Out of stock</p>
                        </div>  
                        <?php
                            foreach($nostock as $val){
                                $subtotal = $val['price'] * $val['quantity'];
                                $data->setCartQuantityZero($val['productID']);
                        ?>
                        <div class="sa-products no-stock">
                            <div class="form">
                                <div class="sold-out-div">
                                    <p>No Stock</p>
                                </div>
                                <div class="product-col">
                                    <div class="imeyds-div">
                                        <a href="customer-view-products.php?stationID=<?php echo $val['shopID']; ?>&&prodID=<?php echo $val['productID'] ?>">
                                            <img class="product-img" src="assets/img/products/<?php echo $val['prod_image']; ?>">
                                        </a>
                                    </div>
                                    <div class="neym-div">
                                        <a class="product-name" href="customer-view-products.php?stationID=<?php echo $val['shopID']; ?>&&prodID=<?php echo $val['productID'] ?>"><?php echo $val['product_name']; ?></a>
                                    </div>
                                    <div class="unit-price-div no-stock">
                                        <span>₱<?= number_format($val['price'], 2) ?></span>
                                    </div>
                                    <div class="quantity-container no-stock">
                                        <div class="input-group quantity-div">
                                            <button class="btn disabled" type="button"><i class="fas fa-minus"></i></button>
                                            <input class="form-control" type="number" value="<?= $val['quantity'] ?>" disabled>
                                            <button class="btn disabled" type="button"><i class="fas fa-plus"></i></button>
                                        </div>
                                        <p><?php echo $val['stocks']?> items left</p>
                                    </div>
                                    <div class="total-price-div">
                                        <span>₱<?=number_format($subtotal, 2)?></span>
                                    </div>
                                    <div class="del-prod-div">
                                        <a class="btn deleteOne" href="assets/includes/deleteOneinCart-inc.php?cartID=<?=$val['cartID']?>">
                                            <i class="far fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <?php
                            }
                        ?>
                        <div class="empty-div no-stock">
                            <a class="remove-nostock">Remove this product/s</a>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                    <div class="row g-0" id="checkout">
                        <div class="col bottom-action">
                            <div class="checkbox-div">
                                <input type="checkbox" id="select-all" class="tsekbox delete-all" name="check" <?php echo $selectAllChecked?>/>
                                <label for="select-all" class="select-all" data-count="<?=$cartItemCountwithQuantity ?>">Select all (<?php echo $cartItemCountwithQuantity?>)</label>
                                <label for="select-all" class="all">All</label>
                            </div>
                            <?php if ($cartCheckedwithQuant == 0){?>
                            <a class="btn del" role="button" id="no-selected-to-delete"><i class="far fa-trash-alt"></i></a>
                            <?php
                            }else{?>
                            <a class="btn del" role="button" id="deleteAll"><i class="far fa-trash-alt"></i></a>
                            <?php
                            }?>
                        </div>
                        <div class="col-auto checkout-col">
                            <div class="total">
                                <span class="item-count">Total (<?=$cartCheckedwithQuant?> item):&nbsp;</span>
                                <p class="price grand-total">₱<?= number_format($grandtotal, 2); ?></p>
                            </div>
                            <?php 
                                if($cartCheckedwithQuant != 0){
                            ?>
                                <div class="checkout">
                                    <a class="btn btn-primary place-order" role="button" href="customer-checkout.php">Check Out</a>
                                </div>
                            <?php 
                                }else if($cartCheckedwithQuant == 0){
                            ?>
                                <div class="checkout">
                                    <a class="btn btn-primary place-order disabled" role="button" href="customer-checkout.php">Check Out</a>
                                </div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                }
            ?>
        </div>
    </div>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/js/bs-init.js"></script>
<script src="assets/js/cart.js"></script>
<!-- <script src="assets/js/cart-backup.js"></script> -->
<script src="assets/js/Sidebar-Menu.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    <?php
    if (isset($_SESSION['message'])) { ?>
        //SUCCESS
        Swal.fire({
            title: 'Successfully!',
            text: '<?php echo $_SESSION['message'] ?>',
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
    }
    ?>

    $('#no-selected-to-delete').click(function (e) { 
        e.preventDefault();
        Swal.fire({
            text: 'Please select a product(s) to be deleted',
            icon: 'info',
            showConfirmButton: false,
            timer: 2000
        });
    });

    //CONFIRMATION TO REMOVE ALL PRODUCTS IN CART
    $('#deleteAll').click(function() {
        Swal.fire({
            title: 'Confirmation',
            text: "Do you want to remove <?=$cartChecked?> product/s in your cart?",
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: 'No',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                document.location = 'assets/includes/deleteAllinCart-inc.php?type=checked';
            }
        })
    });

    //notif if walang checked item bago click checkout
    $('#checkOUT').click(function() {
        Swal.fire({
            title: 'Oops...',
            text: 'You did not choose any items for checkout',
            icon: 'info',
            button: true
        });
    });

    $('.remove-nostock').click(function () { 
        Swal.fire({
            title: 'Confirmation',
            text: "Do you want to remove inactive products in your cart?",
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: 'No',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                document.location = 'assets/includes/deleteAllinCart-inc.php?type=no-stock';
            }
        })
    });

    // $('#select-all').click(function() {
    //     $.ajax({
    //         type: "POST",
    //         url: "assets/includes/selectAll-inc.php",
    //         dataType: 'json',
    //         data: {
    //             checked: $(this).is(":checked")
    //         },
    //         success: function(data) {
    //             console.log(data);
    //             location.reload();
    //         },
    //         error: function(e) {
    //             console.log(e.response);
    //         }
    //     });
    // });

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

        
        //live fetch cart
        let fetchCart = function(){
      	    $.get("assets/ajax/ajax-cart.php", 
                function(data){
                    // $(".dine").html(data);
                }
            );
        }
        fetchCart();
        //auto update every 1.5 sec
        setInterval(fetchCart, 1500);

</script>
</body>

</html>