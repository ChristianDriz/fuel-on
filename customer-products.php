<?php
session_start();
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION["userID"];
    $username = $_SESSION["fname"];
    $userpic = $_SESSION["userPic"];
    $userType = $_SESSION["userType"];

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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | Products</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-navigation.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-products-tab.css">
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
        <div class="page-content-wrapper"></div>
        <?php
        $records = $data->allProductsCustomer();
        if (empty($records)) {
        ?>
            <div class="container" id="no-items-in-cart">
                <div class="row">
                    <div class="col-lg-11 mx-auto">
                        <div class="cont">
                            <div class="row">
                                <div class="col-sm-5 col-md-5 col-lg-5 left"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-tag icon">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M11 3l9 9a1.5 1.5 0 0 1 0 2l-6 6a1.5 1.5 0 0 1 -2 0l-9 -9v-4a4 4 0 0 1 4 -4h4"></path>
                                        <circle cx="9" cy="9" r="2"></circle>
                                    </svg></div>
                                <div class="col-sm-7 col-md-7 col-lg-7 right">
                                    <h4>It seems like stores doesn't</h4>
                                    <h4>have any registered products</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php 
        } else {
        ?>
            <div class="container filter-div">
                <div class="row g-0">
                    <div class="col-6 sort">
                        <p>Sort by</p>
                        <select name="price" id="changePrice">
                            <option selected disabled>Price</option>
                            <option value="low">Price: Lowest</option>
                            <option value="high">Price: Highest</option>
                        </select>
                    </div>
                    <div class="col-6 search">
                        <input type="text" placeholder="Search product here..." id="search">
                    </div>
                </div>
            </div>
            <div class="container products-container">
                <div class="row pradak">
                    <?php
                    foreach ($records as $val) {
                        $getShop = $data->shopDetails($val['shopID']);
                        $station = $getShop[0];
                        // $sold = $data->countShopSold($val['productID']);
                        $quantity = $val['quantity'];
                    ?>
                        <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4 col-xxl-3 kolum">
                            <?php
                                if ($quantity > 0) { 
                            ?>
                            <a href="customer-view-products.php?prodID=<?php echo $val['productID'] ?>">
                            <?php 
                                } 
                            ?>
                                <div class="product-div">
                                    <div class="product-image-div">
                                        <img class="img" src="assets/img/products/<?php echo $val['prod_image'] ?>">
                                    </div>
                                    <div class="product-desc-div">
                                        <h6 class="prod-name"><?= $val['product_name'] ?></h6>
                                        <div class="price-sold-div">
                                            <p class="prod-price"><?= "₱" . $val['price'] ?></p>
                                            <?php
                                                if(empty($val['sold'])){
                                            ?>
                                            <p class="sold">0 sold</p>
                                            <?php
                                                }else{
                                            ?>
                                            <p class="sold"><?php echo $data->numberconverter($val['sold'])?> sold</p>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                        <p class="prod-location">
                                            <i class="fas fa-store"></i>
                                            <?php echo $station['station_name'] . ' ' . $station['branch_name'] ?>
                                        </p>
                                    </div>
                                </div>
                            </a>         
                        </div>
                    <?php 
                        }    
                    ?>
                </div>
            </div>
        <?php
            }
        ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/sortProduct.js"></script>
    <script src="assets/js/Table-With-Search.js"></script>
    <script>
        $(document).ready(function() {
            $("#search").on("input", function() {
                var searchText = $(this).val();
                // alert (searchText);

                if (searchText == "") return;
                $.post('assets/ajax/productsearch.php', {
                        key: searchText
                    },
                    function(data, status) {
                        $(".pradak").html(data);
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
    </script>
</body>

</html>