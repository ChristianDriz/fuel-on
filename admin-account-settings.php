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

    require_once("assets/classes/dbHandler.php");
    $dbh = new Config();
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON (Admin)</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-account-settings.css">
    <link rel="stylesheet" href="assets/css/Admin%20css%20files/admin-navigation.css">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand sticky-top" id="top">
        <div class="container"><a class="btn" role="button" id="menu-toggle" href="#menu-toggle"><i class="fa fa-bars"></i></a><a class="navbar-brand">&nbsp;<i class="fas fa-gas-pump"></i>&nbsp;FUEL ON</a>
            <ul class="navbar-nav">
                <li class="nav-item" id="mail">
                    <p class="badge message-counter"></p>
                    <a class="nav-link" href="chat-list.php"><i class="fas fa-envelope"></i></a>
                </li>
                <li class="nav-item dropdown" id="user"><a class="nav-link" data-bs-toggle="dropdown">
                        <div class="profile-div"><img src="assets/img/profiles/<?php echo $userpic ?>"></div>
                        <p><?php echo $username; ?></p>
                    </a>
                    <div class="dropdown-menu user"><a class="dropdown-item" href="assets/includes/logout-inc.php">Logout</a></div>
                </li>
            </ul>
        </div>
    </nav>
    <div id="wrapper">
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand"> <a href="admin-home-panel.php"><i class="fas fa-home"></i><span class="icon-name">Dashboard</span></a></li>
                <li class="sidebar-brand"> <a href="admin-normal-user-table.php"><i class="fas fa-users"></i><span class="icon-name">Normal Users</span></a></li>
                <li class="sidebar-brand"> <a href="admin-stores-table.php"><i class="fas fa-store"></i><span class="icon-name">Station Owners</span></a></li>
                <li class="sidebar-brand"> <a href="admin-table.php"><i class="fas fa-user-tie"></i><span class="icon-name">Admins</span></a></li>
                <li class="sidebar-brand"> <a href="admin-products-table.php"><i class="fas fa-shopping-basket"></i><span class="icon-name">Products</span></a></li>
                <li class="sidebar-brand"> <a href="admin-fuels-table.php"><i class="fas fa-gas-pump"></i><span class="icon-name">Fuels</span></a></li>
                <li class="sidebar-brand"> <a href="admin-store-locations.php"><i class="fas fa-map-marked-alt"></i><span class="icon-name">Station Locations</span></a></li>
                <li class="sidebar-brand"> 
                    <a href="admin-store-approval.php">
                        <i class="fas fa-user-check"></i><span class="icon-name">Pending Approval</span>
                    </a>
                    <?php 
                        $pending = $dbh->countPending();
                        if ($pending != 0) { ?>
                        <sup><?=$pending ?></sup>
                    <?php
                    } ?>
                </li>
                <li class="sidebar-brand"> <a class="actives" href="admin-account-settings.php"><i class="fas fa-user-cog"></i><span class="icon-name">Settings</span></a></li>
            </ul>
        </div>
        <div class="page-content-wrapper">
        <?php
            $record = $dbh->oneUser($userID);
            $data = $record[0];
        ?>
        <form action="assets/includes/updateProfile-inc.php?userID=<?=$data['userID']?>" method="post" enctype="multipart/form-data">
            <div class="container" id="container-settings">
                <h4>Account Settings</h4>
                <div class="row settings-row">
                    <div class="col-12 col-lg-6 col-xl-5 kolum">
                        <div class="inside-div">
                            <p class="para">Profile Image</p>
                            <div class="avatar-bg">
                                <img class="imeds" src="<?php echo 'assets/img/profiles/'.$data['user_image'] ?>" alt="Profile Photo"></div>
                                <input class="form-control file-input" type="file" name="image" accept="image/*">
                            <div class="leybel">
                                <p>Maximum size: 1MB</p>
                                <p>File extension: PNG, JPG, JPEG</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 col-xl-7 kolum">
                            <div class="inside-div details">
                            <div class="input-div">
                                <label class="form-label">Email address</label>
                                <input class="form-control email" type="email" name="email" readonly value="<?php echo $data['email']; ?>">
                            </div>
                            <div class="input-div">
                                <label class="form-label">Firstname</label>
                                <input class="form-control" type="text" name="firstname" value="<?php echo $data['firstname']; ?>">
                            </div>
                            <div class="input-div">
                                <label class="form-label">Lastname</label>
                                <input class="form-control" type="text" name="lastname" value="<?php echo $data['lastname']; ?>">
                            </div>
                            <div class="input-div">
                                <label class="form-label">Phone number</label>
                                <input class="form-control" type="number" name="phone" value="<?php echo $data['phone_num']; ?>">
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
                    <form action="assets/includes/change-pass-inc.php?userID=<?php echo $data['userID']?>" method="post" enctype="multipart/form-data">
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
    <script src="assets/js/Table-With-Search.js"></script>
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

        $('.change-pass').click(function () { 
            const { value: formValues } = Swal.fire({
            title: 'Change password',
            showConfirmButton: false,
            html:
            '<form action="assets/includes/change-pass-inc.php?userID=<?php echo $data['userID']?>" method="post" enctype="multipart/form-data">'+
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