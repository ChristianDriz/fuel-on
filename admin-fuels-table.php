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

    $fuels = $dbh->superAdminFuels();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | (Admin) All Fuels</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
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
                <h4>Fuels Listed</h4>
                <div class="table-div">
                    <div class="table-responsive">
                        <table class="datatable table">
                            <thead>
                                <tr>
                                    <!-- <th></th> -->
                                    <!-- <th>Image</th> -->
                                    <th>Fuel category</th>
                                    <th>Fuel name</th>
                                    <th>Old price</th>
                                    <th>Latest price</th>
                                    <th>Price changes</th>
                                    <th>Status</th>
                                    <th>Date updated</th>
                                    <th>Owned by</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    foreach($fuels as $fuel){ 
                                        $details = $dbh->shopDetails($fuel['shopID']);
                                        $stationDetails = $details[0];
                                ?>
                                <tr>
                                    <!-- <td></td> -->
                                    <!-- <td class="image-td">
                                        <div><img src="assets/img/products/<?=$fuel['fuel_image']?>"></div>
                                    </td> -->
                                    <td><?=$fuel['fuel_category']?></td>
                                    <td><?=$fuel['fuel_type']?></td>
                                    <td>
                                    <?php
                                        if(empty($fuel['old_price'])){
                                            echo "₱0";
                                        }else{
                                            echo "₱".$fuel['old_price'];
                                        }
                                    ?>
                                    </td>
                                    <td>₱<?=$fuel['new_price']?></td>
                                    <td class="pricechange-td">
                                        <?php
                                            if(empty($fuel['old_price'])){
                                                echo "0";
                                            }elseif($fuel['old_price'] < $fuel['new_price']){
                                        ?>
                                            <p class="up">
                                                <i class="icon ion-arrow-up-a"></i>
                                                +<?php echo number_format($fuel['new_price'] - $fuel['old_price'], 2) ?>
                                            </p>
                                        <?php
                                            }elseif($fuel['old_price'] > $fuel['new_price']){
                                        ?>
                                            <p class="down">
                                                <i class="icon ion-arrow-down-a"></i>
                                                <?php echo number_format($fuel['new_price'] - $fuel['old_price'], 2) ?>
                                            </p>
                                        <?php
                                            }
                                        ?>
                                    </td>
                                    <td><?=$fuel['fuel_status']?></td>
                                    <td><?=$dbh->dateconverter($fuel['date_updated']) ?></td>
                                    <td><?=$stationDetails['station_name'].' '.$stationDetails['branch_name']?></td>
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
                    type: 'column'
                }
            },
            columnDefs: [ {
                className: 'dtr-control',
                orderable: false,
                targets:  1
            } ],
            order: [1, 'asc' ]
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
