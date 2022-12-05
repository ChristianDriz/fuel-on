<?php
session_start();

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $username = $_SESSION['fname'];
    $userpic = $_SESSION['userPic'];
    $userType = $_SESSION['userType'];

    if ($userType == 1) {
        header('location: index.php');
    }
} else {
    header('location: index.php');
}

require_once("assets/classes/dbHandler.php");
$data = new Config();

if (isset($_GET['fuelID'])) {
    $fuelID = $_GET['fuelID'];
}

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
    <link rel="stylesheet" href="assets/fonts/material-icons.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-add-fuel.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-navigation.css">
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
                <li class="nav-item dropdown" id="user"><a class="nav-link" data-bs-toggle="dropdown">
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
                <li class="sidebar-brand"> <a href="store-orders-all.php"><i class="fas fa-shopping-basket"></i><span class="icon-name">Orders</span></a></li>
                <li class="sidebar-brand"> <a href="store-mytimeline.php"><i class="fas fa-store"></i><span class="icon-name">Profile</span></a></li>
                <li class="sidebar-brand"> <a href="store-myproducts.php"><i class="fas fa-shopping-bag"></i><span class="icon-name">Products</span></a></li>
                <li class="sidebar-brand"> <a href="store-view-sales.php"><i class="fas fa-chart-bar"></i><span class="icon-name">View Sales</span></a></li>
                <li class="sidebar-brand"> <a href="store-view-feedback.php"><i class="fas fa-star-half-alt"></i><span class="icon-name">Reviews</span></a></li>
                <li class="sidebar-brand"> <a href="store-account-settings.php"><i class="fas fa-user-cog"></i><span class="icon-name">Settings</span></a></li>
            </ul>
        </div>
        <div class="page-content-wrapper">
            <?php 
                $records = $data->oneFuel($fuelID);
                    foreach($records as $val){
            ?>
            <form action="assets/includes/updateFuel-inc.php?fuelID=<?php echo $val['fuelID'] ?>" method="post" enctype="multipart/form-data">
                <div class="container" id="container-settings">
                    <h4>Update Fuel</h4>
                    <div class="row settings-row">
                        <div class="col-12 col-lg-6 col-xl-5 kolum image-kol">
                            <p class="para">Fuel Image</p>
                            <div class="avatar-bg"><img src="assets/img/products/<?php echo $val['fuel_image'] ?>"/></div>
                                <input class="form-control file-input image-input" type="file" name="image">
                            <div class="leybel">
                                <p>Maximum size: 1MB</p>
                                <p>File extension: JPEG, PNG</p>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 col-xl-7 kolum details">
                            <div class="details-div">
                                <div class="input-div"><label class="form-label">Category</label><input class="form-control email" type="text" readonly value="<?php echo $val['fuel_category']?>" required></div>
                                <div class="input-div"><label class="form-label">Fuel Name</label><input class="form-control" type="text" name="fuelType" value="<?php echo $val['fuel_type']?>" required></div>
                                <div class="input-div">
                                    <label class="form-label">Price</label>
                                    <input class="form-control" type="number" step=".01" name="newPrice" value="<?php echo $val['new_price']?>" required>
                                    <input class="form-control" type="hidden" step=".01" name="oldPrice" value="<?php echo $val['new_price']?>" required>
                                </div>
                                <div class="input-div"><label class="form-label">Status</label>
                                    <select class="form-select" name="fuel_status">
                                        <?php
                                            if($val['fuel_status'] == "available"){
                                        ?>
                                        <option selected disabled>Fuel Status</option>
                                        <option value="available" selected>Available</option>
                                        <option value="not available">Not Available</option>
                                        <?php
                                            }
                                            elseif($val['fuel_status'] == "not available"){
                                        ?>
                                        <option selected disabled>Fuel Status</option>
                                        <option value="available">Available</option>
                                        <option value="not available" selected>Not Available</option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                <div class="button-div"><button class="btn" type="submit" name="save">Save Changes</button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php
                }
            ?>
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
            title: 'Oops...',
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