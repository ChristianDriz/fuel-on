<?php
session_start();
if(isset($_SESSION['userID'])){
    $userID = $_SESSION['userID'];
    $username = $_SESSION['fname'];
    $userpic = $_SESSION['userPic'];
    $userType = $_SESSION['userType'];

    if ($userType == 1 || $userType == 0) 
    {
        header('location: index.php');
    }
}
else{
    header('location: index.php');
}

require_once("assets/classes/dbHandler.php");
$dbh = new Config();

$loc = $dbh->getUserLocation($userID);

$shop = $dbh->shopDetails($userID);
$shopDetails = $shop[0];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | Station Update Location</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-location.css">
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
            <div class="container map-container">
                <h4>Update My Station Location</h4>
                <?php
                    foreach($loc as $location){
                ?>
                <form action="assets/includes/update-storelocation-inc.php" method="POST">
                    <div class="map-div">
                        <div id="maps" class="map"></div>
                        <input type="hidden" name="mapLat" id="mapLat" value="<?php echo $location['map_lat']?>">
                        <input type="hidden" name="mapLng" id="mapLng" value="<?php echo $location['map_lang']?>">
                        <input type="hidden" name="userID" value="<?php echo $userID ?>">
                    </div>
                    <div class="update-div">
                        <a class="btn btn-primary" type="button" href="store-location.php">Back</a>
                        <button class="btn btn-primary" type="submit" name="update_loc">Save Location</button>
                    </div>
                </form>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>
    
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBznw3cpC9HWF3r7VOvfpTpFaC_3s2lPMY"></script>
    <!--galing kay rose-->
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBznw3cpC9HWF3r7VOvfpTpFaC_3s2lPMY"></script> -->
    
    <!--galing kay michelle-->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_OEm-GWs2MhtvKaabGYVDO1wOE6LI9i0"></script>

    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/update-map-store.js"></script> 
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <script>    
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
        }
  
        if(isset($_SESSION['info_message'])) 
            { ?>
            
        //NOTIFY 
        Swal.fire({
            title: 'Oops!',
            text: '<?php echo $_SESSION['info_message']?>',
            icon: 'info',
            button: true
        });
        <?php 
        unset($_SESSION['info_message']);
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

        // $('.update-loc').click(function (e) { 
        //     e.preventDefault();

        //     var mapLat = $('#mapLat').val();
        //     var mapLng = $('#mapLng').val();

        //     Swal.fire({
        //         title: 'Are you sure?',
        //         text: "You want to update the location of your station?",
        //         icon: 'question',
        //         showCancelButton: true,
        //         cancelButtonText: 'No',
        //         confirmButtonText: 'Yes'
        //         }).then((result) => {
        //         if (result.isConfirmed) {
        //             document.location.href = 'assets/includes/update-storelocation-inc.php?userID=<?php echo $userID?>&mapLat=' + mapLat + '&mapLng=' + mapLng;
        //         }
        //     })
        // });

    </script>
</body>

</html>