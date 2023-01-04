<?php
session_start();
if(isset($_SESSION['userID'])){
    $userID = $_SESSION["userID"];
    $username = $_SESSION["fname"];
    $userType = $_SESSION["userType"];
    $userpic = $_SESSION["userPic"];

    if ($userType == 1 || $userType == 0) {
        header('location: index.php');
    }
}
else{
    header('location: index.php');
}

$status = 'To Pickup';

require_once("assets/classes/dbHandler.php");
$dbh = new Config();

$pending = "ordered";
$pickup = "To Pickup";

$pendingCounter = $dbh->OrderCountShop($pending, $userID);
$pickupCounter = $dbh->OrderCountShop($pickup, $userID);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | Station Orders To Pickup</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-navigation.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-mypurchase.css">
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
                                    <li class="nav-item"><a class="nav-link" href="store-orders-all.php">All</a></li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="store-orders-pending.php">Orders
                                        <?php
                                            if($pendingCounter != 0){
                                            echo "(".$pendingCounter.")";
                                            }
                                        ?>
                                        </a>
                                    </li>
                                    <li class="nav-item active">
                                        <a class="nav-link active" href="store-orders-pickup.php">To Pickup
                                        <?php
                                            if($pickupCounter != 0){
                                            echo "(".$pickupCounter.")";
                                            }
                                        ?>                                
                                        </a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="store-orders-completed.php">Completed</a></li>
                                    <li class="nav-item"><a class="nav-link" href="store-orders-cancelled.php">Cancelled</a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
                <?php 
                $orders = $dbh->shopOrderCount($userID, $status);
                if(empty($orders)){
                ?>
                <div class="row g-0" id="transaction-no-order-row">
                    <div class="col-12" id="no-order">
                        <img src="assets/img/no-order.png">
                        <p>No orders yet</p>
                    </div>
                </div>
                <?php }else{
                        foreach($orders as $row){
                            $customer = $dbh->shopGetCustomer($row['orderID']);
                            $buyer = $customer[0];
                ?>

                <div class="prodak">
                    <div class="seller-name">
                        <div class="seller-div">
                            <a>
                                <img src="assets/img/profiles/<?php echo $buyer['user_image']?>"><?php echo $buyer['firstname'].' '.$buyer['lastname']?>
                            </a>
                            <a class="message-icon" href="chat-box.php?userID=<?=$buyer['userID']?>&userType=<?=$buyer['user_type']?>">
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
                        $records = $dbh->customerOrders($row['orderID']);
                        foreach($records as $val){
                            $grandtotal += $val['total'];
                    ?>
                    <div class="sa-products">
                        <a class="product-col" href="store-view-order.php?orderID=<?php echo $row['orderID']?>">
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
    <script src="assets/js/sweetalert2.js"></script>
    <script>    
        <?php 
        if(isset($_SESSION['message'])) {?>
        //SUCCESS
        Swal.fire({
            title: '<?php echo $_SESSION['message']?>',
            icon: 'success',
            button: true
        });
        <?php 
        unset($_SESSION['message']);
        }?>

    $(document).ready(function () {
        $('.complete-btn').click(function () { 
            // var value = $(this).val();
            const url = $(this).attr('href');

            Swal.fire({
                title: 'Confirmation',
                text: "Are you sure that this order is completed?",
                icon: 'question',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    // alert (value);
                    $.ajax({
                        type: "GET",
                        url,
                        success: function (data) {
                            Swal.fire({
                                title: 'Transaction Completed',
                                icon: 'success',
                                button: true
                            }).then(() => {
                                location.reload();
                            });
                        }
                    });
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


        //CONFIRMATION TO CANCEL THE ORDER THAT IS NOT PICKED UP
        $('.cancel-btn').click(function (e) { 
            e.preventDefault();
            const url = $(this).attr('href');

            Swal.fire({
                title: 'Confirmation',
                text: "Are you sure that this order will be cancelled?",
                icon: 'question',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    const { value: reason } = Swal.fire({
                        title: 'Please select a reason',
                        input: 'select',
                        inputPlaceholder: 'Select reason',
                        showCancelButton: true,
                        inputOptions: {
                            reason5: 'Did not pick up the order'
                        },
                        inputValidator: (value) => {
                            return new Promise((resolve) => {
                                if (value) {
                                    resolve()
                                        $.ajax({
                                            type: "GET",
                                            url,
                                            data: "reason=" + value,
                                            success: function (data) {
                                                Swal.fire({
                                                    title: 'Order has been cancelled',
                                                    icon: 'success',
                                                    button: true,
                                                }).then(() => {
                                                    location.reload();
                                                    // alert (data);
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
    </script>
</body>

</html>