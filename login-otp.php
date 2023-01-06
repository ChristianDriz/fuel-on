<?php
    session_start();
    if(isset($_SESSION['userID'])){
        $email = $_SESSION['email'];
        $userID = $_SESSION['userID'];
        $username = $_SESSION['fname'];
        $userpic = $_SESSION['userPic'];
        $userType = $_SESSION['userType'];
    }
    else{
        header('location: login.php');
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | Login OTP</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/material-icons.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Landing%20page%20css%20files/forgot-pass.css">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand-md navbar-top">
        <div class="container">
            <a class="navbar-brand"><i class="fas fa-gas-pump"></i>&nbsp;FUEL ON</a><label class="form-label"></label>
            <a class="sign-out" href="assets/includes/logout-inc.php">Sign out</a>
        </div>
    </nav>
    <div class="login">
        <div class="login-div">
            <form class="sign-in-form" method="post" action="assets/includes/verifyCustomer-inc.php" enctype="multipart/form-data">
                <h2>Account Verification</h2>
                <div class="input-div">
                    <label class="form-label">Enter the verification code</label>
                    <input class="form-control" type="number" name="otp" placeholder="Enter 6 digit code">
                </div>
                <div class="sign-in-btn-div"><button class="btn" type="submit" name="verify">Verify</button></div>
            </form>
        </div>
    </div>
    <footer id="footerpad">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-8 mx-auto">
                    <ul class="list-inline text-center">
                        <li class="list-inline-item"><a href="#"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-facebook fa-stack-1x fa-inverse"></i></span></a></li>
                        <li class="list-inline-item"><a href="#"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-twitter fa-stack-1x fa-inverse"></i></span></a></li>
                        <li class="list-inline-item"><a href="#"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-instagram fa-stack-1x fa-inverse"></i></span></a></li>
                    </ul>
                    <p class="copyright text-center">Copyright © FuelOn 2022 | Web Design by Fuel On Team</p>
                </div>
            </div>
        </div>
    </footer>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/editproduct.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <script>
    <?php
    if (isset($_SESSION['message'])) { ?>
        //SUCCESS
        Swal.fire({
            title: 'Code Sent!',
            text: '<?php echo $_SESSION['message'] ?>',
            icon: 'success',
            button: true
        });
    <?php
        unset($_SESSION['message']);
    }
    if (isset($_SESSION['error_message'])) { 
    ?>
        Swal.fire({
            title: 'Incorrect.',
            text: '<?php echo $_SESSION['error_message'] ?>',
            icon: 'error',
            button: true
        });
    <?php
    unset($_SESSION['error_message']);
    }?>
    </script>
</body>
</html>