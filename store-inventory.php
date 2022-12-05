<?php
session_start();
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION["userID"];
    $username = $_SESSION["fname"];
    $userType = $_SESSION["userType"];
    $userpic = $_SESSION["userPic"];

    if ($userType == 1) {
        header('location: index.php');
    }
} else {
    header('location: index.php');
}

require 'assets/classes/dbHandler.php';
$dbh = new Config();

$products = $dbh->allProducts($userID);
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
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-inventory.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-navigation.css">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand sticky-top" id="top">
        <div class="container"><a class="btn" role="button" id="menu-toggle" href="#menu-toggle"><i class="fa fa-bars"></i></a><a class="navbar-brand" href="#">&nbsp;<i class="fas fa-gas-pump"></i>&nbsp;FUEL ON</a>
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
                <li class="sidebar-brand"> <a href="store-home.php"><i class="fas fa-home"></i><span class="icon-name">Dashboard</span></a></li>
                <li class="sidebar-brand"> <a href="store-location.php"><i class="fas fa-map-marked-alt"></i><span class="icon-name">Location</span></a></li>
                <li class="sidebar-brand"> <a href="store-orders-pending.php"><i class="fas fa-shopping-basket"></i><span class="icon-name">Orders</span></a></li>
                <li class="sidebar-brand"> <a href="store-mytimeline.php"><i class="fas fa-store"></i><span class="icon-name">My Shop</span></a></li>
                <li class="sidebar-brand"> <a class="actives" href="store-inventory.php"><i class="fas fa-clipboard"></i><span class="icon-name">Inventory</span></a></li>
            </ul>
        </div>
        <div class="page-content-wrapper"></div>
    </div>


    <div class="container">
        <div class="col search-table-col">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="inventory-header cs">
                        <tr>
                            <th id="trs-hd-1" class="col-lg-2">Product Name</th>
                            <th id="trs-hd-2" class="col-lg-1">Price</th>
                            <th id="trs-hd-3" class="col-lg-2">Sold</th>
                            <th id="trs-hd-4" class="col-lg-2">Current Stock</th>
                            <th id="trs-hd-5" class="col-lg-1">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $soldp = 0;
                        foreach ($products as $prod) {
                            $prodID = $prod['productID'];
                            $soldprod = $dbh->soldProducts($userID, $prodID);
                            foreach ($soldprod as $sold) {
                                $soldp += $sold['quantity'];
                            }
                        ?>
                            <tr>
                                <td class="name"><?= $prod['product_name'] ?></td>
                                <td class="price"><?= '₱' . number_format($prod['price'], 2) ?></td>
                                <td class="sold"><?= $soldp ?></td>
                                <!-- Display sold out -->
                                <?php
                                if ($prod['quantity'] == 0) { ?>
                                    <td class="sold-out">SOLD OUT</td>
                                <?php } else { ?>
                                    <td class="current-stock"><?= $prod['quantity'] ?></td>
                                <?php } ?>
                                <td><a class="btn btn-primary" role="button" href="store-edit-stock-inventory.php?prodID=<?= $prod['productID'] ?>">Edit</a></td>
                            </tr>
                        <?php
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/Table-With-Search.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
</body>

</html>