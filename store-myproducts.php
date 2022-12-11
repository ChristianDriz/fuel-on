<?php
session_start();

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $username = $_SESSION['fname'];
    $userpic = $_SESSION['userPic'];
    $userType = $_SESSION['userType'];

    if ($userType == 1) {
        header('location: index.php');
    }
} else {
    header('location: index.php');
}

require_once("assets/classes/dbHandler.php");
$data = new Config();

if (isset($_GET['stationID'])) {
    $station = $_GET['stationID'];
} else {
    $station = $userID;
}

$get = $data->getFeedback($station);
$count = $data->getRatings($station);
if (!empty($get) || !empty($count)) {
    $rateSum = 0;
    foreach ($get as $rate) {
        $rateSum += $rate['rating'];
    }
    $totalRate = $rateSum / $count;
} else {
    $totalRate = 0;
}

$shop = $data->shopDetails($userID);
$shopDetails = $shop[0];

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
    <link rel="stylesheet" href="assets/fonts/material-icons.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-myproducts.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-navigation.css">
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css"> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand sticky-top" id="top">
        <div class="container"><a class="btn" role="button" id="menu-toggle" href="#menu-toggle"><i class="fa fa-bars"></i></a><a class="navbar-brand" href="#">&nbsp;<i class="fas fa-gas-pump"></i>&nbsp;FUEL ON</a>
            <ul class="navbar-nav">
                <?php require_once('notifications-div.php'); ?>
                <li class="nav-item" id="mail">
                    <p class="badge message-counter"></p>
                    <a class="nav-link" href="chat-list.php"><i class="fas fa-envelope"></i></a>
                </li>
                <li class="nav-item dropdown" id="user"><a class="nav-link" data-bs-toggle="dropdown">
                        <div class="profile-div"><img src="assets/img/profiles/<?php echo $userpic ?>"></div>
                        <p><?php echo $shopDetails['station_name'].' '.$shopDetails['branch_name']; ?></p>
                    </a>
                    <div class="dropdown-menu user"><a class="dropdown-item" href="assets/includes/logout-inc.php">Logout</a></div>
                </li>
            </ul>
        </div>
    </nav>
    <div id="wrapper">
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand"> <a href="store-home.php"><i class="fas fa-home"></i><span class="icon-name">Dashboard</span></a></li>
                <li class="sidebar-brand"> <a href="store-location.php"><i class="fas fa-map-marked-alt"></i><span class="icon-name">Location</span></a></li>
                <li class="sidebar-brand"> 
                    <a href="store-orders-all.php">
                        <i class="fas fa-shopping-basket"></i><span class="icon-name">Orders</span>
                    </a>
                    <?php
                    $orderCounter = $data->AllOrdersCountShop($userID);
                    if($orderCounter != 0){?>
                        <sup><?php echo $orderCounter ?></sup>
                    <?php
                    }?>
                </li>
                <li class="sidebar-brand"> <a href="store-mytimeline.php"><i class="fas fa-store"></i><span class="icon-name">Profile</span></a></li>
                <li class="sidebar-brand"> <a class="actives" href="store-myproducts.php"><i class="fas fa-shopping-bag"></i><span class="icon-name">Products</span></a></li>
                <li class="sidebar-brand"> <a href="store-view-sales.php"><i class="fas fa-chart-bar"></i><span class="icon-name">View Sales</span></a></li>
                <li class="sidebar-brand"> <a href="store-view-feedback.php"><i class="fas fa-star-half-alt"></i><span class="icon-name">Reviews</span></a></li>
                <li class="sidebar-brand"> <a href="store-account-settings.php"><i class="fas fa-user-cog"></i><span class="icon-name">Settings</span></a></li>
            </ul>
        </div>
        <div class="page-content-wrapper">
        <?php
        $kawnt = $data->countProdsinShop($station);
        $records = $data->allProductsStore($station);
        ?>
        <div class="container products-container">
            <div class="add-prod-div">
                <h4>My Products</h4>
                <a class="btn" role="button" href="store-add-products.php">Add Product</a>
            </div>
            <div class="products-div">
                <!-- <div class="add-prod-div">
                    <p>Total Products: <?php echo $kawnt ?></p>
                    <a class="btn" role="button" href="store-add-products.php"><i class="fas fa-plus"></i>Add Product</a>
                </div> -->
                <?php
                if (empty($records)) {
                ?>
                <div class="no-post">
                    <p>You don't have any products in your inventory.</p>
                </div>
                <?php
                } else {
                ?>
                    <div class="table-responsive">
                        <table class="product-table table">
                            <thead>
                                <tr>
                                    <!-- <th></th> -->
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Sold</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $index = 0;
                                    foreach ($records as $key => $val) {
                                        $prodID = $val['productID'];
                                        $sold = $data->countShopSold($prodID);
                                        // $benta = $sold[];
                                ?>
                                <tr>
                                    <!-- <td></td> -->
                                    <td class="image-td">
                                        <img src="assets/img/products/<?php echo $val['prod_image'] ?>">
                                    </td>
                                    <td class="prodname-td">
                                        <p><?php echo $val['product_name'] ?></p>
                                    </td>
                                    <td>₱<?php echo $val['price'] ?></td>
                                    <td><?php echo $sold ?></td>
                                    <td><?php echo $val['quantity'] ?></td>
                                    <td class="status-td">
                                    <?php
                                        if ($val['quantity'] == 0) { ?>
                                            <button class="btn btn-dark normal">No Stock</button>
                                        <?php
                                        } else if ($val['quantity'] < 10) {
                                        ?>
                                            <button class="btn btn-danger normal">Critical</button>
                                        <?php
                                        } else if ($val['quantity'] >= 10) {
                                        ?>
                                            <button class="btn btn-success normal">Normal</button>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <a class="btn edit-btn" role="button" data-bss-tooltip="" data-bs-placement="bottom" title="Edit product" href="store-update-myproduct.php?productID=<?php echo $val['productID'] ?>">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <!-- <a href="assets/includes/deleteProducts-inc.php?prodID=<?= $val['productID'] ?>" class="btn del-btn" role="button" data-bs-toggle="tooltip" data-bss-tooltip="" data-bs-placement="bottom" title="Delete product" onClick="return deleteconfirm()">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                            <script>
                                function deleteconfirm() 
                                {
                                    var result = confirm("Are you sure you want to delete this product?");
                                    if (result==true) {
                                        return true;
                                    } else {
                                        return false;
                                    }
                                }
                            </script> -->
                                    </td>
                                </tr>
                                <?php
                                    $index++;
                                }
                                ?>
                            </tbody>

                        </table>
                    </div>
                <?php
                }
                ?>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/Profile-edit-form.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/Table-With-Search.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <!-- <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script> -->
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script>    

        let table = new DataTable('.product-table', {
            pageLength: 5,
            lengthMenu: [ 5, 10, 25, 50, 75, 100 ],

            responsive: {
                details: {
                    type: 'column',
                    target: 'tr'
                }
            },
            columnDefs: [ {
                className: 'control',
                orderable: false,
                targets:   0
            } ],
            order: [ 1, 'asc' ]
        });

        <?php 
        if(isset($_SESSION['message'])) {?>
        //SUCCESS
        Swal.fire({
            title: 'Successfully!',
            text: '<?php echo $_SESSION['message']?>',
            icon: 'success',
            button: true
        });
        <?php 
        unset($_SESSION['message']);
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