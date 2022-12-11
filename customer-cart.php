<?php
session_start();
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION["userID"];
    $username = $_SESSION["fname"];
    $userType = $_SESSION["userType"];
    $userpic = $_SESSION["userPic"];

    if ($userType == 2) {
        header('location: index.php');
    }
} else {
    header('location: index.php');
}

require_once("assets/classes/dbHandler.php");
$data = new Config();

$cartItemCount = $data->cartTotalItems($userID);

$cartChecked = $data->cartAllChecked($userID);
$cartCheckedwithQuant = $data->cartAllCheckedwithQuant($userID);

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
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-cart.css">
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
                    <a class="actives" href="customer-cart.php">
                        <i class="fas fa-shopping-cart"></i><span class="icon-name">Cart</span>
                    </a>
                    <?php if ($cartItemCount != 0) { ?>
                        <sup><?php echo $cartItemCount ?></sup>
                    <?php
                    } ?>
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
            <?php
            $records = $data->displayCart($userID);
            if (empty($records)) {
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
            <div id="kart-container" class="product-container">
                <form method="post">
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
                </form>
            <div id="dine">
                <?php
                //to divide the shops in cart
                $stations = $data->divideShops($userID);
                $grandtotal = 0;
                $i = 0;
            
                $selectAllChecked = 'checked';
                foreach ($stations as $station) {
                    $shopID = $station['shopID'];
                    $records = $data->sortCart($userID, $shopID);
                    $sellers = $data->cartGetShop($shopID, $userID);
                    $shops = $sellers[0];

                    // Check if all shop products are checked
                    $shopAllChecked = 'checked';
                    foreach ($records as $record) {
                        if ($record['checked'] == 0 && $record['quantity'] >= 0){
                            $shopAllChecked = '';
                            $selectAllChecked = ''; 
                        }
                    }
                ?>
                <div class="prodak">
                    <form action="assets/includes/selectDeselect-inc.php" method="post">
                        <div class="seller-name">
                            <!-- Select all per store -->
                            <div class="checkbox-div">
                                <input <?= $shopAllChecked ?> type="checkbox" name="select-all-shop" class="tsekbox quant-input-update cbox-md cbox-store-selectall">
                                <input type="hidden" name="select-all-shop-id" value="<?= $shops['shopID'] ?>">
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
                    </form>
                    <?php
                    //to show all products in cart per shop 
                    foreach ($records as $val) {
                        $subtotal = $val['price'] * $val['quantity'];

                        // Only include to total if checked
                        if ($val['checked'] == 1)
                            $grandtotal += $val['price'] * $val['quantity'];
                            $checked = $val['checked'] == 1 ? 'checked' : '';
                        
  
                        if ($val['stocks'] != 0){
                            //set the quantity equal to the avail stocks if the stocks is below or equal to the added quantity of the user
                            if($val['stocks'] < $val['quantity']){
                                $data->setCartQuantityEqualtoStock($val['stocks'], $val['productID'], $userID);
                            }

                            // set the quantity to 1 if the stocks is returned
                            if($val['quantity'] <= 1){
                                $data->setCartQuantityOne($val['productID']);   
                            }
                    ?>
                    <div class="sa-products">
                        <form id="form-update-cart" action="assets/includes/updateCart-inc.php?prodID=<?php echo $val['productID']?>&quantity=<?= $val['quantity'] ?>" method="post">
                            <div class="checkbox-div">
                                <input class="tsekbox quant-input-update cbox-md" type="checkbox" name="checked" <?= $checked ?>/>
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
                                    <span>₱<?= number_format($val['price'], 2); ?></span>
                                </div>
                                <div class="quantity-div">
                                    <input class="form-control quant-input-update update-quanty" type="number" name="quantity" id="<?php echo $val['productID']?>" value="<?= $val['quantity']; ?>" min=1 max="<?php echo $val['stocks'] ?>">                               
                                    <p class="stocks"><?php echo $val['stocks']?> items left</p>
                                </div>
                                <div class="total-price-div">
                                    <span>₱<?= number_format($subtotal, 2); ?></span>
                                </div>
                                <div class="del-prod-div">
                                    <a class="btn deleteOne" href="assets/includes/deleteOneinCart-inc.php?cartID=<?=$val['cartID']?>">
                                        <i class="far fa-trash-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                        <?php
                        }
                        else{
                            //set the quantity to 0 if the stocks 0
                            $data->setCartQuantityZero($val['productID']);
                        ?>
                    <div class="sa-products no-stock">
                        <form>
                            <div class="sold-out-div">
                                <p>No stock</p>
                            </div>
                            
                            <div class="product-col">
                                <div class="imeyds-div no-stock-img">
                                    <a href="customer-view-products.php?stationID=<?php echo $val['shopID']; ?>&&prodID=<?php echo $val['productID'] ?>">
                                        <img class="product-img" src="assets/img/products/<?php echo $val['prod_image']; ?>">
                                    </a>
                                </div>
                                <div class="neym-div">
                                    <a class="product-name" href="customer-view-products.php?stationID=<?php echo $val['shopID']; ?>&&prodID=<?php echo $val['productID'] ?>"><?php echo $val['product_name']; ?></a>
                                </div>
                                <div class="unit-price-div">
                                    <span>₱<?= number_format($val['price'], 2); ?></span>
                                </div>
                                <div class="quantity-div">
                                    <input class="form-control quant-input-update" type="number" name="quantity" disabled value="<?= $val['quantity']; ?>" min=1 max="<?php echo $val['stocks'] ?>">
                                    <p class="stocks"><?php echo $val['stocks']?> items left</p>
                                </div>
                                <div class="total-price-div">
                                    <span>₱<?= number_format($subtotal, 2); ?></span>
                                </div>
                                <div class="del-prod-div">
                                    <a class="btn deleteOne" style="background: #f8f5f5;" href="assets/includes/deleteOneinCart-inc.php?cartID=<?=$val['cartID']?>">
                                        <i class="far fa-trash-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            <?php
                }
            ?>
                <div class="row g-0" id="checkout">
                    <div class="col bottom-action">
                        <div class="checkbox-div">
                            <input <?php echo $selectAllChecked?> class="tsekbox delete-all" id="select-all" name="select-all" type="checkbox"/>
                            <label for="select-all">Select all (<?php echo $cartItemCount?>)</label>
                        </div>
                        <?php if ($cartChecked == 0){?>
                        <a class="btn del" role="button" id="no-selected-to-delete"><i class="far fa-trash-alt"></i></a>
                        <?php
                        }else{?>
                        <a class="btn del" role="button" id="deleteAll"><i class="far fa-trash-alt"></i></a>
                        <?php
                        }?>
                    </div>
                    <div class="col-auto checkout-col">
                        <div class="total">
                            <span>Total (<?=$cartCheckedwithQuant?> item):&nbsp;</span>
                            <p class="price">₱<?= number_format($grandtotal, 2); ?></p>
                        </div>
                        <?php 
                        if($cartCheckedwithQuant != 0){?>
                            <div class="checkout"><a class="btn btn-primary" role="button" href="customer-checkout.php">Check Out</a></div>
                        <?php 
                        }else if($cartCheckedwithQuant == 0){?>
                        <div class="checkout"><a class="btn btn-primary disabled" role="button" id="checkOUT">Check Out</a></div>
                        <?php
                        }
                        ?>

                    </div>
                </div>
            <!-- end ni dine-->
            </div>
            <?php
            }
            ?>
            </div>
        </div>
    </div>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"></script>
<script src="assets/js/bs-init.js"></script>
<script src="assets/js/cart.js"></script>
<!-- <script src="assets/js/cart-backup.js"></script> -->
<script src="assets/js/Sidebar-Menu.js"></script>
<script src="assets/js/sweetalert2.js"></script>
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

    // //CONFIRMATION TO REMOVE ONE PRODUCTS IN CART
    // $('.deleteOne').click(function (e) { 
    //     e.preventDefault();
    //     var value = $(this).val();
    //     Swal.fire({
    //         title: 'Confirmation',
    //         text: "Do you want to remove this product in your cart?",
    //         icon: 'question',
    //         showCancelButton: true,
    //         cancelButtonText: 'No',
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Yes'
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             // alert(value);
    //             $.ajax({
    //                 type: "GET",
    //                 url: "assets/includes/deleteOneinCart-inc.php",
    //                 data: "cartID=" + value,
    //                 success: function (data) {
    //                 Swal.fire({
    //                     title: 'Successfully!',
    //                         text: 'Product removed successfully!',
    //                         icon: 'success',
    //                         button: true
    //                     }).then(() => {
    //                         location.reload();
    //                     });
    //                 }
    //             });
    //         }
    //     })        
    // });

    //CONFIRMATION TO REMOVE ALL PRODUCTS IN CART
    $('#deleteAll').click(function() {
        Swal.fire({
            title: 'Confirmation',
            text: "Do you want to remove <?=$cartChecked?> product in your cart?",
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: 'No',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                document.location = 'assets/includes/deleteAllinCart-inc.php';
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

    $('#select-all').click(function() {
        $.ajax({
            type: "POST",
            url: "assets/includes/selectAll-inc.php",
            dataType: 'json',
            data: {
                checked: $(this).is(":checked")
            },
            success: function(data) {

                console.log(data);
                console.log('good');
                location.reload();
            },
            error: function(e) {
                console.log(e.response);
                console.log('error');
            }
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

        
        //for message notif
        let fetchCart = function(){
      	    $.get("assets/ajax/ajax-cart.php", 
            {
                userID: <?php echo $userID ?>
            },
            // function(data){
            //     $(".dine").html(data);
            // }
            );
        }

        fetchCart();
        //auto update every .5 sec
        setInterval(fetchCart, 100);

</script>
</body>

</html>