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

$shop = $dbh->shopDetails($userID);
$shopDetails = $shop[0];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | Station Add Fuel</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/material-icons.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-add-fuel.css">
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
            <form action="assets/includes/addFuel-inc.php" method="post" enctype="multipart/form-data">
                <div class="container" id="container-settings">
                    <h4>Add Fuel</h4>
                    <div class="row settings-row">
                        <div class="col-12 col-lg-6 col-xl-5 kolum image-kol">
                            <p class="para">Fuel Image</p>
                            <div class="avatar-bg"></div>
                                <input class="form-control file-input image-input" type="file" name="image" accept="image/*">
                            <div class="leybel">
                                <p>Maximum size: 1MB</p>
                                <p>File extension: JPEG, PNG</p>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 col-xl-7 kolum details">
                            <div class="details-div">
                                <div class="input-div"><label class="form-label">Category</label>
                                    <select class="form-select" name="category">
                                        <option selected disabled>Fuel Category</option>
                                        <option value="Diesel">Diesel</option>
                                        <option value="Unleaded">Unleaded</option>
                                        <option value="Premium">Premium</option>
                                    </select>
                                </div>
                                <div class="input-div"><label class="form-label">Fuel Name</label><input class="form-control" type="text" name="prodName" ></div>
                                <div class="input-div"><label class="form-label">Price</label><input class="form-control" type="number" step=".01" name="price" >
                                </div>
                                <div class="input-div"><label class="form-label">Status</label>
                                    <select class="form-select" name="fuel_status">
                                        <option selected disabled>Fuel Status</option>
                                        <option value="available">Available</option>
                                        <option value="not available">Not Available</option>
                                    </select>
                                <div class="button-div"><button class="btn" type="submit" name="save">Add Fuel</button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/Profile-edit-form.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <script>    
        <?php 
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
    </script>
</body>

</html>