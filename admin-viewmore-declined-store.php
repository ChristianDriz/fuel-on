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

$shop = $dbh->shopDetails($shopID);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | (Admin) Declined Station Details</title>
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
    <link rel="stylesheet" href="assets/css/Admin%20css%20files/admin-viewmore-declined-store.css">
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
            <?php
                if(empty($shop)){
                    include 'no-data.php';
                }else{
            ?>
            <div class="container details-container">
                <div class="back-div">
                    <h4>Declined Station Details</h4>
                    <a class="btn" href="admin-declined-store.php">Back</a>
                </div>
                <?php
                    foreach($shop as $shopdetail)

                    //open hour
                    $openTime = $shopdetail['opening'];
                    $createdate = date_create($openTime);
                    $Timeopen = date_format($createdate, "h:i a");

                    //close hour
                    $closeTime = $shopdetail['closing'];
                    $createdate = date_create($closeTime);
                    $Timeclose = date_format($createdate, "h:i a");
                ?>
                <div class="col-12 col-name">
                    <div class="details-dibb">
                        <div class="image-div">
                            <img src="assets/img/profiles/<?php echo $shopdetail['user_image']?>">
                        </div>
                        <div class="name-div">
                            <p class="name-p"><?php echo $shopdetail['station_name'] . ' ' . $shopdetail['branch_name'] ?></p>
                            <p><?php echo $shopdetail['station_address']?></p>
                        </div>
                        <div class="details-div">
                            <p>Owner: <?php echo $shopdetail['firstname'] . ' ' . $shopdetail['lastname']?></p>
                            <p>Email: <?php echo $shopdetail['email']?></p>
                            <p>Phone: <?php echo $shopdetail['phone_num']?></p>
                            <p> 
                            <?php 
                                if ($shopdetail['opening'] == "00:00:00" && $shopdetail['closing'] == "00:00:00"){
                                    echo "Open 24 Hours";
                                }else{
                                    echo 'Open: ' . $Timeopen . ' Close: ' . $Timeclose;
                                }
                            ?>
                            </p>
                            <p>TIN: <?php echo $shopdetail['tin_number']?></p>
                        </div>
                        <div class="buttons-div">
                            <a class="btn reason" role="button">View Reason</a>
                            <?php
                            //if the file type is pdf
                                $filetype = pathinfo($shopdetail['permit_name'], PATHINFO_EXTENSION);
                                if($filetype == "pdf"){
                            ?>
                            <a class="btn" target="_blank" href="uploads/<?php echo $shopdetail['permit_name']?>">View Permit</a>
                            <?php
                                }else{
                            ?>
                            <a class="btn permit" role="button">View Permit</a>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                    <div id="maps" class="map-div"></div>
                    <input type="hidden" id="mapLat" value="<?= $shopdetail['map_lat'] ?>">
                    <input type="hidden" id="mapLng" value="<?= $shopdetail['map_lang'] ?>">
                    <input type="hidden" id="name" value="<?= $shopdetail['station_name'] . ' ' . $shopdetail['branch_name']?>">
                    <input type="hidden" id="address" value="<?= $shopdetail['station_address']?>">
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
    <script src="assets/js/Sidebar-Menu.js"></script>
    <!--galing kay rose-->
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBznw3cpC9HWF3r7VOvfpTpFaC_3s2lPMY"></script> -->
    
    <!--galing kay michelle-->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_OEm-GWs2MhtvKaabGYVDO1wOE6LI9i0"></script>

    <script src="assets/js/admin-view-store-location.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <script>
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


        $('.permit').click(function () { 
            Swal.fire({
                heightAuto: true,
                imageUrl: 'uploads/<?php echo $shopdetail['permit_name'] ?>',
                imageWidth: '100%',
                imageAlt: 'Custom image',
                showConfirmButton: false,
                padding: '0 10px',
                widthAuto: true,
            })                 
        });


        $('.reason').click(function () { 
            Swal.fire(
            'Declination Reason:',
            '<?php echo $shopdetail['declined_reason'] ?>',
            )
        });
    </script>
</body>

</html>