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

$soldp = 0;
$profit = $dbh->countShopProfit($userID);
$feedback = $dbh->countFeedback($userID);
$critical = $dbh->countCritical($userID);
$nostock = $dbh->countNoStock($userID);
$notavail = $dbh->countNotAvailable($userID);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | Station Dashboard</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sarabun&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-home.css">
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
            <?php
            foreach ($profit as $sold) {
                $soldp += $sold['total'];
            }
            ?>
            <div class="container home-container">
                <h4>Station Overview</h4>
                <div class="row g-0">
                    <div class="col-lg-6 col-xl-5">
                        <div class="dashboard">
                            <div><img src="assets/img/1.png"></div>
                            <div class="text">
                                <h2>₱<?= number_format($soldp, 2)?></h2>
                                <p>total profit from orders</p>
                                <a class="btn" href="store-view-sales.php">View Sales</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-7">
                        <div class="dashboard">
                            <div><img src="assets/img/15.png"></div>
                            <div class="text">
                                <h2>
                                    <?php 
                                        if (!empty($notavail)) {
                                            echo $notavail;
                                        }else {
                                            echo '0';
                                        } 
                                    ?>    
                                    of your Fuels
                                </h2>
                                <p>Listed as not available</p>
                                <a class="btn" href="store-myfuels.php">View Fuels</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-7">
                        <div class="dashboard">
                            <div><img src="assets/img/3.png"></div>
                            <div class="text">
                                <h2>
                                    <?php 
                                        if (!empty($critical)) {
                                            echo $critical;
                                        }else {
                                            echo '0';
                                        } 
                                    ?>
                                    of your Products
                                </h2>
                                <p>from inventory are low in stocks</p>
                                <a class="btn" href="store-myproducts.php">View Inventory</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-5">
                        <div class="dashboard">
                            <div><img src="assets/img/2.png"></div>
                            <div class="text">
                                <h2>
                                    <?php 
                                        if (!empty($feedback)) {
                                            echo $feedback;
                                        }else {
                                            echo '0';
                                        } 
                                    ?>
                                    reviews
                                </h2>
                                <p>about your station</p>
                                <a class="btn" href="store-view-feedback.php">View Reviews</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- JS Values -->

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/popup.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/Table-With-Search.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <script>
    <?php
    if (isset($_SESSION['message'])) { ?>
    Swal.fire({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        icon: 'success',
        title: '<?php echo $_SESSION['message'] ?>',
    })
    <?php
        unset($_SESSION['message']);
    }
    ?>
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