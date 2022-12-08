<?php
session_start();

if(isset($_SESSION['userID'])){
    $userID = $_SESSION['userID'];
    $username = $_SESSION['fname'];
    $userpic = $_SESSION['userPic'];
    $userType = $_SESSION['userType'];

    if($userType == 1)
    { 
        header('location: index.php');
    }
}
else{
    header('location: index.php');
}

require_once("assets/classes/dbHandler.php");
$dbh = new Config();

$shop = $dbh->shopDetails($userID);
$shopDetails = $shop[0];

// $file = $dbh->viewFile($userID);

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
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-account-settings.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-navigation.css">
</head>

<body>
<nav class="navbar navbar-light navbar-expand sticky-top" id="top">
        <div class="container"><a class="btn" role="button" id="menu-toggle" href="#menu-toggle"><i class="fa fa-bars"></i></a><a class="navbar-brand">&nbsp;<i class="fas fa-gas-pump"></i>&nbsp;FUEL ON</a>
            <ul class="navbar-nav">
            <?php require_once('notifications-div.php'); ?>
                <li class="nav-item" id="mail">
                    <p class="badge message-counter"></p>
                    <a class="nav-link" href="chat-list.php"><i class="fas fa-envelope"></i></a>
                </li>
                <li class="nav-item dropdown" id="user"><a class="nav-link" data-bs-toggle="dropdown">
                        <div class="profile-div">
                            <img src="assets/img/profiles/<?php echo $userpic ?>">
                        </div>
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
                <li class="sidebar-brand"> <a class="actives" href="store-account-settings.php"><i class="fas fa-user-cog"></i><span class="icon-name">Settings</span></a></li>
            </ul>
        </div>
        <div class="page-content-wrapper">
            <form action="assets/includes/updateStoreProfile-inc.php?userID=<?=$shopDetails['userID']?>" method="post" enctype="multipart/form-data">
                <div class="container" id="container-settings">
                    <h4>Account Settings</h4>
                    <div class="row settings-row">
                        <div class="col-12 col-lg-6 col-xl-5 kolum">
                            <div class="row">
                                <div class="col-12">
                                    <div class="inside-div">
                                        <p class="para">Station Logo</p>
                                        <div class="avatar-bg">
                                            <img class="imeds" src="assets/img/profiles/<?php echo $shopDetails['user_image']?>">
                                        </div>
                                            <input class="form-control file-input image-input" type="file" name="image" accept="image/*">
                                        <div class="leybel">
                                            <p>Maximum size: 1MB</p>
                                            <p>File extension: PNG, JPG, JPEG</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="sched-div">
                                        <p>Station Schedule</p>
                                        <?php if ($shopDetails['opening'] == "00:00:00" && $shopDetails['closing'] == "00:00:00"){ ?>
                                            <div class="radio-div">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" id="closing" name="sched" onclick="showForm()" value="withClosing">
                                                <label class="form-check-label" for="closing">With Closing Hours</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" id="24hrs" name="sched" onclick="showForm()" value="24hrs" checked>
                                                <label class="form-check-label" for="24hrs">Open 24 Hours</label>
                                            </div>
                                        </div>
                                        <div id="hiddenForm" class="sched-input" style="display: none;">
                                            <div class="opening-div">
                                                <p>Opening Time</p>
                                                <input class="form-control" type="time" name="opening" value="<?php echo $shopDetails['opening']?>" >
                                            </div>
                                            <div>
                                                <p>Closing Time</p>
                                                <input class="form-control" type="time" name="closing" value="<?php echo $shopDetails['closing']?>">
                                            </div>
                                        </div>
                                        <?php 
                                            }else{
                                        ?>
                                        <div class="radio-div">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" id="closing" name="sched" onclick="showForm()" value="withClosing" checked>
                                                <label class="form-check-label" for="closing">With Closing Hours</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" id="24hrs" name="sched" onclick="showForm()" value="24hrs">
                                                <label class="form-check-label" for="24hrs">Open 24 Hours</label>
                                            </div>
                                        </div>
                                        <!-- with closing sched -->
                                        <div id="hiddenForm" class="sched-input">
                                            <div class="opening-div">
                                                <p>Opening Time</p>
                                                <input class="form-control timeInput" type="time" name="opening" value="<?php echo $shopDetails['opening']?>">
                                            </div>
                                            <div>
                                                <p>Closing Time</p>
                                                <input class="form-control timeInput" type="time" name="closing" value="<?php echo $shopDetails['closing']?>">
                                            </div>
                                        </div>
                                        <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 col-xl-7 kolum">
                            <div class="inside-div details">
                                <div class="input-div">
                                    <label class="form-label">Email address</label>
                                    <input class="form-control email" type="email" readonly value="<?php echo $shopDetails['email']; ?>" name="email">
                                </div>
                                <div class="row">
                                    <div class="col-12 col-xl-6">
                                        <div class="input-div">
                                            <label class="form-label">First name</label>
                                            <input class="form-control" type="text" value="<?php echo $shopDetails['firstname']; ?>" name="firstname">
                                        </div>
                                    </div>
                                    <div class="col-12 col-xl-6">
                                        <div class="input-div">
                                            <label class="form-label">Last name</label>
                                            <input class="form-control" type="text" value="<?php echo $shopDetails['lastname']; ?>" name="lastname">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-xl-6">
                                        <div class="input-div">
                                            <label class="form-label">Station name</label>
                                            <input class="form-control" type="text" value="<?php echo $shopDetails['station_name']; ?>" name="station_name">
                                        </div>
                                    </div>
                                    <div class="col-12 col-xl-6">
                                        <div class="input-div">
                                            <label class="form-label">Branch name</label>
                                            <input class="form-control" type="text" value="<?php echo $shopDetails['branch_name']; ?>" name="branch">
                                        </div>
                                    </div>
                                </div>
                                <div class="input-div">
                                    <label class="form-label">Station Address</label>
                                    <textarea class="form-control" name="address"><?php echo $shopDetails['station_address']; ?></textarea>
                                </div>    
                                <div class="input-div">
                                    <label class="form-label">Contact number</label>
                                    <input class="form-control input" type="number" value="<?php echo $shopDetails['phone_num']; ?>" name="phone">
                                </div>
                                <div class="business-div">
                                    <div class="input-div">
                                        <label class="form-label">TIN number</label>
                                        <input class="form-control input" type="tel" max="15" maxlength="15" value="<?php echo $shopDetails['tin_number']?>" name="tin">
                                    </div>
                                    <div class="input-div bisnes">
                                        <label class="form-label">Business Permit</label>
                                        <div class="drop-down-div">
                                            <input class="form-control permit-input" type="text" readonly value="<?php echo $shopDetails['permit_name']?>">
                                            <div class="dropdown">
                                                <a class="btn dropdown-toggle drapdawn" aria-expanded="false" data-bs-toggle="dropdown" role="button"></a>
                                                <div class="dropdown-menu">
                                                    <?php
                                                        $filetype = pathinfo($shopDetails['permit_name'], PATHINFO_EXTENSION);
                                                        if($filetype == "pdf"){
                                                    ?>
                                                    <a class="dropdown-item" target="_blank" href="uploads/<?php echo $shopDetails['permit_name']?>">View Permit</a>
                                                    <?php
                                                        }else{
                                                    ?>
                                                    <a class="dropdown-item show-modal-img">View Permit</a>
                                                    <?php
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-div">
                                    <label class="form-label">Update permit (Accepts: PDF, PNG, JPG, JPEG)</label>
                                    <input class="form-control" type="file" name="permit" accept=".pdf, .png, .jpg, .jpeg">
                                </div>
                                <div class="button-div">
                                    <a class="btn change-pass" role="button" data-bs-toggle="modal" href="#">Change Password</a>
                                    <button class="btn update-btn" type="submit" name="save">Update Profile</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal fade" role="dialog" tabindex="-1" id="myModal">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form action="assets/includes/change-pass-inc.php?userID=<?php echo $shopDetails['userID']?>" method="post" enctype="multipart/form-data">
                        <div class="close-div"><button class="btn btn-close" type="reset" data-bs-dismiss="modal" aria-label="Close"></button></div>
                        <div class="password-div">
                            <div class="input-group">
                                <input class="form-control" type="password" name="old_pass" placeholder="Old password">
                            </div>
                            <div class="input-group">
                                <input class="form-control" type="password" name="new_pass" placeholder="New password">
                            </div>
                            <div class="input-group">
                                <input class="form-control" type="password" name="confirm_pass" placeholder="Confirm new password">
                            </div>
                        </div>
                        <div class="change-pass-div"><button class="btn" type="submit" name="changePass">Change Password</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/Profile-edit-form.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <script>    
        //for dyanmic form in sched
        function showForm() {
            var withClosing = document.getElementById("closing");
            var form = document.getElementById("hiddenForm");
            form.style.display = withClosing.checked ? "block" : "none";
        }

        <?php 
        if(isset($_SESSION['message'])) {?>
        //SUCCESS
        Swal.fire({
            title: 'Successfully!',
            text: '<?php echo $_SESSION['message']?>',
            icon: 'success',
            button: true,
            confirmButtonColor: '#fea600',
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
            button: true,
            confirmButtonColor: '#fea600',
        });
        <?php 
        unset($_SESSION['info_message']);
        }


        if(isset($_SESSION['error_message'])) 
        { ?>
        //Error 
        Swal.fire({
            title: '<?php echo $_SESSION['error_message']?>',
            icon: 'error',
            button: true,
            confirmButtonColor: '#fea600',
        });
        <?php 
        unset($_SESSION['error_message']);
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

        $('.show-modal-img').click(function () { 
            Swal.fire({
                heightAuto: true,
                imageUrl: 'uploads/<?php echo $shopDetails['permit_name'] ?>',
                imageWidth: '100%',
                imageAlt: 'Custom image',
                showConfirmButton: false,
                padding: '0 10px',
                width: '40%',
            })
        });

        $('.change-pass').click(function () { 
            const { value: formValues } = Swal.fire({
            title: 'Change password',
            showConfirmButton: false,
            html:
            '<form action="assets/includes/change-pass-inc.php?userID=<?php echo $shopDetails['userID']?>" method="post" enctype="multipart/form-data">'+
                '<div class="password-div">'+
                    '<div class="input-group">'+
                        '<input type="password" name="old_pass" class="form-control" placeholder="Old password">' +
                    '</div>'+
                    '<div class="input-group">'+
                        '<input type="password" name="new_pass" class="form-control" placeholder="New password">' +
                    '</div>'+
                    '<div class="input-group">'+
                        '<input type="password" name="confirm_pass" class="form-control" placeholder="Confirm new password">' +
                    '</div>'+
                '</div>'+
                '<div class="change-pass-div"><button style="background-color:#fea600;" class="swal2-confirm swal2-styled swal2-default-outline" type="submit" name="changePass">Change Password</button></div>'+
            '</form>', 
            });
        });
    </script>
</body>

</html>