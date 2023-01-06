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

$shop = $dbh->shopDetails($userID);
$shopDetails = $shop[0];

// $file = $dbh->viewFile($userID);

?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | Station Account Settings</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/material-icons.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-account-settings.css">
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
            <form action="assets/includes/updateStoreProfile-inc.php?userID=<?=$shopDetails['userID']?>" method="post" enctype="multipart/form-data">
                <div class="container" id="container-settings">
                    <h4>Account Settings</h4>
                    <div class="row settings-row">
                        <div class="col-12 col-lg-6 col-xl-5">
                            <div class="form prod-image">
                                <p class="para">Profile Image</p>
                                <div class="avatar-bg">
                                    <img src="assets/img/profiles/<?php echo $shopDetails['user_image']?>">
                                </div>
                                <input class="form-control file-input image-input" type="file">
                                <div class="leybel">
                                    <p>Maximum size: 2MB</p>
                                    <p>File extension: JPEG, PNG</p>
                                </div>
                            </div>
                            <div class="form sched-div">
                                <p class="para">Station Schedule</p>
                                <?php 
                                    if ($shopDetails['opening'] == "00:00:00" && $shopDetails['closing'] == "00:00:00"){ 
                                ?>
                                <div class="radio-div">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="closing" name="sched" onclick="showForm()" value="withClosing">
                                        <label class="form-check-label" for="closing">With closing hours</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="24hrs" name="sched" onclick="showForm()" value="24hrs" checked>
                                        <label class="form-check-label" for="24hrs">Open 24 hours</label>
                                    </div>
                                </div>
                                <div id="hiddenForm" class="name-div" style="display: none;">
                                    <div class="input-div left">
                                        <label class="form-label">Opening time</label>
                                        <input class="form-control" type="time" name="opening" value="<?php echo $shopDetails['opening']?>">
                                    </div>
                                    <div class="input-div right">
                                        <label class="form-label">Closing time</label>
                                        <input class="form-control" type="time" name="closing" value="<?php echo $shopDetails['closing']?>">
                                    </div>
                                </div>
                                <?php 
                                    }else{
                                ?>
                                <div class="radio-div">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="closing" name="sched" onclick="showForm()" value="withClosing" checked>
                                        <label class="form-check-label" for="closing">With closing hours</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="24hrs" name="sched" onclick="showForm()" value="24hrs">
                                        <label class="form-check-label" for="24hrs">Open 24 hours</label>
                                    </div>
                                </div>
                                <div id="hiddenForm" class="name-div">
                                    <div class="input-div left">
                                        <label class="form-label">Opening time</label>
                                        <input class="form-control" type="time" name="opening" value="<?php echo $shopDetails['opening']?>">
                                    </div>
                                    <div class="input-div right">
                                        <label class="form-label">Closing time</label>
                                        <input class="form-control" type="time" name="closing" value="<?php echo $shopDetails['closing']?>">
                                    </div>
                                </div>
                                <?php
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 col-xl-7">
                            <div class="form prod-details">
                                <div class="input-div">
                                    <label class="form-label">Email</label>
                                    <input class="form-control email" type="email" readonly value="<?php echo $shopDetails['email']; ?>" name="email">
                                </div>
                                <div class="name-div">
                                    <div class="input-div left">
                                        <label class="form-label">First name</label>
                                        <input class="form-control" type="text" value="<?php echo $shopDetails['firstname']; ?>" name="firstname">
                                    </div>
                                    <div class="input-div right">
                                        <label class="form-label">Last name</label>
                                        <input class="form-control" type="text" value="<?php echo $shopDetails['lastname']; ?>" name="lastname">
                                    </div>
                                </div>
                                <div class="name-div">
                                    <div class="input-div left">
                                        <label class="form-label">Station name</label>
                                        <input class="form-control" type="text" value="<?php echo $shopDetails['station_name']; ?>" name="station_name">
                                    </div>
                                    <div class="input-div right">
                                        <label class="form-label">Branch name</label>
                                        <input class="form-control" type="text" value="<?php echo $shopDetails['branch_name']; ?>" name="branch">
                                    </div>
                                </div>
                                <div class="input-div">
                                    <label class="form-label">Station Address</label>
                                    <textarea class="form-control" name="address"><?php echo $shopDetails['station_address']; ?></textarea>
                                </div>
                                <div class="input-div">
                                    <label class="form-label">Contact number</label>
                                    <input class="form-control" type="number" value="<?php echo $shopDetails['phone_num']; ?>" name="phone">
                                </div>
                                <div class="name-div">
                                    <div class="input-div left">
                                        <label class="form-label">TIN number</label>
                                        <input class="form-control" type="tel" max="15" maxlength="15" value="<?php echo $shopDetails['tin_number']?>" name="tin">
                                    </div>
                                    <div class="input-div right">
                                        <label class="form-label">Business Permit</label>
                                        <div class="permit-div">
                                            <input class="form-control email" type="text" readonly value="<?php echo $shopDetails['permit_name']?>">
                                            <?php
                                                $filetype = pathinfo($shopDetails['permit_name'], PATHINFO_EXTENSION);
                                                if($filetype == "pdf"){
                                            ?>
                                                <a class="btn" role="button" target="_blank" href="uploads/<?php echo $shopDetails['permit_name']?>">View</a>
                                            <?php
                                                }else{
                                            ?>
                                                <a class="btn show-modal-img" role="button">View</a>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-div">
                                    <label class="form-label">Update permit (Accepts: PDF, PNG, JPG, JPEG)</label>
                                    <input class="form-control custom-file" type="file" name="permit" accept=".pdf, .png, .jpg, .jpeg">
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

            <form action="assets/includes/change-pass-inc.php?userID=<?php echo $shopDetails['userID']?>" method="post" enctype="multipart/form-data">
                <div class="modal fade" role="dialog" tabindex="-1" id="myModal">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="input-div">
                                    <label class="form-label">Current Password</label>
                                    <input class="form-control input-pass" type="password" name="old_pass" placeholder="Enter your current password">
                                </div>
                                <div class="input-div">
                                    <label class="form-label">New Password</label>
                                    <input class="form-control input-pass" type="password" name="new_pass" placeholder="Enter your new password">
                                </div>
                                <div class="input-div">
                                    <label class="form-label">Confirm New Password</label>
                                    <input class="form-control input-pass" type="password" name="confirm_pass" placeholder="Re-enter your new password">
                                </div>
                                <div class="form-check checkbox-div">
                                    <input class="form-check-input show-pass" type="checkbox" id="formCheck">
                                    <label class="form-check-label" for="formCheck">Show password</label>
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
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <script>    
        //for dyanmic form in sched
        function showForm() {
            var withClosing = document.getElementById("closing");
            var form = document.getElementById("hiddenForm");
            form.style.display = withClosing.checked ? "flex" : "none";
        }

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

        $('.show-modal-img').click(function () { 
            Swal.fire({
                heightAuto: true,
                imageUrl: 'uploads/<?php echo $shopDetails['permit_name'] ?>',
                imageWidth: '100%',
                imageAlt: 'Custom image',
                showConfirmButton: false,
                padding: '0 10px',
                widthAuto: true,
            })
        });

        // $('.change-pass').click(function () { 
        //     const { value: formValues } = Swal.fire({
        //     title: 'Change password',
        //     showConfirmButton: false,
        //     html:
        //     '<form action="assets/includes/change-pass-inc.php?userID=<?php echo $shopDetails['userID']?>" method="post" enctype="multipart/form-data">'+
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