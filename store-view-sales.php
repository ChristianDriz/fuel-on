<?php
session_start();
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $username = $_SESSION['fname'];
    $userpic = $_SESSION['userPic'];
    $userType = $_SESSION['userType'];

    if ($userType == 1 || $userType == 0) 
    {
        header('location: index.php');
    }

} else {
    header('location: index.php');
}

require_once("assets/classes/dbHandler.php");
$dbh = new Config();

$status = 'Completed';
$orders = $dbh->shopOrdersCompleted($userID, $status);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | Station My Sales</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-navigation.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-view-sales.css">
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
            <div class="container sales-container">
                <h4>Sales Report</h4>
                <div class="whole-sales-div">
                    <div class="sort-div">
                        <div class="filter-date-div">
                            <div class="from-div">
                                <label class="form-label">From Date</label>
                                <input type="date" name="from_date" class="from-date">
                            </div>
                            <div class="to-div">
                                <label class="form-label">To Date</label>
                                <input type="date" name="to_date" class="to-date">
                            </div>
                        </div>
                        <button class="btn filter-btn" type="button" name="filter">Filter</button>
                    </div>
                    <div class="data-div">
                        <?php
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
                                        <th class="text-end">Date</th>
                                        <th class="text-end">Unit Cost</th>
                                        <th class="text-end">Qty</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $grandtotal = 0;
                                        foreach($orders as $row){
                                            $grandtotal += $row['total'];
                                    ?>
                                    <tr>
                                        <!-- <td></td> -->
                                        <td><?php echo $row['product_name']?></td>
                                        <td class="text-end"><?php echo $dbh->dateconverter($row['date_completed'])?></td>
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
                <?php
                    if(!empty($orders)){
                ?>   
                <div class="chart-container">
                    <figure class="figure highcharts-figure">
                        <!-- <canvas id="chart" class="chart"></canvas> -->
                        <div id="chart" class="chart"></div>
                    </figure>
                </div>    
                <?php
                    }
                ?> 
            </div>
        </div>
    </div>
    <!-- JS Values -->
    <input type="hidden" value="<?= $userID ?>" id="input-shop-id">
    <input type="hidden" value="<?= $status ?>" id="input-ord-status">

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.1.1/chart.umd.js"></script>
    <script src="assets/js/charttt.js"></script> -->

    <script src="assets/js/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>

    <script src="assets/js/sort-sales.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
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
