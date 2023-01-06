<?php
    session_start();

    if(isset($_GET['email']) && isset($_GET['fname']) 
    && isset($_GET['lname']) &&  isset($_GET['station_name']) 
    && isset($_GET['branch']) &&  isset($_GET['address']) 
    && isset($_GET['phone']) && isset($_GET['tin_num'])){
        $email = $_GET['email'];
        $fname = $_GET['fname'];  
        $lname = $_GET['lname']; 
        $station_name = $_GET['station_name'];  
        $branch = $_GET['branch']; 
        $address = $_GET['address'];
        $phone = $_GET['phone']; 
        $tin_num = $_GET['tin_num']; 
    }
    else{
        $email = '';
        $fname = '';
        $lname = '';
        $station_name = '';
        $branch = '';
        $address = '';
        $phone = '';
        $tin_num = '';
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | Register Station</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Landing%20page%20css%20files/register-store.css">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand-md">
        <div class="container"><a class="navbar-brand" href="index.php"><i class="fas fa-gas-pump"></i>&nbsp;FUEL ON</a></div>
    </nav>
    <div class="login">
        <div class="login-div">
            <ul class="nav nav-justified signup-type">
                <li class="nav-item"><a class="nav-link" href="register-customer.php">Customer</a></li>
                <li class="nav-item"><a class="nav-link active" href="register-store.php">Station Owner</a></li>
            </ul>
            <form class="sign-in-form" action="assets/includes/registerStore-inc.php?type=2" method="post" enctype="multipart/form-data">
                <h2>Sign up</h2>
                <div class="row">
                    <div class="col-md-6 col-lg-4">
                        <div class="input-div">
                            <label class="form-label">Email</label>
                            <input class="form-control" type="email" placeholder="Enter your active email" name="email" value="<?php echo $email?>">
                        </div>
                        <div class="input-div">
                            <label class="form-label">First name</label>
                            <input class="form-control" type="text" placeholder="Enter your first name" name="fname" value="<?php echo $fname?>">
                        </div>
                        <div class="input-div">
                            <label class="form-label">Last name</label>
                            <input class="form-control" type="text" placeholder="Enter your last name" name="lname" value="<?php echo $lname?>">
                        </div>
                        <div class="input-div">
                            <label class="form-label">Station name</label>
                            <input class="form-control" type="text" placeholder="Enter station name" name="station_name" value="<?php echo $station_name?>">
                        </div>
                        <div class="input-div">
                            <label class="form-label">Branch name</label>
                            <input class="form-control" type="text" placeholder="Enter branch name" name="branch" value="<?php echo $branch?>">
                        </div>
                        <div class="input-div">
                            <label class="form-label">Station address</label>
                            <textarea class="form-control" placeholder="Enter station address" name="address"><?php echo $address?></textarea>
                        </div>
                        <div class="input-div">
                            <label class="form-label">Phone number</label>
                            <input class="form-control" type="number" placeholder="Enter your phone number" name="phone" value="<?php echo $phone?>">
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="input-div">
                            <label class="form-label">TIN number</label>
                            <input class="form-control" type="text" placeholder="000-000-000-000 9 to 12 digits" maxlength="15" name="tin_num" value="<?php echo $tin_num?>">
                        </div>
                        <div class="input-div">
                            <label class="form-label">Schedule</label>
                            <select class="form-select sched" name="schedule">
                                <option value="withclosing">With closing</option>
                                <option value="24/7">Open 24 hours</option>
                            </select>
                        </div>
                        <div class="input-div opening">
                            <label class="form-label">Opening hour</label>
                            <input class="form-control" placeholder="Enter your last name" type="time" name="opening">
                        </div>
                        <div class="input-div closing">
                            <label class="form-label">Closing hour</label>
                            <input class="form-control" placeholder="Enter your phone number" type="time" name="closing">
                        </div>
                        <div class="input-div">
                            <label class="form-label">Permit (pdf, png, jpg, jpeg) Max 2MB</label>
                            <input class="form-control file-input" type="file" placeholder="Enter your phone number" name="myfile" accept=".pdf, .png, .jpg, .jpeg">
                        </div>
                        <div class="input-div">
                            <label class="form-label">Password</label>
                            <input class="form-control input-pass" type="password" placeholder="Password minimum of 8 digits" name="password">
                        </div>
                        <div class="input-div">
                            <label class="form-label">Confirm password</label>
                            <input class="form-control input-pass" type="password" placeholder="Re-enter your password" name="confirm" >
                        </div>
                        <div class="form-check checkbox-div">
                            <input class="form-check-input show-pass" type="checkbox" id="formCheck">
                            <label class="form-check-label" for="formCheck">Show password</label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="input-div">
                            <label class="form-label">Click the map to add a marker</label>
                            <div id="maps" class="map-div"></div>
                            <input type="hidden" name="mapLat" id="mapLat" required>
                            <input type="hidden" name="mapLng" id="mapLng" required> 
                        </div>
                    </div>
                    <div class="col-sm-8 col-md-6 col-lg-4 offset-sm-2 offset-md-3 offset-lg-4">
                        <div class="sign-in-btn-div">
                            <button class="btn" type="submit" name="submit">Sign up</button>
                        </div>
                        <p class="no-account">Already have an account?<a class="sign-up-link" href="login.php">Sign in here</a></p>
                    </div>
                </div>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/Profile-edit-form.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--galing kay rose-->
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBznw3cpC9HWF3r7VOvfpTpFaC_3s2lPMY"></script> -->
    
    <!--galing kay michelle-->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_OEm-GWs2MhtvKaabGYVDO1wOE6LI9i0"></script>

    <script src="assets/js/map-locator.js"></script>
    <!-- <script src="assets/js/register-store.js"></script> -->
    <script src="assets/js/sweetalert2.js"></script>
    <script>
        $(document).ready(function(){
            $(".sched").change(function(){
                var value = $(this).val();
                
                if (value == "24/7"){
                    // $('.opening').prop('disabled', true);
                    // $('.closing').prop('disabled', true);

                    $('.opening').css('display', 'none');
                    $('.closing').css('display', 'none');
                }
                else if (value == "withclosing"){
                    // $('.opening').prop('disabled', false);
                    // $('.closing').prop('disabled', false);

                    $('.opening').css('display', 'block');
                    $('.closing').css('display', 'block');
                }
            });

            /* show password */
            var pass = false;
            $('.show-pass').click(function () {
                if(pass){
                    $('.input-pass').attr('type', 'password');
                    pass = false;
                }
                else{
                    $('.input-pass').attr('type', 'text');
                    pass = true;
                }
            });
            /* end show password*/   
        });


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

        if(isset($_SESSION['message'])) 
        { ?>
        Swal.fire({
            title: 'Successfully!',
            text: '<?php echo $_SESSION['message']?>',
            icon: 'success',
            button: true
        }).then(() => {
            window.location.href="login.php";
        });
        <?php 
        unset($_SESSION['message']);
            }
        ?>
    </script>
</body>

</html>