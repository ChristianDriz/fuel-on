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
    <title>Fuel ON | (Admin) Home</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sarabun&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-navigation.css">
    <link rel="stylesheet" href="assets/css/Admin%20css%20files/admin-home-panel.css">
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
            <div class="container home-container">
                <h4>Admin Dashboard</h4>
                <div class="row g-0">
                    <div class="col justify-content-center align-items-center">
                        <div class="dashboard above">
                            <div><img src="assets/img/7.png"></div>
                            <div class="text">
                                <h2><?php echo $users?> Users</h2>
                                <p>Verified Users</p>
                                <a class="btn" href="admin-normal-user-table.php">View Users</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="dashboard above">
                            <div><img src="assets/img/gas-pump.png"></div>
                            <div class="text">
                                <h2><?php echo $shops?> Station</h2>
                                <p>Verified Station Owners</p>
                                <a class="btn" href="admin-stores-table.php">View Stations</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="dashboard above">
                            <div><img src="assets/img/14.png"></div>
                            <div class="text">
                                <h2><?php echo $pending?> Pending</h2>
                                <p>Registration Approval</p>
                                <a class="btn" href="admin-store-approval.php">View Pending</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="dashboard below">
                            <div><img src="assets/img/12.png"></div>
                            <div class="text">
                                <h2><?php echo $prods?> Products</h2>
                                <p>Products Listed</p>
                                <a class="btn" href="admin-products-table.php">View Products</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="dashboard below">
                            <div><img src="assets/img/13.png"></div>
                            <div class="text">
                                <h2><?php echo $fuels?> Fuels</h2>
                                <p>Fuels Listed</p>
                                <a class="btn" href="admin-fuels-table.php">View Fuels</a>
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