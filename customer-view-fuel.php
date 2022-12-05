<?php
session_start();
if(isset($_SESSION['userID'])){
        $userID = $_SESSION["userID"];
        $username = $_SESSION["fname"];
        $userType = $_SESSION["userType"];
        $userpic = $_SESSION["userPic"];

        if($userType == 2)
        { 
            header('location: index.php');
        }
    }
    else{
        header('location: index.php');
    }

require_once("assets/classes/dbHandler.php");
$dbh = new Config();

if(isset($_GET['stationID'])){
    $station = $_GET['stationID'];
}
else{
    $station = $userID;
}

if(isset($_GET['prodID'])){
    $prodID = $_GET['prodID'];
}
else{
    $prodID = 0;
}

$products = $dbh->oneFuel($prodID);
$prod = $products[0];

$stations = $dbh->oneShop($station);
$shop = $stations[0];
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
    <link rel="stylesheet" href="assets/css/Card-Group-Classic.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-navigation.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-view-product.css">
    <link rel="stylesheet" href="assets/css/Navbar-With-Button.css">
</head>

<body style="background: rgb(248,245,245);">
    <nav class="navbar navbar-light navbar-expand" id="top">
        <div class="container"><a class="navbar-brand" href="customer-home.html">&nbsp;<i class="fas fa-gas-pump"></i>&nbsp;FUEL ON</a>
        <label class="form-label">View Fuel</label>
            <ul class="navbar-nav">
                <?php require_once('notifications-div.php'); ?>
                <li class="nav-item" id="mail"><a class="nav-link" href="chat-list.php"><i class="fas fa-envelope"></i></a></li>
                <li class="nav-item dropdown" id="user"><a class="nav-link" href="customer-view-products.html" data-bs-toggle="dropdown">
                        <div class="profile-div">
                            <img src="assets/img/profiles/<?php echo $userpic ?>">
                        </div>
                        <p><?php echo $username;?></p>
                    </a>
                    <div class="dropdown-menu user"><a class="dropdown-item" href="customer-account-settings.php">Account Settings</a><a class="dropdown-item" href="customer-purchases-pending.php">My Purchases</a><a class="dropdown-item" href="assets/includes/logout-inc.php">Logout</a></div>
                </li>
            </ul>
        </div>
    </nav>
    <nav class="navbar navbar-light navbar-expand-md sticky-top" id="bot">
        <div class="container">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="customer-home.php"><i class="la la-home"></i>
                        <p>&nbsp;Home</p>
                    </a></li>
                <li class="nav-item"><a class="nav-link" href="customer-map.php"><i class="la la-map-marker"></i>
                        <p>&nbsp;Map</p>
                    </a></li>
                <li class="nav-item"><a class="nav-link" href="customer-products.php"><i class="la la-tags"></i>
                        <p>&nbsp;Products</p>
                    </a></li>
                <li class="nav-item"><a class="nav-link" href="customer-cart.php"><i class="la la-shopping-cart"></i>
                        <p>&nbsp;Cart</p>
                    </a></li>
            </ul>
        </div>
    </nav>
    <div class="container product-container">
        <form action="assets/includes/view-store.php?shopID=<?php echo $station?>" method="post">
            
            <div class="row justify-content-center" id="Product-details">
                <input type="hidden" name="prodID" value="<?php echo $prod['fuelID'] ?>"/>
                <div class="col-lg-6 product-image-col"><img src="assets/img/products/<?php echo $prod['fuel_image']; ?>"></div>
                <div class="col-lg-6 product-info-col">
                    <div class="row" id="Product-name-and-price">
                        <div class="col-12 col-prod-name">
                            <h2 name="prodName"><?php echo $prod['fuel_type']; ?></h2>
                        </div>
                        <div class="col col-prod-price">
                            <p>₱<?php echo $prod['new_price']; ?></p>
                        </div>
                    </div>
                    <div class="row" id="Product-description">
                        <div class="col"><label class="form-label">Description</label>
                            <p><?php echo $prod['description']; ?><br></p>
                        </div>
                    </div>
                    <div class="row" id="Product-quantity">
                    </div>
                    <div class="row text-end" id="Product-btns">
                        <div class="col">
                        <div><a class="btn btn-primary" role="button" href="view-store.php?stationID=<?php echo $_GET['stationID'] ?>"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-building-store">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <line x1="3" y1="21" x2="21" y2="21"></line>
                                <path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4"></path>
                                <line x1="5" y1="21" x2="5" y2="10.85"></line>
                                <line x1="19" y1="21" x2="19" y2="10.85"></line>
                                <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4"></path>
                            </svg>&nbsp;Back to Store</a></div>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
</body>

</html>