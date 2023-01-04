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
    <title>Fuel ON | (Admin) Account Settings</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-account-settings.css">
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
            $record = $dbh->oneUser($userID);
            $data = $record[0];
        ?>
            <form action="assets/includes/updateProfile-inc.php?userID=<?=$data['userID']?>" method="post" enctype="multipart/form-data">
                <div class="container" id="container-settings">
                    <h4>Account Settings</h4>
                    <div class="row settings-row">
                        <div class="col-12 col-lg-6 col-xl-5">
                            <div class="form prod-image">
                                <p class="para">Profile Image</p>
                                <div class="avatar-bg">
                                    <img class="imeds" src="<?php echo 'assets/img/profiles/'.$data['user_image'] ?>" alt="Profile Photo">
                                </div>
                                <input class="form-control file-input image-input" name="image" type="file">
                                <div class="leybel">
                                    <p>Maximum size: 2MB</p>
                                    <p>File extension: JPEG, PNG</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 col-xl-7">
                            <div class="form prod-details">
                                <div class="input-div">
                                    <label class="form-label">Email</label>
                                    <input class="form-control email" type="email" name="email" readonly value="<?php echo $data['email']; ?>">
                                </div>
                                <div class="input-div">
                                    <label class="form-label">First name</label>
                                    <input class="form-control" type="text" name="firstname" value="<?php echo $data['firstname']; ?>">
                                </div>
                                <div class="input-div">
                                    <label class="form-label">Last name</label>
                                    <input class="form-control" type="text" name="lastname" value="<?php echo $data['lastname']; ?>">
                                </div>
                                <div class="input-div">
                                    <label class="form-label">Contact number</label>
                                    <input class="form-control" type="number" name="phone" value="<?php echo $data['phone_num']; ?>">
                                </div>
                                <div class="button-div">
                                    <button class="btn change-pass" type="button" data-bs-toggle="modal" data-bs-target="#myModal">Change Password</button>
                                    <button class="btn save-btn" type="submit" name="save">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <form action="assets/includes/change-pass-inc.php?userID=<?php echo $data['userID']?>" method="post" enctype="multipart/form-data">
                <div class="modal fade" role="dialog" tabindex="-1" id="myModal">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="input-div">
                                    <label class="form-label">Old Password</label>
                                    <input class="form-control" type="password" name="old_pass" placeholder="Enter your old password">
                                </div>
                                <div class="input-div">
                                    <label class="form-label">New Password</label>
                                    <input class="form-control" type="password" name="new_pass" placeholder="Enter your new password">
                                </div>
                                <div class="input-div">
                                    <label class="form-label">Confirm New Password</label>
                                    <input class="form-control" type="password" name="confirm_pass" placeholder="Re-enter your new password">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn change-pass-btn" type="submit" name="changePass">Change Password</button>
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
        }

        if(isset($_SESSION['error_message'])) 
        { ?>
        //Error 
        Swal.fire({
            title: '<?php echo $_SESSION['error_message']?>',
            icon: 'error',
            button: true
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

        // $('.change-pass').click(function () { 
        //     const { value: formValues } = Swal.fire({
        //     title: 'Change password',
        //     showConfirmButton: false,
        //     html:
        //     '<form action="assets/includes/change-pass-inc.php?userID=<?php echo $data['userID']?>" method="post" enctype="multipart/form-data">'+
        //         '<div class="password-div">'+
        //             '<div class="input-group">'+
        //                 '<input type="password" name="old_pass" class="form-control" placeholder="Old password">' +
        //             '</div>'+
        //             '<div class="input-group">'+
        //                 '<input type="password" name="new_pass" class="form-control" placeholder="New password">' +
        //             '</div>'+
        //             '<div class="input-group">'+
        //                 '<input type="password" name="confirm_pass" class="form-control" placeholder="Confirm new password">' +
        //             '</div>'+
        //         '</div>'+
        //         '<div class="change-pass-div"><button style="background-color:#fea600;" class="swal2-confirm swal2-styled swal2-default-outline" type="submit" name="changePass">Change Password</button></div>'+
        //     '</form>', 
        //     });
        // });
    </script>
</body>

</html>