<?php
    session_start();
    $userID = $_SESSION["userID"];
    $username = $_SESSION["fname"];
    $userType = $_SESSION["userType"];
    $userpic = $_SESSION["userPic"];

    if(isset($_GET['prodID'])){
        $prodID = $_GET['prodID'];
    }
    else{
        $prodID = 0;
    }

    require 'assets/classes/dbHandler.php';
    $dbh = new Config();
    $record = $dbh->oneProduct($prodID);
    $data = $record[0];
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
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-navigation.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-products-update.css">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand" id="top">
        <div class="container">
            <a class="navbar-brand" href="customer-home.html">&nbsp;<i class="fas fa-gas-pump"></i>&nbsp;FUEL ON</a>
            <label class="form-label">Edit Product</label>
            <ul class="navbar-nav">
                <li class="nav-item" id="store-profile"><a class="nav-link" href="view-store.php"><i class="fas fa-store"></i></a></li>
                <?php require_once('notifications-div.php'); ?>
                <li class="nav-item" id="mail"><a class="nav-link" href="chat-list.php"><i class="fas fa-envelope"></i></a></li>
                <li class="nav-item dropdown" id="user"><a class="nav-link" data-bs-toggle="dropdown">
                        <div class="profile-div">
                            <img src="assets/img/profiles/<?php echo $userpic ?>">
                        </div>
                        <p><?php echo $username; ?></p>
                    </a>
                    <div class="dropdown-menu user"><a class="dropdown-item" href="store-account-settings.html">Account Settings</a><a class="dropdown-item" href="assets/includes/logout-inc.php">Logout</a></div>
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
                        <p>&nbsp;Location</p>
                    </a></li>
                <li class="nav-item"><a class="nav-link" href="store-orders-tab-order.html"><i class="la la-credit-card"></i>
                        <p>&nbsp;Orders</p>
                    </a></li>
                <li class="nav-item"><a class="nav-link" href="store-inventory-tab.html"><i class="la la-clipboard"></i>
                        <p>&nbsp;Inventory</p>
                    </a></li>
            </ul>
        </div>
    </nav>
    <div class="container product-update-container">
        <form action="assets/includes/updateProducts-inc.php?prodID=<?php echo $_GET['prodID'] ?>" method="post" enctype="multipart/form-data">
            <div class="row header-row">
                <div class="col">
                    <div>
                        <p class="p1">Edit products</p>
                        <p class="p2">Manage your products</p>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="row profile-row">
                <div class="col-sm-12 col-md-12 col-lg-5 prod-img-col">
                    <div class="avatar">
                        <div class="avatar-bg center" id="avatar"><img class="img" style="height: 250px; width:250px;" src="<?php echo 'assets/img/products/'.$data['prod_image'] ?>"/></div>
                        
                            <input class="form-control" id="img" type="file" name="image" style="width: 249px;margin: auto;">
                        
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-7 prod-details-col">
                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-3"><label class="form-label">Product Name</label><input class="form-control" type="text" name="prodName" value="<?php echo $data['product_name'] ?>"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-7">
                            <div class="form-group mb-3"><label class="form-label">Category</label><input class="form-control" type="text" readonly="" disabled="" placeholder="<?php echo $_GET['category'];?>"></div>
                        </div>
                        <div class="col-5">
                            <div class="form-group mb-3"><label class="form-label">Price</label><input class="form-control" type="decimal" name="price" value="<?php echo $data['price'] ?>"></div>
                        </div>
                    </div>
                    <div class="form-group mb-3"><label class="form-label">Description</label><input class="form-control" name="description" value="<?php echo $data['description'] ?>"/></div>
                </div>
            </div>
            <div class="row btn-cont">
                <div class="col">
                    <div><button class="btn btn-light" type="reset">Reset</button></div>
                    <div><button class="btn btn-primary" type="submit" name="save">Save</button></div>
                </div>
            </div>
        </form>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/Profile-Edit-Form.js"></script>
</body>

</html>