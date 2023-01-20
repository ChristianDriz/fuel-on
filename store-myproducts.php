<?php
session_start();

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $username = $_SESSION['fname'];
    $userpic = $_SESSION['userPic'];
    $userType = $_SESSION['userType'];

    if ($userType == 1 || $userType == 0) 
    {
        header('location: index.php');
    }

} else {
    header('location: index.php');
}

require_once("assets/classes/dbHandler.php");
$dbh = new Config();

if (isset($_GET['stationID'])) {
    $station = $_GET['stationID'];
} else {
    $station = $userID;
}

$get = $dbh->getFeedback($station);
$count = $dbh->getRatings($station);
if (!empty($get) || !empty($count)) {
    $rateSum = 0;
    foreach ($get as $rate) {
        $rateSum += $rate['rating'];
    }
    $totalRate = $rateSum / $count;
} else {
    $totalRate = 0;
}

$shop = $dbh->shopDetails($userID);
$shopDetails = $shop[0];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | Station My Products</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/material-icons.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-myproducts.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-navigation.css">
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css"> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="assets/css/modal-form.css">
</head>

<body>
    <?php
        //top navigation
        include 'top-navigation.php';
    ?>
    <div id="wrapper">
        <?php
            //side navigation
            include 'side-navigation.php';
        ?>
        <div class="page-content-wrapper">
            <?php
            $kawnt = $dbh->countProdsinShop($station);
            $records = $dbh->allProductsStore($station);
            ?>
            <div class="container products-container">
                <div class="add-prod-div">
                    <h4>My Products</h4>
                    <a class="btn" role="button" href="#modal-add" data-bs-toggle="modal">Add Product</a>
                </div>
                <?php
                if (empty($records)) {
                ?>
                <div id="no-products">
                    <div class="no-prod-div"><img src="assets/img/no-product.png">
                        <h5>You don't have any products in your inventory.</h5>
                    </div>
                </div>
                <?php
                    }else {
                ?>
                <div class="products-div">
                    <div class="table-responsive">
                        <table class="product-table table">
                            <thead>
                                <tr>
                                    <!-- <th></th> -->
                                    <th class="text-center">Image</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Sold</th>
                                    <th>Stock</th>
                                    <th>Critical Level</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($records as $val) {
                                        $prodID = $val['productID'];
                                        $sold = $dbh->countShopSold($prodID);
                                ?>
                                <tr>
                                    <!-- <td></td> -->
                                    <td class="image-td">
                                        <img src="assets/img/products/<?php echo $val['prod_image'] ?>">
                                    </td>
                                    <td class="prodname-td">
                                        <p><?php echo $val['product_name'] ?></p>
                                    </td>
                                    <td>₱<?php echo $val['price']?></td>
                                    <?php
                                        if($sold == 0){
                                    ?>
                                    <td>0</td>
                                    <?php
                                        }else{
                                    ?>
                                    <td><?php echo $dbh->numberconverter($sold)?></td>
                                    <?php
                                        }
                                    ?>
                                    <td><?php echo $val['quantity'] ?></td>
                                    <td><?php echo $val['critical_level']?></td>
                                    <td>
                                        <div class="status-div">
                                            <?php
                                                if ($val['quantity'] == 0) { 
                                            ?>
                                                <p class="no-stock">No Stock</p>
                                            <?php
                                                }else if ($val['quantity'] <= $val['critical_level']){
                                            ?>
                                                <p class="critical">Critical</p>
                                            <?php
                                                } else if ($val['quantity'] > $val['critical_level']) {
                                            ?>
                                                <p class="normal">Normal</p>
                                            <?php 
                                                }
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <a class="btn edit-btn" role="button" data-bs-toggle="modal" href="#modal-update<?php echo $val['productID']?>">
                                            <i class="fas fa-pen"></i>
                                        </a>
      
                                        <!--update product modal-form-->
                                        <div class="modal fade" role="dialog" tabindex="-1" id="modal-update<?php echo $val['productID']?>">
                                            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <form class="modal-form" action="assets/includes/updateProducts-inc.php?prodID=<?php echo $val['productID'] ?>" method="post" enctype="multipart/form-data">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Update Product</h4>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row settings-row">
                                                                <div class="col-12 col-xl-4 form prod-image">
                                                                    <div class="input-div"><label class="form-label">Product Image</label>
                                                                        <div class="avatar-bg"><img src="assets/img/products/<?php echo $val['prod_image'] ?>"/></div>
                                                                    </div>
                                                                    <input class="form-control file-input image-input" type="file" name="image" accept="image/*">
                                                                    <div class="leybel">
                                                                        <p>Maximum size: 2MB</p>
                                                                        <p>File extension: JPEG, PNG</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-xl-8 form prod-details">
                                                                    <div class="input-div">
                                                                        <label class="form-label">Product Name</label>
                                                                        <input class="form-control" type="text" name="prodName" placeholder="Enter product name" value="<?php echo $val['product_name']?>" required>
                                                                    </div>
                                                                    <div class="input-div">
                                                                        <label class="form-label">Description</label>
                                                                        <textarea class="form-control" name="description" placeholder="Enter product description" required><?php echo $val['description']?></textarea>
                                                                    </div>
                                                                    <div class="input-div">
                                                                        <label class="form-label">Price</label>
                                                                        <input class="form-control" type="number" name="price" min="1" placeholder="Enter price" value="<?php echo number_format($val['price'])?>" required>
                                                                    </div>
                                                                    <div class="stocks-n-critical">
                                                                        <div class="input-div stocks-div">
                                                                            <label class="form-label">Stocks</label>
                                                                            <input class="form-control" type="number" name="stocks" min="0" placeholder="Enter stocks" value="<?php echo $val['quantity']?>" required>
                                                                        </div>
                                                                        <div class="input-div critical-div">
                                                                            <label class="form-label">Critical Level</label>
                                                                            <input class="form-control" type="number" name="criticalLevel" min="1" placeholder="Enter product critical level" value="<?php echo $val['critical_level']?>" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="button-div">
                                                                        <button class="btn cancel" type="button">Discard</button>
                                                                        <button class="btn save" type="submit" name="save">Save Changes</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php
                }
                if(isset($_GET['prodName']) || isset($_GET['stocks']) || isset($_GET['criticalLevel']) || isset($_GET['price']) || isset($_GET['description'])){
                    $prodName = $_GET['prodName'];
                    $desc = $_GET['description'];
                    $price = $_GET['price'];
                    $stocks = $_GET['stocks'];
                    $level = $_GET['level'];
                }else{
                    $prodName = "";
                    $desc = "";
                    $price = "";
                    $stocks = "";
                    $level = "";
                }
            ?>
            <!--add product modal form-->
            <div class="modal fade" role="dialog" tabindex="-1" id="modal-add">
                <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form class="modal-form" action="assets/includes/addProduct-inc.php" method="post" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h4 class="modal-title">Add Product</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row settings-row">
                                    <div class="col-12 col-xl-4 form prod-image">
                                        <div class="input-div"><label class="form-label">Product Image</label>
                                            <div class="avatar-bg"></div>
                                        </div>
                                        <input class="form-control file-input image-input" type="file" name="image" accept="image/*">
                                        <div class="leybel">
                                            <p>Maximum size: 2MB</p>
                                            <p>File extension: JPEG, PNG</p>
                                        </div>
                                    </div>
                                    <div class="col-12 col-xl-8 form prod-details">
                                        <div class="input-div">
                                            <label class="form-label">Product Name</label>
                                            <input class="form-control" type="text" name="prodName" placeholder="Enter product name" value="<?php echo $prodName?>">
                                        </div>
                                        <div class="input-div">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" name="description" placeholder="Enter product description"><?php echo $desc?></textarea>
                                        </div>
                                        <div class="input-div">
                                            <label class="form-label">Price</label>
                                            <input class="form-control" type="number" name="price" min="1" placeholder="Enter price" value="<?php echo $price?>">
                                        </div>
                                        <div class="stocks-n-critical">
                                            <div class="input-div stocks-div">
                                                <label class="form-label">Stocks</label>
                                                <input class="form-control" type="number" name="stocks" min="1" placeholder="Enter stocks" value="<?php echo $stocks?>">
                                            </div>
                                            <div class="input-div critical-div">
                                                <label class="form-label">Critical Level</label>
                                                <input class="form-control" type="number" name="criticalLevel" min="1" placeholder="Enter product critical level" value="<?php echo $level?>">
                                            </div>
                                        </div>
                                        <div class="button-div">
                                            <button class="btn cancel" type="button">Cancel</button>
                                            <button class="btn save" type="submit" name="save">Add Product</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/Profile-edit-form.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/Table-With-Search.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <!-- <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script> -->
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script>    

        let table = new DataTable('.product-table', {
            pageLength: 5,
            lengthMenu: [ 5, 10, 25, 50, 75, 100 ],

            responsive: {
                details: {
                    type: 'column',
                    target: 'tr'
                }
            },
            columnDefs: [ {
                className: 'control',
                orderable: false,
                targets:   0
            } ],
            order: [ 1, 'asc' ]
        });
        
        <?php 
        if(isset($_SESSION['info_message'])) 
            { ?>
            
        //NOTIFY 
        Swal.fire({
            title: 'Oops...',
            text: '<?php echo $_SESSION['info_message']?>',
            icon: 'info',
            button: true
        });
        <?php 
        unset($_SESSION['info_message']);
        }

        if(isset($_SESSION['message'])) {?>
        //SUCCESS
        Swal.fire({
            title: 'Successfully!',
            text: '<?php echo $_SESSION['message']?>',
            icon: 'success',
            button: true
        });
        <?php 
        unset($_SESSION['message']);
        }?>


        //for last seen update
        let lastSeenUpdate = function(){
      	    $.get("assets/ajax/active_status.php");
        }
        lastSeenUpdate();
        setInterval(lastSeenUpdate, 1000);

        //for message notif
        let fetchMessageNotif = function(){
      	$.get("assets/ajax/unread_message_count.php", 
            {
            userID: <?php echo $userID ?>
            },
            function(data){
                if (data != 0){
                    $(".message-counter").html(data);
                }
            });
        }
        fetchMessageNotif();
        //auto update every .5 sec
        setInterval(fetchMessageNotif, 500);
    </script>
</body>

</html>