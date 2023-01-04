<?php
session_start();
if(isset($_SESSION['userID'])){
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
}
else{
    header('location: index.php');
}

require_once("assets/classes/dbHandler.php");
$data = new Config();

$userLoc = $data->getUserLocation($userID);
$loc = $userLoc[0];

if(empty($loc['map_lat']) && empty($loc['map_lang'])){
    $mapLat = "";
    $mapLang = "";
}else{
    $mapLat = $loc['map_lat'];
    $mapLang = $loc['map_lang'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | Set Location</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-map.css">
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
                if(empty($loc['map_lat']) && empty($loc['map_lang'])){
                    $mapLat = "";
                    $mapLang = "";
            ?>
            <div class="container map-container">
                <div class="title-div">
                    <h4>Set your location</h4>
                    <div class="note-div">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Click the map to set location</p>
                    </div>
                </div>
                <form action="assets/includes/customer-setlocation-inc.php?userID=<?=$userID?>" method="post">
                    <div class="map-div">
                        <div id="maps" class="map"></div>
                        <input type="hidden" name="mapLat" id="mapLat" value="<?php echo $mapLat ?>">
                        <input type="hidden" name="mapLng" id="mapLng" value="<?php echo $mapLang ?>">
                    </div>
                    <div class="buttons-div">
                        <a class="btn" type="button" href="customer-map.php">Back to main map</a>
                        <button class="btn" type="submit" name="submit">Save Location</button>
                    </div>
                </form>
                <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_OEm-GWs2MhtvKaabGYVDO1wOE6LI9i0"></script>
                <script src="assets/js/customer-setlocation.js"></script> 
            </div>
            <?php
                }else{
                    $mapLat = $loc['map_lat'];
                    $mapLang = $loc['map_lang'];
            ?>
            <div class="container map-container">
                <div class="title-div">
                    <h4>Update your location</h4>
                    <div class="note-div">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Click the map or drag the marker to change location</p>
                    </div>
                </div>
                <form action="assets/includes/customer-updatelocation-inc.php?userID=<?=$userID?>" method="post">
                    <div class="map-div">
                        <div id="maps" class="map"></div>
                        <input type="hidden" name="mapLat" id="mapLat" value="<?php echo $mapLat ?>">
                        <input type="hidden" name="mapLng" id="mapLng" value="<?php echo $mapLang ?>">
                    </div>
                    <div class="buttons-div">
                        <a class="btn" type="button" href="customer-map.php">Back to main map</a>
                        <button class="btn" type="submit" name="submit">Update Location</button>
                    </div>
                </form>
                <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_OEm-GWs2MhtvKaabGYVDO1wOE6LI9i0"></script>
                <script src="assets/js/customer-updatelocation.js"></script> 
            </div>
            <?php
                }
            ?>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!--galing kay rose-->
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBznw3cpC9HWF3r7VOvfpTpFaC_3s2lPMY"></script> -->
    
    <!--galing kay michelle-->
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_OEm-GWs2MhtvKaabGYVDO1wOE6LI9i0"></script> -->

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
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


        <?php if(isset($_SESSION['message'])) 
            { ?>
        Swal.fire({
            title: 'Successfully!',
            text: '<?php echo $_SESSION['message']?>',
            icon: 'success',
            button: true
        });
        <?php 
        unset($_SESSION['message']);
            }
        ?>
    </script>
</body>
</html>