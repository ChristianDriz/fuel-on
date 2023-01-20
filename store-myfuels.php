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


$get = $dbh->getFeedback($userID);
$count = $dbh->getRatings($userID);
if(!empty($get) || !empty($count)){
$rateSum = 0;
foreach($get as $rate){
    $rateSum += $rate['rating'];
}
$totalRate = $rateSum / $count;
}else{
    $totalRate = 0;
}

$shop = $dbh->shopDetails($userID);
$shopDetails = $shop[0];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | Station My Fuels</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sarabun&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Share+Tech+Mono&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/fonts/material-icons.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-myfuels.css">
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
            <div class="container">
                <div class="add-div">
                    <h4>My Fuels</h4><a class="btn" role="button" data-bs-toggle="modal" href="#add-fuel">Add Fuel</a>
                </div>
                <div class="row">
                    <?php
                        $records = $dbh->allFuelPerStation($userID);
                        if(empty($records)){
                    ?>
                    <div id="no-fuels">
                        <h4>You don't have any fuels added</h4>
                    </div>
                    <?php
                        }else{
                            foreach($records as $fuels){
                    ?>
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-4 col-xxl-3 kolum">
                        <?php
                            //if the fuel is not available, the unavailable tag will displayed
                            if($fuels['fuel_status'] == "not available"){
                        ?>
                        <span class="status-tag">Not available</span>
                        <?php 
                            }
                            if($fuels['fuel_category'] == "Diesel" ){
                        ?>
                        <div class="fuel-div diesel">
                        <?php
                            }elseif($fuels['fuel_category'] == "Unleaded"){
                        ?>
                        <div class="fuel-div unleaded">
                        <?php
                            }elseif($fuels['fuel_category'] == "Premium"){
                        ?>
                        <div class="fuel-div premium">
                        <?php
                            }elseif($fuels['fuel_category'] == "Racing"){
                        ?>
                        <div class="fuel-div racing">
                        <?php
                            }
                        ?>
                            <div class="fuel-name">
                                <h1><?php echo $fuels['fuel_category']?></h1>
                                <h6><?php echo $fuels['fuel_type']?></h6>
                            </div>
        
                            <div class="fuel-price">
                                <?php
                                    if(empty($fuels['old_price'])){
                                ?>
                                <h1><strong>₱</strong><?php echo $fuels['new_price']?>
                                <?php
                                    }else{
                                        if($fuels['new_price'] > $fuels['old_price']){
                                ?>
                                <h1><strong>₱</strong><?php echo $fuels['new_price']?><i class="fas fa-angle-double-up"></i><span><?php echo number_format($fuels['new_price'] - $fuels['old_price'], 2) ?></span></h1>
                                <p>from <strong>₱</strong><?php echo $fuels['old_price']?></p>
                                <?php
                                        }elseif($fuels['new_price'] < $fuels['old_price']){
                                ?>
                                <h1><strong>₱</strong><?php echo $fuels['new_price']?><i class="fas fa-angle-double-down"></i><span><?php echo abs(number_format($fuels['new_price'] - $fuels['old_price'], 2)) ?></span></h1>
                                <p>from <strong>₱</strong><?php echo $fuels['old_price']?></p>
                                <?php
                                        }
                                    }
                                ?>
                            </div>
                            <p class="date">as of <?php echo $dbh->dateconverter($fuels['date_updated'])?></p>
                            <a class="dropdown" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-h"></i>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" data-bs-toggle="modal" href="#update-fuel<?php echo $fuels['fuelID']?>">Update</a>
                                    <a class="dropdown-item DeleteFuel" data-fuelID="<?php echo $fuels['fuelID']?>">Delete</a>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- update fuel modal -->
                    <div class="modal fade" role="dialog" tabindex="-1" id="update-fuel<?php echo $fuels['fuelID'] ?>">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Update Fuel</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="assets/includes/updateFuel-inc.php?fuelID=<?php echo $fuels['fuelID'] ?>" method="post" enctype="multipart/form-data">
                                        <div class="row settings-row">
                                            <div class="col form prod-details">
                                                <div class="input-div">
                                                    <label class="form-label">Category</label>
                                                    <select class="form-select select-fuel input" name="category" required>
                                                    <?php
                                                        //pang return ng selected dropdown
                                                        if($fuels['fuel_category'] == "Diesel"){        
                                                    ?>
                                                        <option disabled>Fuel Category</option>
                                                        <option selected value="Diesel">Diesel</option>
                                                        <option value="Unleaded">Unleaded</option>
                                                        <option value="Premium">Premium</option>
                                                        <option value="Racing">Racing</option>
                                                    <?php
                                                        }elseif($fuels['fuel_category'] == "Unleaded"){        
                                                    ?>
                                                        <option disabled>Fuel Category</option>
                                                        <option value="Diesel">Diesel</option>
                                                        <option selected value="Unleaded">Unleaded</option>
                                                        <option value="Premium">Premium</option>
                                                        <option value="Racing">Racing</option>
                                                    <?php
                                                        }elseif($fuels['fuel_category'] == "Premium"){        
                                                    ?>
                                                        <option disabled>Fuel Category</option>
                                                        <option value="Diesel">Diesel</option>
                                                        <option value="Unleaded">Unleaded</option>
                                                        <option selected value="Premium">Premium</option>
                                                        <option value="Racing">Racing</option>
                                                    <?php
                                                        }elseif($fuels['fuel_category'] == "Racing"){        
                                                    ?>
                                                        <option disabled>Fuel Category</option>
                                                        <option value="Diesel">Diesel</option>
                                                        <option value="Unleaded">Unleaded</option>
                                                        <option value="Premium">Premium</option>
                                                        <option selected value="Racing">Racing</option>
                                                    <?php
                                                        }else{        
                                                    ?>
                                                        <option selected disabled>Fuel Category</option>
                                                        <option value="Diesel">Diesel</option>
                                                        <option value="Unleaded">Unleaded</option>
                                                        <option value="Premium">Premium</option>
                                                        <option value="Racing">Racing</option>
                                                    <?php
                                                        }
                                                    ?>
                                                    </select></div>
                                                <div class="input-div">
                                                    <label class="form-label">Fuel name</label>
                                                    <input class="form-control" type="text" name="fuelname" placeholder="Enter fuel name" value="<?php echo $fuels['fuel_type']?>" required>
                                                </div>
                                                <div class="input-div">
                                                    <label class="form-label">Price</label>
                                                    <input class="form-control" type="number" step=".01" name="price" placeholder="Enter price" value="<?php echo $fuels['new_price']?>" required>
                                                    <input class="form-control" type="hidden" name="oldPrice" value="<?php echo $fuels['new_price']?>">
                                                </div>
                                                <div class="input-div">
                                                    <label class="form-label">Status</label>
                                                    <select class="form-select" name="fuel_status" required>
                                                    <?php
                                                        //pang return ng selected dropdown
                                                        if($fuels['fuel_status'] == "available"){        
                                                    ?>
                                                        <option disabled>Fuel Status</option>
                                                        <option value="available" selected>Available</option>
                                                        <option value="not available">Not Available</option>
                                                    <?php
                                                        }elseif($fuels['fuel_status'] == "not available"){
                                                    ?>
                                                        <option disabled>Fuel Status</option>
                                                        <option value="available">Available</option>
                                                        <option value="not available" selected>Not Available</option>
                                                    <?php
                                                        }else{
                                                    ?>
                                                        <option selected disabled>Fuel Status</option>
                                                        <option value="available">Available</option>
                                                        <option value="not available">Not Available</option>
                                                    <?php
                                                        }
                                                    ?>
                                                    </select>
                                                </div>
                                                <div class="button-div">
                                                    <button class="btn cancel" type="button">Discard</button>
                                                    <button class="btn save input" type="submit" name="save">Save Changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                            }
                        }

                        if(isset($_GET['fuelname']) || isset($_GET['category']) || isset($_GET['price']) || isset($_GET['fuel_status'])){
                            $fuelname = $_GET['fuelname'];
                            $category = $_GET['category'];
                            $price = $_GET['price'];
                            $fuel_status = $_GET['fuel_status'];
                        }else{
                            $fuelname = "";
                            $category = "";
                            $price = "";
                            $fuel_status = "";
                        }
                    ?>
                </div>
            </div>
            <!-- add fuel modal-->
            <div class="modal fade" role="dialog" tabindex="-1" id="add-fuel">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Fuel</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="assets/includes/addFuel-inc.php" method="post" enctype="multipart/form-data">
                                <div class="row settings-row">
                                    <div class="col form prod-details">
                                        <div class="input-div">
                                            <label class="form-label">Category</label>
                                            <select class="form-select select-fuel input" name="category">
                                            <?php
                                                //pang return ng selected dropdown
                                                if($category == "Diesel"){        
                                            ?>
                                                <option disabled>Fuel Category</option>
                                                <option selected value="Diesel">Diesel</option>
                                                <option value="Unleaded">Unleaded</option>
                                                <option value="Premium">Premium</option>
                                                <option value="Racing">Racing</option>
                                            <?php
                                                }elseif($category == "Unleaded"){        
                                            ?>
                                                <option disabled>Fuel Category</option>
                                                <option value="Diesel">Diesel</option>
                                                <option selected value="Unleaded">Unleaded</option>
                                                <option value="Premium">Premium</option>
                                                <option value="Racing">Racing</option>
                                            <?php
                                                }elseif($category == "Premium"){        
                                            ?>
                                                <option disabled>Fuel Category</option>
                                                <option value="Diesel">Diesel</option>
                                                <option value="Unleaded">Unleaded</option>
                                                <option selected value="Premium">Premium</option>
                                                <option value="Racing">Racing</option>
                                            <?php
                                                }elseif($category == "Racing"){        
                                            ?>
                                                <option disabled>Fuel Category</option>
                                                <option value="Diesel">Diesel</option>
                                                <option value="Unleaded">Unleaded</option>
                                                <option value="Premium">Premium</option>
                                                <option selected value="Racing">Racing</option>
                                            <?php
                                                }else{        
                                            ?>
                                                <option selected disabled>Fuel Category</option>
                                                <option value="Diesel">Diesel</option>
                                                <option value="Unleaded">Unleaded</option>
                                                <option value="Premium">Premium</option>
                                                <option value="Racing">Racing</option>
                                            <?php
                                                }
                                            ?>
                                            </select></div>
                                        <div class="input-div">
                                            <label class="form-label">Fuel name</label>
                                            <input class="form-control" type="text" name="fuelname" placeholder="Enter fuel name" value="<?php echo $fuelname?>">
                                        </div>
                                        <div class="input-div">
                                            <label class="form-label">Price</label>
                                            <input class="form-control" type="number" step=".01" name="price" placeholder="Enter price" value="<?php echo $price?>">
                                        </div>
                                        <div class="input-div">
                                            <label class="form-label">Status</label>
                                            <select class="form-select" name="fuel_status">
                                            <?php
                                                //pang return ng selected dropdown
                                                if($fuel_status == "available"){        
                                            ?>
                                                <option disabled>Fuel Status</option>
                                                <option value="available" selected>Available</option>
                                                <option value="not available">Not Available</option>
                                            <?php
                                                }elseif($fuel_status == "not available"){
                                            ?>
                                                <option disabled>Fuel Status</option>
                                                <option value="available">Available</option>
                                                <option value="not available" selected>Not Available</option>
                                            <?php
                                                }else{
                                            ?>
                                                <option selected disabled>Fuel Status</option>
                                                <option value="available">Available</option>
                                                <option value="not available">Not Available</option>
                                            <?php
                                                }
                                            ?>
                                            </select>
                                        </div>
                                        <div class="button-div">
                                            <button class="btn cancel" type="button">Cancel</button>
                                            <button class="btn save input" type="submit" name="save">Add Fuel</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <script>    
    $(document).ready(function () {
        $('.cancel').click(function () {

            Swal.fire({
                title: 'Are you sure?',
                text: "You will discard the data you input.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
                }).then((result) => {
                if (result.isConfirmed) {
                    location.href = "store-myfuels.php";
                }
            })
        });


        // $('.select-fuel').change(function () { 
        //     var value = $(this).val();

        //     if (value == "Diesel"){
        //         $('.input').css({'background-color': '#ffbb2a', 'color':'white'});
        //     }
        //     else if(value == "Unleaded"){
        //         $('.input').css({'background-color': '#07a24d', 'color':'white'});
        //     }
        //     else if(value == "Premium"){
        //         $('.input').css({'background-color': '#ea3f23', 'color':'white'});
        //     }
        //     else if(value == "Racing"){
        //         $('.input').css({'background-color': '#084faf', 'color':'white'});
        //     }
        // });


        //CONFIRMATION TO DELETE A FUEL
        $('.DeleteFuel').click(function (e) { 
            e.preventDefault();
            var value = $(this).attr('data-fuelID');

            Swal.fire({
                title: 'Confirmation',
                text: "Are you sure you want to delete this fuel?",
                icon: 'question',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    // alert(value);
                    $.ajax({
                        type: "GET",
                        url: "assets/includes/deleteFuel-inc.php",
                        data: "fuelID=" + value,
                        success: function (data) {
                            Swal.fire({
                                title: 'Successfully!',
                                text: 'Fuel deleted successfully!',
                                icon: 'success',
                                button: true
                            }).then(() => {
                                location.reload();
                            });
                        }
                    });
                }
            })        
        });
    });

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