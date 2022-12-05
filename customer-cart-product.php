<?php
session_start();
if(isset($_SESSION['userID'])){
    $userID = $_SESSION["userID"];
    $username = $_SESSION["fname"];
    $userType = $_SESSION["userType"];
    $userpic = $_SESSION['userPic'];

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


$products = $dbh->oneProduct($prodID);
$prod = $products[0];

$cart = $dbh->oneCart($prodID, $userID);
$item = $cart[0];

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
    <div class="container product-container">
    <form action="assets/includes/updateCart-inc.php?prodID=<?php echo $item['productID'] ?>&quantity=<?=$item['quantity']?>" method="post">
            
            <div class="row justify-content-center" id="Product-details">
                <input type="hidden" name="prodID" value="<?php echo $item['productID'] ?>"/>
                <div class="col-lg-6 product-image-col"><img src="assets/img/products/<?php echo $prod['prod_image']; ?>"></div>
                <div class="col-lg-6 product-info-col">
                    <div class="row" id="Product-name-and-price">
                        <div class="col-12 col-prod-name">
                            <h2 name="prodName"><?php echo $item['product_name']; ?></h2>
                        </div>
                        <div class="col col-prod-price">
                            <p>₱<?php echo $prod['price']; ?></p>
                        </div>
                    </div>
                    <div class="row" id="Product-description">
                        <div class="col"><label class="form-label">Description</label>
                            <p><?php echo $prod['description']; ?><br></p>
                        </div>
                    </div>
                    <div class="row" id="Product-quantity">
                    <div class="col"><label class="form-label">Quantity</label><input class="form-control" type="number" name="quantity" min="1" max="<?=$prod['quantity']?>" value="<?=$item['quantity']?>"></div>
                    </div>
                    <div class="row text-end" id="Product-btns">
                        <div class="col">
                            <div><button class="btn btn-primary" type="submit" name="add"><i class="la la-shopping-cart"></i>&nbsp;Update Cart</button></div>
                            <div><a class="btn btn-primary" type="submit" name="back" href="customer-cart.php">Cancel</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="Product-seller">
                <div class="col-sm-3 col-md-3 col-lg-2 seller-img-col"><img src="assets/img/profiles/<?php echo $shop['user_image'] ?>">
                    <p><?php echo $shop['firstname'] ?></p>
                </div>
                <div class="col-sm-9 col-md-9 col-lg-10 seller-btn-col">
                    <div><a class="btn btn-primary" role="button"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-message">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M4 21v-13a3 3 0 0 1 3 -3h10a3 3 0 0 1 3 3v6a3 3 0 0 1 -3 3h-9l-4 4"></path>
                                <line x1="8" y1="9" x2="16" y2="9"></line>
                                <line x1="8" y1="13" x2="14" y2="13"></line>
                            </svg>&nbsp;Chat Now</a></div>
                    <div><a class="btn btn-primary" role="button" href="view-store.php?stationID=<?php echo $_GET['stationID'] ?>"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-building-store">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <line x1="3" y1="21" x2="21" y2="21"></line>
                                <path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4"></path>
                                <line x1="5" y1="21" x2="5" y2="10.85"></line>
                                <line x1="19" y1="21" x2="19" y2="10.85"></line>
                                <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4"></path>
                            </svg>&nbsp;View Store</a></div>
                    </div>
            </div>
        </form>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
</body>

</html>