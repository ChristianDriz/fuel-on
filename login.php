<?php
session_start();

    if(isset($_GET['email'])){
        $email = $_GET['email'];
    }
    else{
        $email = '';
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
    <link rel="stylesheet" href="assets/css/Landing page css files/login.css">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand-md">
        <div class="container"><a class="navbar-brand" href="index.php"><i class="fas fa-gas-pump"></i>&nbsp;FUEL ON</a></div>
    </nav>
    <div class="login" style="background: rgb(254, 166, 0);">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-6 col-xl-4">
                    <div class="card mb-5">
                        <div class="card-body d-flex flex-column align-items-center">
                            <form class="text-center d-inline" id="login-form" method="post" action="assets/includes/validation-inc.php">
                                <h2>Login</h2>
                                <div class="mb-3"><input class="form-control" type="text" name="email" id="inputemail" placeholder="Email" value="<?php echo $email?>"></div>
                                <div id="pass" class="mb-3"><input class="form-control pass" type="password" id="inputpass" name="password" placeholder="Password" data-toggle="password" >
                                    <div class="input-group-text"><i class="far fa-eye" id="icon" onclick="toggle()"></i></div>
                                </div>
                                <p class="text-muted forgotpass">
                                    <a href="forgot-pass.php">Forgot password?</a>
                                </p>
                                <div class="mb-3"><input class="btn btn-primary d-block w-100" type="submit" name="submitbutton" value="Login"></div>
                                <p class="text-muted">Don't have an account?&nbsp;<a href="register-customer.php">Sign up here</a></p>
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
    <script src="assets/js/scripts.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // $(function() {
        //     var on_submit_function = function(evt) {
        //         evt.preventDefault(); //The form wouln't be submitted Yet.
        //         const loginForm = $(this);

        //         $.ajax({
        //             type: "POST",
        //             url: "assets/includes/checkUser-inc.php",
        //             dataType: 'json',
        //             data: {
        //                 email: $("#inputemail").val(),
        //                 password: $("#inputpass").val(),
        //             },
        //             success: function(data) {
        //                 console.log(data);
        //                 if (data.success) {
        //                     // $(this).off('submit', on_submit_function); //It will remove this handle and will submit the form again if it's all ok.
        //                     loginForm[0].submit();
        //                 }
        //             },
        //             error: function(e) {
        //                 Swal.fire({
        //                     title: 'Oops...',
        //                     text: 'Incorrect email or password',
        //                     icon: 'error',
        //                     button: true
        //                 });
        //                 return false;
        //             }
        //         });
        //     }
        //     $('#login-form').on('submit', on_submit_function); //Registering on submit.
        // });
        <?php 
        //success
        if(isset($_SESSION['message'])) {?>
        Swal.fire({
            title: 'Successfully!',
            text: '<?php echo $_SESSION['message']?>',
            icon: 'success',
            button: true
        });
        <?php 
        unset($_SESSION['message']);
        }

        //incorrect
        if(isset($_SESSION['error_message'])) {?>
        Swal.fire({
            title: 'Wrong Credentials',
            text: '<?php echo $_SESSION['error_message']?>',
            icon: 'error',
            button: true
        });
        <?php 
        unset($_SESSION['error_message']);
        }
        //Info
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
        }?>
    </script>
</body>

</html>