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

$stations = $data->shopDetails($station);
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
    <title>Fuel ON | Station All Products</title>
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
            if(empty($stations)){
                include 'no-data.php';
            }else{
        ?>
            <div class="store-profile-div">
            <?php
                    foreach($stations as $val){
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
                    <div class="map-div" id="maps"></div>
                    <input type="hidden" id="mapLat" value="<?php echo $val['map_lat']?>">
                    <input type="hidden" id="mapLng" value="<?php echo $val['map_lang']?>">
                    <input type="hidden" id="station" value="<?php echo $val['station_name'].' '.$val['branch_name']?>">
                    <input type="hidden" id="address" value="<?php echo $val['station_address']?>">

                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-8 profile-col">
                            <div class="profile-image-div">
                                <div class="outline-div">
                                    <img class="img" src="assets/img/profiles/<?= $val['user_image'] ?>">
                                </div>
                            </div>
                            <div class="profile-name-div">
                                <h5><?= $val['station_name'].' '.$val['branch_name'] ?></h5>
                                <p style="font-weight: 600;"><?php echo $val['station_address']?></p>
                                <p class="rating">
                                    <i class="far fa-star"></i>Rating:
                                    <a href="customer-view-feedback.php?stationID=<?=$val['userID']?>">
                                        <!-- the numberconverter is conversion of the number from 1000 to 1k -->
                                        <span><?= number_format($totalRate, 1)?> (<?= $data->numberconverter($count) ?> Rating)</span>
                                    </a>
                                </p>
                                <div class="sched-div 24hrs"><i class="far fa-clock"></i>
                                    <p class="open">
                                        <span style="font-weight: 500;">
                                        <?php
                                            if($val['opening'] == "00:00:00" && $val['closing'] == "00:00:00"){
                                                echo "Open 24 Hours"; 
                                            }else{
                                                echo 'Open: ' . $Timeopen . ' Close: ' . $Timeclose;
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
                                    
                                    if(empty($ratings)){
                                ?>
                                    <a class="btn rate-btn" role="button" href="#ratestoremodal" data-bs-toggle="modal">Rate Station</a>
                                <?php
                                    }else{
                                ?>
                                    <a class="btn rate-btn" role="button" data-bs-toggle="modal" data-bs-target="#editratestoremodal<?php echo $fidback['ratingID']?>">Edit rating</a>
                                <?php
                                    include 'customer-edit-feedback.php';
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
            <div id="no-products">
                <div class="no-prod-div">
                    <img src="assets/img/no-product.png">
                    <h5>It seems like they don't have any products yet</h5>
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
                            <a href="customer-view-products.php?prodID=<?php echo $val['productID']?>">
                                <div class="product-div">
                                    <div class="product-image-div"><img class="img" src="assets/img/products/<?= $val['prod_image']?>"></div>
                                    <div class="product-desc-div">
                                        <h6 class="prod-name"><?= $val['product_name']?></h6>
                                        <div class="price-sold-div">
                                            <p class="prod-price"><?= "₱".$val['price']?></p>
                                            <?php
                                                if($sold == 0){
                                            ?>
                                            <p class="sold">0 sold</p>
                                            <?php
                                                }else{
                                            ?>
                                            <p class="sold"><?php echo $data->numberconverter($sold)?> sold</p>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                        <p class="prod-location">Stocks:<?= ' '.$val['quantity']?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php } ?>          
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
                                            <?php
                                                if($sold == 0){
                                            ?>
                                            <p class="sold">0 sold</p>
                                            <?php
                                                }else{
                                            ?>
                                            <p class="sold"><?php echo $data->numberconverter($sold)?> sold</p>
                                            <?php
                                                }
                                            ?>
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
            <?php    
                    }
                }
            ?>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/editproduct.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/popup.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/Table-With-Search.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <!--galing kay rose-->
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBznw3cpC9HWF3r7VOvfpTpFaC_3s2lPMY"></script> -->
    
    <!--galing kay michelle-->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_OEm-GWs2MhtvKaabGYVDO1wOE6LI9i0"></script>

    <script src="assets/js/customer-viewstore-location.js"></script>
    <script>
        <?php
        //Info
        if(isset($_SESSION['info_message'])) {
        ?>
        Swal.fire({
            title: 'Oops...',
            text: '<?php echo $_SESSION['info_message']?>',
            icon: 'info',
            button: true
        });
        <?php 
        unset($_SESSION['info_message']);
        }?>


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