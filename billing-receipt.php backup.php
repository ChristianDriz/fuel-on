<?php
session_start();
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION["userID"];
    $username = $_SESSION["fname"];
    $userType = $_SESSION["userType"];
    $userpic = $_SESSION["userPic"];

    if ($userType == 2) {
        header('location: index.php');
    }
} else {
    header('location: index.php');
}
require_once("assets/classes/dbHandler.php");
$data = new Config();

$record = $data->getShopBillingReceipt($_GET['orderId']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/material-icons.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-cart.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-navigation.css">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand sticky-top" id="top">
        <div class="container">
            <a class="btn" role="button" id="menu-toggle" href="#menu-toggle">
                <i class="fa fa-bars"></i>
            </a>
            <a class="navbar-brand">
                <i class="fas fa-gas-pump"></i>&nbsp;FUEL ON
            </a>
            <ul class="navbar-nav">
                <?php require_once('notifications-div.php'); ?>
                <li class="nav-item" id="mail"><a class="nav-link" href="chat-list.php"><i class="fas fa-envelope"></i></a></li>
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
                <li class="sidebar-brand"> <a href="customer-home.php"><i class="fas fa-home"></i><span class="icon-name">Home</span></a></li>
                <li class="sidebar-brand"> <a href="customer-map.php"><i class="fas fa-map-pin"></i><span class="icon-name">Map</span></a></li>
                <li class="sidebar-brand"> <a href="customer-products.php"><i class="fas fa-tags"></i><span class="icon-name">Products</span></a></li>
                <li class="sidebar-brand"> <a class="actives" href="customer-cart.php"><i class="fas fa-shopping-cart"></i><span class="icon-name">Cart</span></a></li>
                <li class="sidebar-brand"> <a href="customer-purchases-pending.php"><i class="fas fa-shopping-bag"></i><span class="icon-name">My Orders</span></a></li>
                <li class="sidebar-brand"> <a href="customer-account-settings.php"><i class="fas fa-user-cog"></i><span class="icon-name">My Account</span></a></li>
            </ul>
        </div>
        <div class="page-content-wrapper"></div>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="container mb-5 mt-3">
                <div class="row d-flex align-items-baseline">
                    <div class="col-xl-9">
                        <p style="color: #7e8d9f;font-size: 20px;">Invoice >> <strong>ID: #<?= $record[0]['orderID'] ?></strong></p>
                    </div>
                    <div class="col-xl-3 float-end">
                        <a class="btn btn-light text-capitalize border-0" data-mdb-ripple-color="dark"><i class="fas fa-print text-primary"></i> Print</a>
                        <a class="btn btn-light text-capitalize" data-mdb-ripple-color="dark"><i class="far fa-file-pdf text-danger"></i> Export</a>
                    </div>
                    <hr>
                </div>

                <div class="container">
                    <div class="col-md-12">
                        <div class="text-center" style="font-weight: 800;font-size: 20px;color: #3e3d3d;">
                            <i class="fas fa-gas-pump"></i>&nbsp;FUEL ON
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-xl-8">
                            <ul class="list-unstyled">
                                <li class="text-muted">To: <span style="color:#5d9fc5 ;">John Lorem</span></li>
                                <li class="text-muted">Street, City</li>
                                <li class="text-muted">State, Country</li>
                                <li class="text-muted"><i class="fas fa-phone"></i> 123-456-789</li>
                            </ul>
                        </div>
                        <div class="col-xl-4">
                            <p class="text-muted">Invoice</p>
                            <ul class="list-unstyled">
                                <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i> <span class="fw-bold">ID:</span>#123-456</li>
                                <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i> <span class="fw-bold">Creation Date: </span>Jun 23,2021</li>
                                <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i> <span class="me-1 fw-bold">Status:</span><span class="badge bg-warning text-black fw-bold">
                                        Unpaid</span></li>
                            </ul>
                        </div>
                    </div>

                    <div class="row my-2 mx-1 justify-content-center">
                        <table class="table table-striped table-borderless">
                            <thead style="background-color:#fea600 ;" class="text-white">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">Unit Price</th>
                                    <th scope="col">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>Pro Package</td>
                                    <td>4</td>
                                    <td>$200</td>
                                    <td>$800</td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td>Web hosting</td>
                                    <td>1</td>
                                    <td>$10</td>
                                    <td>$10</td>
                                </tr>
                                <tr>
                                    <th scope="row">3</th>
                                    <td>Consulting</td>
                                    <td>1 year</td>
                                    <td>$300</td>
                                    <td>$300</td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                    <div class="row">
                        <div class="col-xl-8">
                            <p class="ms-3">Add additional notes and payment information</p>

                        </div>
                        <div class="col-xl-3">
                            <ul class="list-unstyled">
                                <li class="text-muted ms-3"><span class="text-black me-4">SubTotal</span>$1110</li>
                                <li class="text-muted ms-3 mt-2"><span class="text-black me-4">Tax(15%)</span>$111</li>
                            </ul>
                            <p class="text-black float-start"><span class="text-black me-3"> Total Amount</span><span style="font-size: 25px;">$1221</span></p>
                        </div>
                    </div>
                    <hr>
                    <!-- <div class="row">
                        <div class="col-xl-10">
                            <p>Thank you for your purchase</p>
                        </div>
                        <div class="col-xl-2">
                            <button type="button" class="btn btn-primary text-capitalize" style="background-color:#60bdf3 ;">Pay Now</button>
                        </div>
                    </div> -->

                </div>
            </div>
        </div>
    </div>


    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/cart.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/Table-With-Search.js"></script>
</body>

</html>