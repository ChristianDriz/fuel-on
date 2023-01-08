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
    elseif($userType == 1)
    { 
        header('location: index.php');
    }
}
else{
    header('location: index.php');
}

if(isset($_GET['shopID'])){
    $shopID = $_GET['shopID'];
}
else{
    $shopID = 0;
}

require_once("assets/classes/dbHandler.php");
$dbh = new Config();

$get = $dbh->getFeedback($shopID);
$count = $dbh->getRatings($shopID);
if(!empty($get) || !empty($count)){
$rateSum = 0;
foreach($get as $rate){
    $rateSum += $rate['rating'];
}
$totalRate = $rateSum / $count;
}else{
    $totalRate = 0;
}

$shop = $dbh->shopDetails($shopID);
$allratings = $dbh->viewRatings($shopID);
$alltransac = $dbh->countPerShopTransac($shopID);
$orders = $dbh->shopAllOrders($shopID);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | Admin) Station All Transactions</title>
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
    <link rel="stylesheet" href="assets/css/Admin%20css%20files/admin-viewmore-customeralltransac.css">
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
                if(empty($shop)){
                    include 'no-data.php';
                }else{
            ?>
            <div class="container details-container">
                <h4>Station Details</h4>
                <?php
                    foreach($shop as $user){

                    //open hour
                    $openTime = $user['opening'];
                    $createdate = date_create($openTime);
                    $Timeopen = date_format($createdate, "h:i a");

                    //close hour
                    $closeTime = $user['closing'];
                    $createdate = date_create($closeTime);
                    $Timeclose = date_format($createdate, "h:i a");
                ?>
                <div class="col-12 col-name">
                    <div class="dib">
                        <div class="image-div"><img src="assets/img/profiles/<?php echo $user['user_image']?>"></div>
                        <div class="details-dibb">
                            <div class="name-div">
                                <p class="name-p"><?php echo $user['station_name'].' '.$user['branch_name']?></p>
                                <p><?php echo $user['station_address']?></p>
                            </div>
                            <div class="details-div">
                                <div class="div-div">
                                    <p><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none">
                                            <path d="M3 3H5L5.4 5M7 13H17L21 5H5.4M7 13L5.4 5M7 13L4.70711 15.2929C4.07714 15.9229 4.52331 17 5.41421 17H17M17 17C15.8954 17 15 17.8954 15 19C15 20.1046 15.8954 21 17 21C18.1046 21 19 20.1046 19 19C19 17.8954 18.1046 17 17 17ZM9 19C9 20.1046 8.10457 21 7 21C5.89543 21 5 20.1046 5 19C5 17.8954 5.89543 17 7 17C8.10457 17 9 17.8954 9 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>Completed Transaction: <?php echo $alltransac?>
                                    </p>
                                    <p><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none">
                                            <path d="M11.0489 2.92707C11.3483 2.00576 12.6517 2.00576 12.9511 2.92707L14.4697 7.60083C14.6035 8.01285 14.9875 8.29181 15.4207 8.29181H20.335C21.3037 8.29181 21.7065 9.53143 20.9228 10.1008L16.947 12.9894C16.5965 13.244 16.4499 13.6954 16.5838 14.1074L18.1024 18.7812C18.4017 19.7025 17.3472 20.4686 16.5635 19.8992L12.5878 17.0107C12.2373 16.756 11.7627 16.756 11.4122 17.0107L7.43647 19.8992C6.65276 20.4686 5.59828 19.7025 5.89763 18.7812L7.41623 14.1074C7.5501 13.6954 7.40344 13.244 7.05296 12.9894L3.07722 10.1008C2.2935 9.53143 2.69628 8.29181 3.665 8.29181H8.57929C9.01251 8.29181 9.39647 8.01285 9.53034 7.60083L11.0489 2.92707Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>Ratings:&nbsp;<span><?= number_format($totalRate, 1)?> (<?= $count ?> Rating)</span>
                                    </p>
                                    <p><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none">
                                            <path d="M12 8V12L15 15M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                        <?php 
                                            if ($user['opening'] && $user['closing'] == "00:00:00"){
                                                echo "Open 24 Hours";
                                            }else{
                                                echo 'Open: ' . $Timeopen . ' Close: ' . $Timeclose;
                                            }
                                        ?>
                                    </p>
                                </div>
                                <div class="div-div">
                                    <p><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none">
                                            <path d="M3 8L10.8906 13.2604C11.5624 13.7083 12.4376 13.7083 13.1094 13.2604L21 8M5 19H19C20.1046 19 21 18.1046 21 17V7C21 5.89543 20.1046 5 19 5H5C3.89543 5 3 5.89543 3 7V17C3 18.1046 3.89543 19 5 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg><?php echo $user['email']?>
                                    </p>
                                    <p><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none">
                                            <path d="M3 5C3 3.89543 3.89543 3 5 3H8.27924C8.70967 3 9.09181 3.27543 9.22792 3.68377L10.7257 8.17721C10.8831 8.64932 10.6694 9.16531 10.2243 9.38787L7.96701 10.5165C9.06925 12.9612 11.0388 14.9308 13.4835 16.033L14.6121 13.7757C14.8347 13.3306 15.3507 13.1169 15.8228 13.2743L20.3162 14.7721C20.7246 14.9082 21 15.2903 21 15.7208V19C21 20.1046 20.1046 21 19 21H18C9.71573 21 3 14.2843 3 6V5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg><?php echo $user['phone_num']?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="message-div">
                        <a class="btn" role="button" href="chat-box.php?userID=<?=$user['userID']?>&userType=<?=$user['user_type']?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none">
                                <path d="M8 10H8.01M12 10H12.01M16 10H16.01M9 16H5C3.89543 16 3 15.1046 3 14V6C3 4.89543 3.89543 4 5 4H19C20.1046 4 21 4.89543 21 6V14C21 15.1046 20.1046 16 19 16H14L9 21V16Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>Message
                        </a>
                    </div>
                </div>
                <?php
                }
                ?>
                <div class="nav-div">
                    <ul class="nav">
                        <li class="nav-item"><a class="nav-link" href="admin-viewmore-stores-allratings.php?shopID=<?=$user['userID']?>">All Ratings</a></li>
                        <li class="nav-item"><a class="nav-link active" href="admin-viewmore-stores-alltransac.php?shopID=<?=$user['userID']?>">All Transactions</a></li>
                        <li class="nav-item"><a class="nav-link" href="admin-viewmore-stores-allfuels.php?shopID=<?=$user['userID']?>">All Fuels</a></li>
                        <li class="nav-item"><a class="nav-link" href="admin-viewmore-stores-allproducts.php?shopID=<?=$user['userID']?>">All Products</a></li>
                        <li class="nav-item"><a class="nav-link" href="admin-viewmore-store-location.php?shopID=<?=$user['userID']?>">Station Location</a></li>
                        <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Station Permit</a>
                            <div class="dropdown-menu">
                                <?php
                                    $filetype = pathinfo($user['permit_name'], PATHINFO_EXTENSION);
                                    if($filetype == "pdf"){
                                ?>
                                <a class="dropdown-item" target="_blank" href="uploads/<?php echo $user['permit_name']?>">View Permit</a>
                                <?php
                                    }else{
                                ?>
                                <a class="dropdown-item show-modal-img">View Permit</a>
                                <?php
                                    }
                                ?>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="transac-div">
                    <div class="sort-div">
                        <div><span>Sort by</span>
                            <select name="order-status" class="sort-status" id="<?php echo $user['userID']?>">
                                <option selected disabled>Sort by order status</option>
                                <option value="all">All Transaction</option>
                                <option value="ordered">Ordered</option>
                                <option value="pickup">To Pickup</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled/Declined</option>
                            </select>
                        </div>
                        <div class="serts-div">
                            <input type="text" class="serts" id="<?php echo $user['userID']?>" placeholder="Search order id here...">
                        </div>
                    </div>
                    <div class="dine">
                    <?php
                        if(empty($orders)){
                    ?>
                    <h5 class="no-rate">No Transactions Yet</h5>
                    <?php
                        }else{
                        foreach($orders as $order){   
                            $customer = $dbh->shopGetCustomer($order['orderID']);
                            $buyer = $customer[0];
                    ?>
                        <div class="view-order-container">
                            <div class="header-div">
                                <div class="seller-div">
                                    <img src="assets/img/profiles/<?php echo $buyer['user_image']?>">
                                    <a href="admin-viewmore-customer-allratings.php?userID=<?= $buyer['userID']?>"><?php echo $buyer['firstname'].' '. $buyer['lastname']?></a>
                                </div>
                                <div>
                                    <span><?php echo $order['orderID']?></span><span class="divider">|</span><span class="status"><?php echo $order['order_status']?></span>
                                </div>
                            </div>
                            <?php
                                //ordered
                                if($order['order_status'] == "Ordered"){
                            ?>
                            <div class="transac-date-div">
                                <div class="col-12 col-md-4 details-col">
                                    <div class="payment-div">
                                        <span>Payment:</span>
                                        <p><?php echo $order['payment_method']?></p>
                                    </div>
                                    <div class="note-div">
                                        <span>Waiting for the station to confirm the order.</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-8 order-first order-md-last timeline-div">
                                    <div>
                                        <div class="date">
                                            <span>Date Ordered:</span>
                                            <p><?php echo $dbh->datetimeconverter($order['date_ordered'])?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                //to pickup
                                }elseif($order['order_status'] == "To Pickup"){
                            ?>
                            <div class="transac-date-div">
                                <div class="col-12 col-md-4 details-col">
                                    <div class="payment-div">
                                        <span>Payment:</span>
                                        <p><?php echo $order['payment_method']?></p>
                                    </div>
                                    <div class="note-div">
                                        <span><i class="fas fa-exclamation-circle"></i>Customer must pickup the order within 2 days upon approval.</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-8 order-first order-md-last timeline-div">
                                    <div>
                                        <div class="date">
                                            <span>Date Approved:</span>
                                            <p><?php echo $dbh->datetimeconverter($order['date_approved'])?></p>
                                        </div>
                                        <div class="date">
                                            <span>Date Ordered:</span>
                                            <p><?php echo $dbh->datetimeconverter($order['date_ordered'])?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                //completed
                                }elseif($order['order_status'] == "Completed"){
                            ?>
                            <div class="transac-date-div">
                                <div class="col-12 col-md-4 details-col">
                                    <div class="payment-div">
                                        <span>Payment:</span>
                                        <p><?php echo $order['payment_method']?></p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-8 order-first order-md-last timeline-div">
                                    <div>
                                        <div class="date">
                                            <span>Date Completed:</span>
                                            <p><?php echo $dbh->datetimeconverter($order['date_completed'])?></p>
                                        </div>
                                        <div class="date">
                                            <span>Date Approved:</span>
                                            <p><?php echo $dbh->datetimeconverter($order['date_approved'])?></p>
                                        </div>
                                        <div class="date">
                                            <span>Date Ordered:</span>
                                            <p><?php echo $dbh->datetimeconverter($order['date_ordered'])?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                //declined, cancelled
                                }elseif($order['order_status'] == "Declined" || $order['order_status'] == "Cancelled"){
                            ?>
                            <div class="transac-date-div">
                                <div class="col-12 col-md-4 details-col">
                                    <div class="payment-div">
                                        <span>Payment:</span>
                                        <p><?php echo $order['payment_method']?></p>
                                    </div>
                                    <div class="payment-div">
                                        <span>Cancellation Reason:</span>
                                        <p><?php echo $dbh->cancelreason($order['cancel_reason'])?></p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-8 order-first order-md-last timeline-div">
                                    <div>
                                        <div class="date">
                                            <span>Date Cancelled:</span>
                                            <p><?php echo $dbh->datetimeconverter($order['date_cancelled'])?></p>
                                        </div>
                                        <div class="date">
                                            <span>Date Ordered:</span>
                                            <p><?php echo $dbh->datetimeconverter($order['date_ordered'])?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                //pickup failed
                                }elseif($order['order_status'] == "Pickup Failed"){
                            ?>
                            <div class="transac-date-div">
                                <div class="col-12 col-md-4 details-col">
                                    <div class="payment-div">
                                        <span>Payment:</span>
                                        <p><?php echo $order['payment_method']?></p>
                                    </div>
                                    <div class="payment-div">
                                        <span>Cancellation Reason:</span>
                                        <p><?php echo $dbh->cancelreason($order['cancel_reason'])?></p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-8 order-first order-md-last timeline-div">
                                    <div>
                                        <div class="date">
                                            <span>Date Cancelled:</span>
                                            <p><?php echo $dbh->datetimeconverter($order['date_cancelled'])?></p>
                                        </div> 
                                        <div class="date">
                                            <span>Date Approved:</span>
                                            <p><?php echo $dbh->datetimeconverter($order['date_approved'])?></p>
                                        </div>
                                        <div class="date">
                                            <span>Date Ordered:</span>
                                            <p><?php echo $dbh->datetimeconverter($order['date_ordered'])?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                }
                            ?>
                            <div class="prodak">
                                <?php
                                    $grandtotal = 0;
                                    $records = $dbh->customerOrders($order['orderID']);
                                    foreach($records as $key => $val){
                                        $grandtotal += $val['total'];
                                ?>
                                <div class="sa-products">
                                    <a class="product-col">
                                        <div class="imeds-n-neym">
                                            <div class="imeyds-div">
                                                <img class="product-img" src="assets/img/products/<?php echo $val['prod_image']?>">
                                            </div>
                                            <div>
                                                <div class="neym-div">
                                                    <p class="product-name"><?php echo $val['product_name']?></p>
                                                </div>
                                                <div class="unit-price-div"><span>₱<?php echo $val['price']?></span></div>
                                                <div class="quantity-div"><span>Quantity: <?php echo $val['quantity']?></span></div>
                                            </div>
                                        </div>
                                        <div class="total-price-div"><span>₱<?php echo number_format($val['total'], 2)?></span></div>
                                    </a>
                                </div>
                                <?php
                                    }
                                ?>
                                <div class="total-div"><span>Order Total:</span>
                                    <p>₱<?php echo number_format($grandtotal, 2) ?></p>
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
            <?php
                }
            ?>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/editproduct.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/Table-With-Search.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <script>
        $(document).ready(function () {
            //for search
            $(".serts").on("input", function() {
                var searchText = $(this).val();
                var id = $(this).attr('id');

                if (searchText == "") return;
                $.post('assets/ajax/admin-viewmore-store-searchtransac.php', {
                    key: searchText, storeID: id
                },
                function(data, status) {
                    $(".dine").html(data);
                });
            });

            $(".serts").on("keypress", function(e) {
                if(e.which == 13){
                    var searchText = $(this).val();
                    var id = $(this).attr('id');

                    // alert (id);
                    if (searchText == "") return;
                    $.post('assets/ajax/admin-viewmore-store-searchtransac.php', {
                        key: searchText, storeID: id
                    },
                    function(data, status) {
                        $(".dine").html(data);
                    });
                }
            });


            //for sort
            $(".sort-status").on('change', function(){
            var value = $(this).val();
            var id = $(this).attr('id');
            // alert(id);
                $.ajax({
                    url: "assets/ajax/admin-viewmore-store-sort-transac.php",
                    type: "GET",
                    data: {request: value, storeID: id},
                    beforeSend:function(){
                        $(".dine").html("<span>Loading...</span>");
                    },
                    success:function(data){
                        $(".dine").html(data);
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


        $('.show-modal-img').click(function () { 
            Swal.fire({
                heightAuto: true,
                imageUrl: 'uploads/<?php echo $user['permit_name'] ?>',
                imageWidth: '100%',
                imageAlt: 'Custom image',
                showConfirmButton: false,
                padding: '0 10px',
                widthAuto: true,
            })
        });
    </script>
</body>

</html>