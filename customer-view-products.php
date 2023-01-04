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


// if(isset($_GET['stationID'])){
//     $station = $_GET['stationID'];
// }
// else{
//     $station = $userID;
// }

if(isset($_GET['prodID'])){
    $prodID = $_GET['prodID'];
    $products = $data->oneProduct($prodID);
}
// else{
//     header('location: customer-products.php');
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | Product Details</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/fonts/material-icons.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-navigation.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-view-product.css">
</head>

<body style="background: rgb(248,245,245);">
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
            if(empty($products)){
                include 'no-data.php';
            }else{
                $prod = $products[0];
                $sold = $data->countShopSold($prod ['productID']);
                $stations = $data->shopDetails($prod['shopID']);
                $shop = $stations[0];
            ?>
            <div class="container product-container product-data">
                <form action="assets/includes/addToCart-inc.php?prodID=<?php echo $prod['productID'] ?>&&prodName=<?php echo $prod['product_name']?>&&shopID=<?php echo $prod['shopID']?>" method="post">
                    <div class="row" id="Product-details">
                        <input type="hidden" name="prodID" value="<?php echo $prod['productID'] ?>"/>
                        <div class="col-lg-6 product-image-col">
                            <?php if ($prod['quantity'] == 0){?>
                            <img class="no-stock-img" src="assets/img/products/<?php echo $prod['prod_image']; ?>">
                            <div class="sold-out-div">
                                <p>Out of stock</p>
                            </div>
                            <?php
                            }else{
                            ?>
                            <img src="assets/img/products/<?php echo $prod['prod_image']; ?>">
                            <?php
                            }
                            ?>
                        </div>
                        <div class="col-lg-6 product-info-col">
                            <div class="row" id="Product-name-and-price">
                                <div class="col-12 col-prod-name">
                                    <p name="prodName"><?php echo $prod['product_name']; ?></p>
                                </div>
                                <div class="col col-prod-price">
                                    <div>
                                    <p>₱<?php echo $prod['price']; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="Product-description">
                                <div class="col"><label class="form-label">Description</label>
                                    <p><?php echo $prod['description']; ?><br></p>
                                </div>
                            </div>
                            <div class="row" id="Product-quantity">
                                <div class="col">
                                    <label class="form-label">Quantity</label>
                                    <?php
                                        if ($prod['quantity'] != 0){
                                    ?>
                                    <div class="input-group quantity-div">
                                        <button class="btn decrement-btn" type="button"><i class="fas fa-minus"></i></button>
                                        <input class="form-control quantity" type="number" name="quantity" value="1">
                                        <button class="btn increment-btn" type="button"><i class="fas fa-plus"></i></button>
                                    </div>
                                    <?php
                                        }else{
                                    ?>
                                    <div class="input-group quantity-div">
                                        <button class="btn" type="button" disabled><i class="fas fa-minus"></i></button>
                                        <input class="form-control" type="number" value="1" disabled>
                                        <button class="btn" type="button" disabled><i class="fas fa-plus"></i></button>
                                    </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                                <div class="col">
                                    <label class="form-label">Stocks</label>
                                    <p class="stocks" id="<?php echo $prod["quantity"]?>"><?php echo $prod["quantity"]?> pc/s</p>       
                                </div>
                                <div class="col">
                                    <label class="form-label">Sold</label>                  
                                    <?php
                                        if($sold == 0){
                                    ?>
                                    <p class="stocks">0 sold</p>   
                                    <?php
                                        }else{
                                    ?>
                                    <p class="stocks"><?php echo $data->numberconverter($sold)?> sold</p>   
                                    <?php
                                        }
                                    ?>   
                                </div>
                            </div>
                            <div class="row" id="Product-btns">
                                <div class="col">
                                    <?php if ($prod['quantity'] != 0){?>
                                        <div><button class="btn btn-primary addcart-btn" type="submit" name="add" value="<?= $prod['productID']; ?>"><i class="la la-shopping-cart"></i>&nbsp;Add to cart</button></div>
                                    <?php
                                    }else {?>                  
                                        <div><button class="btn btn-primary addcart-btn" type="submit" disabled><i class="la la-shopping-cart"></i>&nbsp;Add to cart</button></div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="Product-seller">
                        <div class="col-auto seller-img-col">
                            <div class="seller-img"><img src="assets/img/profiles/<?php echo $shop['user_image'] ?>"></div>             
                        </div>
                        <div class="col seller-btn-col">
                            <div class="seller-name-div">
                                <p><?php echo $shop['station_name'].' '.$shop['branch_name'] ?></p>
                            </div>
                            <div class="btns-div">
                                <div>
                                    <a class="btn chat-now" role="button" href="chat-box.php?userID=<?=$shop['userID']?>&userType=<?=$shop['user_type']?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-message">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M4 21v-13a3 3 0 0 1 3 -3h10a3 3 0 0 1 3 3v6a3 3 0 0 1 -3 3h-9l-4 4"></path>
                                            <line x1="8" y1="9" x2="16" y2="9"></line>
                                            <line x1="8" y1="13" x2="14" y2="13"></line>
                                        </svg>Message
                                    </a>
                                </div>
                                <div>
                                    <a class="btn view-store" role="button" href="customer-viewstore-timeline.php?stationID=<?php echo $shop['userID'] ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-building-store">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <line x1="3" y1="21" x2="21" y2="21"></line>
                                            <path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4"></path>
                                            <line x1="5" y1="21" x2="5" y2="10.85"></line>
                                            <line x1="19" y1="21" x2="19" y2="10.85"></line>
                                            <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4"></path>
                                        </svg>View Station
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/view-product.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <script>    
        //PANG SUCCESS
        <?php if(isset($_SESSION['message'])) 
            { ?>
        Swal.fire({
            title: 'Successfully!',
            text: '<?php echo $_SESSION['message']?>',
            icon: 'success',
            showConfirmButton: false,
            timer: 2000
        });
        <?php 
        unset($_SESSION['message']);
            }
        //PANG NOTIFY
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
<?php?>