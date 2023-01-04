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
    <title>Fuel ON | (Admin) Table</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-navigation.css">
    <link rel="stylesheet" href="assets/css/Admin%20css%20files/admin-table.css">
    <link rel="stylesheet" href="assets/css/modal-admin-form.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
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
            <div class="container table-container">
                <div class="add-div">
                    <h4>Admins</h4><a class="btn" role="button" data-bs-toggle="modal" href="#add-admin">Add Admin</a>
                </div>
                <div class="table-div">
                    <div class="table-responsive">
                        <table class="datatable table">
                            <thead>
                                <tr>
                                    <!-- <th></th> -->
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
                                    <!-- <td></td> -->
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
            <?php
                if(isset($_GET['fname']) || isset($_GET['lname']) || isset($_GET['email']) || isset($_GET['phone'])){
                    $fname = $_GET['fname'];
                    $lname = $_GET['lname'];
                    $emai = $_GET['email'];
                    $phone = $_GET['phone'];
                }
                else{
                    $fname = '';
                    $lname = '';
                    $emai = '';
                    $phone = '';
                }
            ?>
            <form action="assets/includes/addAdmin-inc.php" method="POST" enctype="multipart/form-data">
                <div class="modal fade" role="dialog" tabindex="-1" id="add-admin">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Add Admin</h4><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="row settings-row">
                                        <div class="col form prod-details">
                                            <div class="input-div">
                                                <label class="form-label">First name</label>
                                                <input class="form-control" type="text" name="fname" required placeholder="Enter first name" value="<?php echo $fname?>">
                                            </div> 
                                            <div class="input-div">
                                                <label class="form-label">Last name</label>
                                                <input class="form-control" type="text" name="lname" required placeholder="Enter last name" value="<?php echo $lname?>">
                                            </div>
                                            <div class="input-div">
                                                <label class="form-label">Email</label>
                                                <input class="form-control" type="email" name="email" required placeholder="Enter email address" value="<?php echo $emai?>">
                                            </div>
                                            <div class="input-div">
                                                <label class="form-label">Phone</label>
                                                <input class="form-control" type="number" name="phone" required placeholder="Enter contact number" value="<?php echo $phone?>">
                                            </div>
                                            <div class="input-div">
                                                <label class="form-label">Password</label>
                                                <input class="form-control" type="password" name="pass" required placeholder="Enter password">
                                            </div>
                                            <div class="input-div">
                                                <label class="form-label">Confirm password</label>
                                                <input class="form-control" type="password" name="confirmpass" required placeholder="Re-enter password">
                                            </div>
                                            <div class="button-div">
                                                <button class="btn cancel" type="button">Cancel</button>
                                                <button class="btn save input" type="submit" name="addAdmin">Add Admin</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <!-- <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script> -->
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
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
