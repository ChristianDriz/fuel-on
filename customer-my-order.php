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

$pending = "ordered";
$pickup = "To Pickup";

$pendingCounter = $data->OrderCountCustomer($pending, $userID);
$pickupCounter = $data->OrderCountCustomer($pickup, $userID);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | My Orders All</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-mypurchase.css">
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
        <div class="container" id="transaction-container">
            <div class="row g-0" id="transaction-row-header">
                <div class="col">
                    <nav class="navbar navbar-light navbar-expand">
                        <div class="container">
                            <ul class="navbar-nav">
                                <li class="nav-item active"><a class="nav-link active" href="customer-my-order.php">All</a></li>
                                <li class="nav-item">
                                    <a class="nav-link" href="customer-purchases-pending.php">Pending
                                    <?php
                                        if($pendingCounter != 0){
                                        echo "(".$pendingCounter.")";
                                        }
                                    ?>
                                    </a>
                                </li>
                                <li class="nav-item">

                                    <a class="nav-link" href="customer-purchases-pickup.php">To Pickup 
                                    <?php
                                        if($pickupCounter != 0){
                                        echo "(".$pickupCounter.")";
                                        }
                                    ?>
                                    </a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="customer-purchases-completed.php">Completed</a></li>
                                <li class="nav-item"><a class="nav-link" href="customer-purchases-cancelled.php">Cancelled</a></li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
            <?php
            $orders = $data->CustomerAllOrder($userID);
                if(empty($orders)){
            ?>
            <div class="row" id="transaction-no-order-row">
                <div class="col-12" id="no-order">
                    <div class="col-12" id="no-order">
                        <img src="assets/img/no-order.png">
                        <p>No orders yet</p>
                    </div>
                </div>
            </div>
            <?php
                }
                else{
                    foreach($orders as $row){   
                        $station = $data->customerGetShop($row['orderID']);
                        $shopDetails = $station[0];
            ?>
                <div class="prodak">
                    <div class="seller-name">
                        <div class="seller-div">
                            <a>
                                <i class="fas fa-store"></i><?php echo $shopDetails['station_name'].' '. $shopDetails['branch_name']?>
                            </a>
                            <a class="message-icon" href="chat-box.php?userID=<?=$shopDetails['shopID']?>&userType=<?=$shopDetails['user_type']?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-message">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M4 21v-13a3 3 0 0 1 3 -3h10a3 3 0 0 1 3 3v6a3 3 0 0 1 -3 3h-9l-4 4"></path>
                                    <line x1="8" y1="9" x2="16" y2="9"></line>
                                    <line x1="8" y1="13" x2="14" y2="13"></line>
                                </svg>
                            </a>
                        </div>
                        <div class="order-id-div">
                            <p><?php echo $row['order_status']?></p>
                        </div>
                    </div>
                    <?php
                        $grandtotal = 0;
                        $records = $data->customerOrders($row['orderID']);
                        foreach($records as $key => $val){
                            $grandtotal += $val['total'];
                    ?>
                    <div class="sa-products">
                        <a class="product-col" href="customer-view-order.php?orderID=<?php echo $row['orderID']?>">
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
                    <div class="summary">
                        <div class="div-1">
                            <div class="payment-div"><span>Payment:</span>
                                <p><?php echo $val['payment_method']?></p>
                            </div>
                            <div class="total-div"><span>Order Total:</span>
                                <p>₱<?php echo number_format($grandtotal, 2) ?></p>
                            </div>
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
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/Table-With-Search.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/order-script.js"></script>
    <script>
    //CONFIRMATION TO CANCEL ORDER
    $(document).ready(function () {
        $('.cancel-order').click(function (e) { 
            e.preventDefault();
            const url = $(this).attr('href');

            Swal.fire({
                title: 'Confirmation',
                text: "Do you want to cancel your order?",
                icon: 'question',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    const { value: reason } = Swal.fire({
                        title: 'Please select your reason',
                        input: 'select',
                        inputPlaceholder: 'Select reason',
                        showCancelButton: true,
                        inputOptions: {
                            reason1: 'Need to modify order',
                            reason2: 'Found something else cheaper',
                            reason3: 'Others / Change of mind'
                        },
                        inputValidator: (value) => {
                            return new Promise((resolve) => {
                                if (value) {
                                    resolve()
                                        $.ajax({
                                            type: "GET",
                                            url,
                                            data: "reason=" +value,
                                            success: function (data) {
                                                Swal.fire({
                                                    title: 'Order Cancelled',
                                                    icon: 'success',
                                                    button: true,
                                                }).then(() => {
                                                    location.reload();
                                                });
                                            }
                                        });
                                }else{
                                    resolve('You need to select reason')
                                }
                            })
                        }
                    })
                }
            }) 
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