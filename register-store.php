<?php
    session_start();

    if(isset($_GET['email'])){
        $email = $_GET['email'];
        $firstname = $_GET['firstname'];  
        $lastname = $_GET['lastname']; 
        $station_name = $_GET['station_name'];  
        $branch = $_GET['branch']; 
        $address = $_GET['address'];
        $phone = $_GET['phone']; 
        $tin_num = $_GET['tin']; 
    }
    else{
        $email = '';
        $firstname = '';
        $lastname = '';
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
    <title>Fuel ON</title>
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
    <div class="register">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-xxl-10">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs nav-fill card-header-tabs">
                                <li class="nav-item"><a class="nav-link" href="register-customer.php">Customer</a></li>
                                <li class="nav-item"><a class="nav-link active" href="register-store.php">Store</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <form class="register-form" method="post" action="assets/includes/registerStore-inc.php?type=2" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-12 col-xxl-12">
                                        <h2>Registration</h2>
                                    </div>
                                    <div class="col-md-6 col-xl-4 input-cols">
                                        <div class="form-group">
                                            <label class="form-label">Email</label>
                                            <input class="form-control" type="email" placeholder="Owner email or business email" name="email" value="<?php echo $email?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">First name</label>
                                            <input class="form-control" type="text" name="firstname" value="<?php echo $firstname?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Last name</label>
                                            <input class="form-control" type="text" name="lastname" value="<?php echo $lastname?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Station name</label>
                                            <input class="form-control" type="text" name="station_name" value="<?php echo $station_name?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Branch name</label>
                                            <input class="form-control" type="text" name="branch" value="<?php echo $branch?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Station Address</label>
                                            <textarea class="form-control" name="address"><?php echo $address?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Contact Number</label>
                                            <input class="form-control" type="tel" name="phone" value="<?php echo $phone?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xl-4 input-cols">
                                        <div class="form-group">
                                            <label class="form-label">Tin number</label>
                                            <input class="form-control" type="text" placeholder="000-000-000-000 / 9 to 12 digits" maxlength="15" name="tin_num" value="<?php echo $tin_num?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Schedule</label>
                                            <select class="form-select form-control sched" name="schedule">
                                                <option value="withclosing">With Closing</option>
                                                <option value="24/7">Open 24 Hours </option>
                                            </select>
                                        </div>
                                        <div class="form-group opening">
                                            <label class="form-label">Opening hour</label>
                                            <input class="form-control opening" type="time" name="opening">
                                        </div>
                                        <div class="form-group closing">
                                            <label class="form-label">Closing hour</label>
                                            <input class="form-control closing" type="time" name="closing">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Permit (PDF, PNG, JPG, JPEG) Max 2MB</label>
                                            <input class="form-control" type="file" name="myfile" accept=".pdf, .png, .jpg, .jpeg">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Password</label>
                                            <input class="form-control" type="password" name="password" data-toggle="password">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Confirm Password</label>
                                            <input class="form-control" type="password" name="confirm" data-toggle="password">
                                        </div>
                                    </div>
                                    <div class="col-12 col-xl-4 map-cols">
                                        <p>Move the map to change the marker</p>
                                        <div id="maps" class="map-div"></div>
                                        <input type="hidden" name="mapLat" id="mapLat" required>
                                        <input type="hidden" name="mapLng" id="mapLng" required>   
                                    </div>
                                    <div class="col-md-8 col-lg-6 col-xl-4 offset-md-2 offset-lg-3 offset-xl-4">
                                        <button class="btn d-block w-100 signup" type="submit" name="submit">Register</button>
                                        <p class="text-muted">Already have an account?&nbsp;<a href="login.php">Login here</a></p>
                                    </div>
                                </div>
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
    <script src="assets/js/Profile-edit-form.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBznw3cpC9HWF3r7VOvfpTpFaC_3s2lPMY"></script>
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