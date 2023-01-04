<?php
session_start();
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION["userID"];
    $username = $_SESSION["fname"];
    $userpic = $_SESSION["userPic"];
    $userType = $_SESSION["userType"];

    if($userType == 2)
    { 
        header('location: index.php');
    }
    elseif($userType == 0)
    { 
        header('location: index.php');
    }
} else {
    header('location: index.php');
}

require_once("assets/classes/dbHandler.php");
$dbh = new Config();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | All Stations</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-navigation.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-view-allstation.css">
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
        <div class="page-content-wrapper"></div>
            <div class="container products-container">
                <div class="search-div">
                    <h4>All Station</h4>
                    <input type="text" id="search" placeholder="Search station here...">
                </div>
                <div class="row pradak">
                    <?php
                        $records = $dbh->allShops();
                        foreach($records as $val){

                            $get = $dbh->getFeedback($val['shopID']);
                            $count = $dbh->getRatings($val['shopID']);
                            if(!empty($get) || !empty($count)){
                                $rateSum = 0;
                                foreach($get as $rate){
                                    $rateSum += $rate['rating'];
                                }
                                $totalRate = $rateSum / $count;
                            }else{
                                $totalRate = 0;
                            }
                    ?>
                    <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4 col-xxl-3 kolum">
                        <a href="customer-viewstore-timeline.php?stationID=<?php echo $val['shopID']?>">
                            <div class="product-div">
                                <div class="product-image-div">
                                    <img class="img" src="assets/img/profiles/<?php echo $val['user_image']?>">
                                </div>
                                <div class="product-desc-div">
                                    <h6 class="prod-name"><?php echo $val['station_name'] ?> <?php echo $val['branch_name'] ?></h6>
                                    <div class="ratings-div">
                                        <div><i class="fas fa-star"></i></div>
                                        <p><?= number_format($totalRate, 1)?> (<?php echo $dbh->numberconverter($count)?> Rating)</p>
                                    </div>
                                    <p class="prod-location">
                                        <i class="fas fa-map-marker-alt"></i><?php echo $val['station_address'] ?>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
                        }
                    ?>
                </div>    
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/sortProduct.js"></script>
    <script src="assets/js/Table-With-Search.js"></script>
    <script>
        $(document).ready(function() {
            $("#search").on("input", function() {
                var searchText = $(this).val();

                if (searchText == "") return;
                $.post('assets/ajax/stationsearch.php', {
                        key: searchText
                    },
                    function(data, status) {
                        $(".pradak").html(data);
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
    </script>
</body>

</html>