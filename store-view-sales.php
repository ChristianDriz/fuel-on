<?php
session_start();
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $username = $_SESSION['fname'];
    $userpic = $_SESSION['userPic'];
    $userType = $_SESSION['userType'];

    if ($userType == 1 || $userType == 0) {
        header('location: index.php');
    }
} else {
    header('location: index.php');
}

if (isset($_SESSION['from_date']) && isset($_SESSION['to_date'])) {
    $from_date = $_SESSION['from_date'];
    $to_date = $_SESSION['to_date'];
}

require_once("assets/classes/dbHandler.php");
$data = new Config();

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
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-navigation.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-view-sales.css">
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
                <li class="nav-item dropdown" id="user">
                    <a class="nav-link" data-bs-toggle="dropdown">
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
                <li class="sidebar-brand"> <a href="store-myproducts.php"><i class="fas fa-shopping-bag"></i><span class="icon-name">Products</span></a></li>
                <li class="sidebar-brand"> <a class="actives" href="store-view-sales.php"><i class="fas fa-chart-bar"></i><span class="icon-name">View Sales</span></a></li>
                <li class="sidebar-brand"> <a href="store-view-feedback.php"><i class="fas fa-star-half-alt"></i><span class="icon-name">Reviews</span></a></li>
                <li class="sidebar-brand"> <a href="store-account-settings.php"><i class="fas fa-user-cog"></i><span class="icon-name">Settings</span></a></li>
            </ul>
        </div>
        <div class="page-content-wrapper">
            <div class="container sales-container">
                <h4>Sales Report</h4>
                <div class="whole-sales-div">
                    <div class="sort-div">
                        <div class="filter-date-div">
                            <div class="from-div">
                                <label class="form-label">From Date</label>
                                <input type="date" name="from_date" class="from-date" value="<?php echo $from_date?>">
                            </div>
                            <div class="to-div">
                                <label class="form-label">To Date</label>
                                <input type="date" name="to_date" class="to-date" value="<?php echo $to_date?>">
                            </div>
                                <button class="btn filter-btn" type="button" name="filter">Filter</button>
                        </div>
                    </div>
                    <div class="data-div">
                        <?php
                            $status = 'Completed';
                            $orders = $data->shopOrdersCompleted($userID, $status);
                            if(empty($orders)){
                        ?>
                        <div class="no-sales"><img src="assets/img/transaction.png">
                            <h5>No transaction for this date</h5>
                        </div>
                        <?php
                            }else{
                        ?>
                        <div class="table-responsive">
                            <table class="mydatatable table">
                                <thead>
                                    <tr>
                                        <!-- <th></th> -->
                                        <th>Product Details</th>
                                        <th class="text-center" style="width: 12%;">Date</th>
                                        <th class="text-center" style="width: 10%;">Unit Cost</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $grandtotal = 0;
                                        foreach($orders as $row){
                                            $grandtotal += $row['total'];

                                            $date = $row['transac_date'];
                                            $createdate = date_create($date);
                                            $new_date = date_format($createdate, "M d, Y");
                                    ?>
                                    <tr>
                                        <!-- <td></td> -->
                                        <td><?php echo $row['product_name']?></td>
                                        <td class="text-end"><?php echo $new_date?></td>
                                        <td class="text-end">₱<?php echo number_format($row['price'], 2)?></td>
                                        <td class="text-end"><?php echo $row['quantity']?></td>
                                        <td class="text-end">₱<?php echo number_format($row['total'], 2)?></td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <!-- <th></th> -->
                                        <th class="generate"><a class="btn" target="_blank" href="generate-sales-report.php">Generate Report</a></th>
                                        <th class="text-end" colspan="3">TOTAL</th>
                                        <th class="text-end">₱<?php echo number_format($grandtotal, 2)?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>           
                <div class="chart-container">
                    <!-- <figure class="figure highcharts-figure"> -->
                        <canvas id="chart" class="chart"></canvas>
                    <!-- </figure> -->
                </div>     
            </div>
        </div>
    </div>
    <!-- JS Values -->
    <?php
        $status = "Completed";
    ?>
    <input type="hidden" value="<?= $userID ?>" id="input-shop-id">
    <input type="hidden" value="<?= $status ?>" id="input-ord-status">

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/charttt.js"></script>
    <!-- <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script> -->
    <script src="assets/js/sort-sales.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <!-- <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script> -->
    <script src="https://cdn.datatables.net/v/dt/dt-1.13.1/datatables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script>
        let table = new DataTable('.mydatatable', {
            scrollY: 450,
            scrollCollapse: true,
            paging: false,
            searching: false,
            ordering:  false,

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

        <?php
            unset($_SESSION['from_date']); 
            unset($_SESSION['to_date']);
        ?>

    </script>
</body>

</html>
