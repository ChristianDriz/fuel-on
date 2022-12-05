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

    $acc = $dbh->superAdmin();
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
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Admin%20css%20files/admin-navigation.css">
    <link rel="stylesheet" href="assets/css/Admin%20css%20files/admin-table.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css">
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
                <li class="sidebar-brand"> <a class="actives" href="admin-table.php"><i class="fas fa-user-tie"></i><span class="icon-name">Admins</span></a></li>
                <li class="sidebar-brand"> <a href="admin-products-table.php"><i class="fas fa-shopping-basket"></i><span class="icon-name">Products</span></a></li>
                <li class="sidebar-brand"> <a href="admin-fuels-table.php"><i class="fas fa-gas-pump"></i><span class="icon-name">Fuels</span></a></li>
                <li class="sidebar-brand"> <a href="admin-store-locations.php"><i class="fas fa-map-marked-alt"></i><span class="icon-name">Station Locations</span></a></li>
                <li class="sidebar-brand"> 
                    <a href="admin-store-approval.php"><i class="fas fa-user-check"></i><span class="icon-name">Pending Approval</span></a>
                    <?php 
                        $pending = $dbh->countPending();
                        if ($pending != 0) { ?>
                        <sup><?=$pending ?></sup>
                    <?php
                    } ?>
                </li>
                <li class="sidebar-brand"> <a href="admin-account-settings.php"><i class="fas fa-user-cog"></i><span class="icon-name">Settings</span></a></li>
            </ul>
        </div>
        <div class="page-content-wrapper">
            <div class="container">
                <div class="add-div">
                    <h4>Admins</h4><a class="btn" role="button" data-bs-toggle="modal" href="#add-admin">Add Admin</a>
                </div>
                <div class="table-div">
                    <div>
                        <table class="datatable row-border">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>#</th>
                                    <th>Firstname</th>
                                    <th>Lastname</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($acc as $admin){
                                ?>
                                <tr>
                                    <td></td>
                                    <td><?php echo $admin['userID']?></td>
                                    <td><?php echo $admin['firstname']?></td>
                                    <td><?php echo $admin['lastname']?></td>
                                    <td><?php echo $admin['phone_num']?></td>
                                    <td><?php echo $admin['email']?></td>
                                </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal fade" role="dialog" tabindex="-1" id="add-admin">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form action="assets/includes/addAdmin-inc.php" method="POST" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h4 class="modal-title">Add Admin</h4><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="form-label">Firstname</label>
                                    <input class="form-control" type="text" name="fname" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Lastname</label>
                                    <input class="form-control" type="text" name="lname" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input class="form-control" type="email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Phone number</label>
                                    <input class="form-control" type="text" name="phone" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Password</label>
                                    <input class="form-control" type="password" name="pass" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Confirm Password</label>
                                    <input class="form-control" type="password" name="confirmpass" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-light" type="reset" data-bs-dismiss="modal">Discard</button>
                                <button class="btn add-btn" type="submit" name="addAdmin">Add Admin</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/table.js"></script>
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


        <?php
        if (isset($_SESSION['message'])) { ?>
            //SUCCESS
            Swal.fire({
                title: 'Successfully!',
                text: '<?php echo $_SESSION['message'] ?>',
                icon: 'success',
                button: true
            }).then(() => {
                location.reload();
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
        }
        ?>
    </script>
</body>
</html>
