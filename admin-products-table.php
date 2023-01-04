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

    require_once("assets/classes/dbHandler.php");
    $dbh = new Config();

    $products = $dbh->superAdminProducts();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | (Admin) All Products</title>
    <link rel="icon" type="image" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-navigation.css">
    <link rel="stylesheet" href="assets/css/Admin%20css%20files/admin-table.css">
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css"> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
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
            <div class="container table-container">
                <h4>Products Listed</h4>
                <div class="table-div">
                    <div class="table-responsive">
                        <table class="datatable table">
                            <thead>
                                <tr>
                                    <!-- <th></th> -->
                                    <th>Image</th>
                                    <th>Product name</th>
                                    <th>Price</th>
                                    <th>Sold</th>
                                    <th>Stock</th>
                                    <th>Critical Level</th>
                                    <th>Sold by</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    foreach($products as $prods){ 
                                        $details = $dbh->shopDetails($prods['shopID']);
                                        $stationDetails = $details[0];
                                        $sold = $dbh->countShopSold($prods['productID']);
                                ?>
                                <tr>
                                    <!-- <td></td> -->
                                    <td class="image-td">
                                        <div><img src="assets/img/products/<?=$prods['prod_image']?>"></div>
                                    </td>
                                    <td><?=$prods['product_name']?></td>
                                    
                                    <td>₱<?=$prods['price']?></td>
                                    <?php
                                        if($sold == 0){
                                    ?>
                                    <td>0</td>
                                    <?php
                                        }else{
                                    ?>
                                    <td><?php echo $dbh->numberconverter($sold)?></td>
                                    <?php
                                        }
                                    ?>
                                    <td><?=$prods['quantity']?></td>
                                    <td><?=$prods['critical_level']?></td>
                                    <td><?=$stationDetails['station_name'].' '.$stationDetails['branch_name']?></td>
                                    <td>
                                        <div class="status-div">
                                        <?php 
                                            if ($prods['quantity'] >= 10) { ?>
                                            <p class="normal">Normal</p>
                                        <?php
                                            } else if ($prods['quantity'] == 0) {
                                        ?>
                                            <p class="no-stock">No Stock</p>
                                        <?php
                                            } else if ($prods['quantity'] < 10) {
                                        ?>
                                             <p class="critical">Critical</p>
                                        <?php 
                                            } 
                                        ?>    
                                        </div>
                                    </td>     
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <!-- <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script> -->
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <!-- <script src="assets/js/table.js"></script> -->
    <script>
        let admintable = new DataTable('.datatable', {
            responsive: {
                details: {
                    type: 'column',
                    target: 'tr'
                }
            },
            columnDefs: [ {
                className: 'dtr-control',
                orderable: false,
                targets:   0
            } ],
            order: [ 1, 'asc' ]
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