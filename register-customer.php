<?php
    session_start();

    if(isset($_GET['email']) || isset($_GET['fname']) || isset($_GET['lname']) || isset($_GET['phone'])){
        $email = $_GET['email'];
        $fname = $_GET['fname'];  
        $lname = $_GET['lname']; 
        $phone = $_GET['phone']; 
    }
    else{
        $email = '';
        $fname = '';
        $lname = '';
        $phone = '';
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Landing%20page%20css%20files/register.css">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand-md">
        <div class="container"><a class="navbar-brand" href="index.php"><i class="fas fa-gas-pump"></i>&nbsp;FUEL ON</a></div>
    </nav>
    <div class="register">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs nav-fill card-header-tabs">
                                <li class="nav-item"><a class="nav-link active" href="register-customer.php">Customer</a></li>
                                <li class="nav-item"><a class="nav-link" href="register-store.php">Station</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <form id="Form" method="post" action="assets/includes/registerCustomer-inc.php?type=1">
                                <h2>Registration</h2>
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input class="form-control" type="email" name="email" value="<?php echo $email?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">First name</label>
                                    <input class="form-control" type="text" name="fname" value="<?php echo $fname?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Last name</label>
                                    <input class="form-control" type="text" name="lname" value="<?php echo $lname?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Contact number</label>
                                    <input class="form-control" type="tel" name="phone" value="<?php echo $phone?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Password</label>
                                    <input class="form-control" type="password" name="password" data-toggle="password">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Confirm password</label>
                                    <input class="form-control" type="password" name="confirm" data-toggle="password">
                                </div>
                                <button class="btn btn-primary d-block w-100" type="submit" name="submit" id="register">Register</button>
                                <p class="text-muted">Already have an account?&nbsp;<a href="login.php">Login here</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
                    <p class="copyright text-center">Copyright © FuelOn 2022 | Web Design by Christian Joseph Dimla</p>
                </div>
            </div>
        </div>
    </footer>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <script>
        <?php 
        if(isset($_SESSION['error_message'])) {?>
        //Info
        Swal.fire({
            title: 'Failed!',
            text: '<?php echo $_SESSION['error_message']?>',
            icon: 'error',
            button: true
        });
        <?php 
        unset($_SESSION['error_message']);
        }
        if(isset($_SESSION['info_message'])) {
        ?>
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