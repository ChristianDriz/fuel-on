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
    
    $users = $dbh->countUsers();
    $shops = $dbh->countShops();
    $prods = $dbh->countProds();
    $fuels = $dbh->countFuels();
    $pending = $dbh->countPending();
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
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Admin%20css%20files/admin-navigation.css">
    <link rel="stylesheet" href="assets/css/Admin%20css%20files/admin-home-panel.css">
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
                <li class="sidebar-brand"> <a class="actives" href="admin-home-panel.php"><i class="fas fa-home"></i><span class="icon-name">Dashboard</span></a></li>
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
                    <?php if ($pending != 0) { ?>
                        <sup><?=$pending ?></sup>
                    <?php
                    } ?>
                </li>
                <li class="sidebar-brand"> <a href="admin-account-settings.php"><i class="fas fa-user-cog"></i><span class="icon-name">Settings</span></a></li>
            </ul>
        </div>
        <div class="page-content-wrapper">
            <div class="container" id="home">
                <div class="row g-0 dashboard">
                    <div class="col-12 col-xl-4 kolum" id="normal-user-col">
                        <div class="user-div"><i class="fas fa-users"></i>
                            <div>
                                <h2><?php echo $users?> User/s</h2>
                                <p>Verified Users</p><a class="btn" role="button" href="admin-normal-user-table.php">View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 kolum" id="store-owner-col">
                        <div class="store-div"><i class="fas fa-store"></i>
                            <div>
                                <h2><?php echo $shops?> Station/s</h2>
                                <p>Verified Station Owners</p><a class="btn" role="button" href="admin-stores-table.php">View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 kolum" id="approval-col">
                        <div class="approval-div"><i class="fas fa-user-check"></i>
                            <div>
                                <h2><?php echo $pending?> Pending</h2>
                                <p>Registration Approval</p><a class="btn" role="button" href="admin-store-approval.php">View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-6 kolum" id="products-col">
                        <div class="product-div"><i class="fas fa-shopping-basket"></i>
                            <div>
                                <h2><?php echo $prods?> Product/s</h2>
                                <p>Product Listed</p><a class="btn" role="button" href="admin-products-table.php">View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-6 kolum" id="products-col-1">
                        <div class="product-div"><i class="fas fa-gas-pump"></i>
                            <div>
                                <h2><?php echo $fuels?> Fuel/s</h2>
                                <p>Fuel Listed</p><a class="btn" role="button" href="admin-fuels-table.php">View Details</a>
                            </div>
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
    <?php
    if (isset($_SESSION['message'])) { ?>
        const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
        })

        Toast.fire({
        icon: 'success',
        title: '<?php echo $_SESSION['message'] ?>'
        })
    <?php
        unset($_SESSION['message']);
    }

    if(isset($_SESSION['info_message'])) 
    { 
    ?>
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
        
    </script>
</body>

</html>