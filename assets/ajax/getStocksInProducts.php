<?php  

session_start();

# check if the user is logged in
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION["userID"];
    $username = $_SESSION["fname"];
    $userType = $_SESSION["userType"];
    $userpic = $_SESSION["userPic"];
	
}
	# database connection file
	include '../db.conn.php';
	require_once("../classes/dbHandler.php");
    $data = new Config();

    $cartItemCount = $data->cartTotalItems($userID);
    $cartChecked = $data->cartAllChecked($userID);

	// $userID = $_GET['userID'];
    // $shopID = $_GET['shopID'];

	// $dbh = new Config();

?>
    <form method="post">
        <div id="product-header">
            <div class="col-5 head-col">
                <p class="produkto">Product</p>
            </div>
            <div class="col-2 head-col heder">
                <p>Unit Price</p>
            </div>
            <div class="col-2 head-col heder">
                <p>Quantity</p>
            </div>
            <div class="col-2 head-col heder">
                <p>Total Price</p>
            </div>
            <div class="col head-col heder">
                <p>Action</p>
            </div>
        </div>
    </form>
<?php
    $stations = $data->divideShops($userID);
    $grandtotal = 0;
    $i = 0;

    $selectAllChecked = 'checked';
    foreach ($stations as $station) {
        $shopID = $station['shopID'];
        $records = $data->sortCart($userID, $shopID);
        $sellers = $data->cartGetShop($shopID, $userID);
        $shops = $sellers[0];

        // Check if all shop products are checked
        $shopAllChecked = 'checked';
        foreach ($records as $record) {
            if ($record['checked'] == 0 && $record['quantity'] > 0){
                $shopAllChecked = '';
                $selectAllChecked = ''; 
            }
        }
    ?>
    <div class="prodak">
        <form action="assets/includes/selectDeselect-inc.php" method="post">
            <div class="seller-name">
                <!-- Select all per store -->
                <div class="checkbox-div">
                    <input <?= $shopAllChecked ?> type="checkbox" name="select-all-shop" class="tsekbox quant-input-update cbox-md cbox-store-selectall">
                    <input type="hidden" name="select-all-shop-id" value="<?= $shops['userID'] ?>">
                </div>
                <a href="customer-viewstore-timeline.php?stationID=<?php echo $shops['userID'] ?>">
                    <span><?php echo $shops['firstname'] . ' ' . $shops['lastname']; ?> Branch</span>
                </a>
            </div>
        </form>
        <?php
        foreach ($records as $val) {
            $subtotal = $val['price'] * $val['quantity'];

            // Only include to total if checked
            if ($val['checked'] == 1)
                $grandtotal += $val['price'] * $val['quantity'];
                $checked = $val['checked'] == 1 ? 'checked' : '';
            

            if ($val['stocks'] != 0){
                //set the quantity equal to the avail stocks if the stocks is below or equal to the added quantity of the user
                if($val['stocks'] < $val['quantity']){
                    $data->setCartQuantityEqualtoStock($val['stocks'], $val['productID'], $userID);
                    //echo "<script>document.location='customer-cart.php'</script>";
                }

                // set the quantity to 1 if the stocks is returned
                if($val['quantity'] <= 1){
                    echo $data->setCartQuantityOne($val['productID'], $userID);   
                    //echo "<script>document.location='assets/ajax/setCartQuantityZero.php?productID=". $val['stocks'] ."&userID=".$userID."'</script>";
                //echo "<script>document.location='customer-cart.php'</script>";
                }
        ?>
        <!-- <script>
                let fetchStock = function(){
                $.ajax({
                    type: "GET",
                    url: "assets/ajax/setCartQuantityZero.php",
                    data: {userID: <?php echo $userID ?>, productID: <?php echo $val['productID'] ?>}
                });
            }
            fetchStock();
            //auto update every .5 sec
            setInterval(fetchStock, 500);
        </script> -->
        <?php
              
        ?>
        <div class="sa-products">
            <form id="form-update-cart" action="assets/includes/updateCart-inc.php?prodID=<?php echo $val['productID']?>&quantity=<?= $val['quantity'] ?>" method="post">
                <div class="checkbox-div">
                    <input class="tsekbox quant-input-update cbox-md" type="checkbox" name="checked" <?= $checked ?>/>
                </div>
                <div class="product-col">
                    <div class="imeyds-div">
                        <a href="customer-view-products.php?stationID=<?php echo $val['shopID']; ?>&&prodID=<?php echo $val['productID'] ?>">
                            <img class="product-img" src="assets/img/products/<?php echo $val['prod_image']; ?>">
                        </a>
                    </div>
                    <div class="neym-div">
                        <a class="product-name" href="customer-view-products.php?stationID=<?php echo $val['shopID']; ?>&&prodID=<?php echo $val['productID'] ?>"><?php echo $val['product_name']; ?></a>
                    </div>
                    <div class="unit-price-div">
                        <span>₱<?= number_format($val['price']); ?></span>
                    </div>
                    <div class="quantity-div">
                        <input class="form-control quant-input-update" type="number" name="quantity" value="<?= $val['quantity']; ?>" min=1 max="<?php echo $val[3] ?>">
                        <p class="stocks"><?php echo $val['stocks']?> items left</p>
                    </div>
                    <div class="total-price-div">
                        <span>₱<?= number_format($subtotal); ?></span>
                    </div>
                    <div class="del-prod-div">
                        <button class="btn deleteOne" value="<?php echo $val['cartID']?>">
                            <i class="far fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <?php
            }
            else{
                //set the quantity to 0 if the stocks 0
                $data->setCartQuantityZero($val['productID'], $userID);
            
        ?>
            <!-- <script>
                let fetchStock = function(){
                $.ajax({
                    type: "GET",
                    url: "assets/ajax/setCartQuantityZero.php",
                    data: {userID: <?php echo $userID ?>, productID: <?php echo $val['productID'] ?>}
                });
            }

            fetchStock();
            //auto update every .5 sec
            setInterval(fetchStock, 500);
            </script> -->
        <div class="sa-products no-stock">
            <form>
                <div class="sold-out-div">
                    <p>No stock</p>
                </div>
                <div class="product-col">
                    <div class="imeyds-div">
                        <a href="customer-view-products.php?stationID=<?php echo $val['shopID']; ?>&&prodID=<?php echo $val['productID'] ?>">
                            <img class="product-img" src="assets/img/products/<?php echo $val['prod_image']; ?>">
                        </a>
                    </div>
                    <div class="neym-div">
                        <a class="product-name" href="customer-view-products.php?stationID=<?php echo $val['shopID']; ?>&&prodID=<?php echo $val['productID'] ?>"><?php echo $val['product_name']; ?></a>
                    </div>
                    <div class="unit-price-div">
                        <span>₱<?= number_format($val['price']); ?></span>
                    </div>
                    <div class="quantity-div">
                        <input class="form-control quant-input-update" type="number" name="quantity" disabled value="0" min=1 max="<?php echo $val['stocks'] ?>">
                        <p class="stocks"><?php echo $val['stocks']?> items left</p>
                    </div>
                    <div class="total-price-div">
                        <span>₱0</span>
                    </div>
                    <div class="del-prod-div">
                        <button class="btn deleteOne" value="<?php echo $val['cartID']?>">
                            <i class="far fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <?php
            }
        }
        ?>
    </div>
<?php
    }
?>
<!--end-->
    <div class="row g-0" id="checkout">
        <div class="col-6 col-sm-6 col-md-5 order-2 order-sm-2 order-md-first order-lg-first bottom-action">
            <div class="checkbox-div">
                <input <?php echo $selectAllChecked?> class="tsekbox delete-all" id="select-all" name="select-all" type="checkbox"/>
                <label for="select-all">Select all (<?php echo $cartItemCount?>)</label>
            </div>
            <?php if ($cartChecked == 0){?>
            <a class="btn del" role="button" id="no-selected-to-delete"><i class="far fa-trash-alt"></i></a>
            <?php
            }else{?>
            <a class="btn del" role="button" id="deleteAll"><i class="far fa-trash-alt"></i></a>
            <?php
            }?>
        </div>
        <div class="col offset-6 offset-md-0 offset-lg-0">
            <div class="total">
                <span>Total (<?=$cartChecked?> item):&nbsp;</span>
                <p class="price">₱<?= number_format($grandtotal); ?></p>
            </div>
        </div>
        <div class="col-6 col-sm-6 col-md-4 col-lg-3 order-3 ms-auto checkout-col">
            <?php if($cartChecked != 0){?>
                <div class="checkout"><a class="btn btn-primary" role="button" href="customer-checkout.php">Check Out</a></div>
            <?php 
            }else if($cartChecked == 0){?>
            <div class="checkout"><a class="btn btn-primary" role="button" id="checkOUT">Check Out</a></div>
            <?php
            }?>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"></script>
<script src="assets/js/bs-init.js"></script>
<script src="assets/js/cart.js"></script>
<script src="assets/js/Sidebar-Menu.js"></script>
<script src="assets/js/sweetalert2.js"></script>
<script>
    <?php
    if (isset($_SESSION['message'])) { ?>
        //SUCCESS
        Swal.fire({
            title: 'Successfully!',
            text: '<?php echo $_SESSION['message'] ?>',
            icon: 'success',
            button: true
        });
    <?php
        unset($_SESSION['message']);
    }
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
    ?>

    $('#no-selected-to-delete').click(function (e) { 
        e.preventDefault();
        Swal.fire({
            text: 'Please select a product(s) to be deleted',
            icon: 'info',
            showConfirmButton: false,
            timer: 2000
        });
    });

    //CONFIRMATION TO REMOVE ONE PRODUCTS IN CART
    $('.deleteOne').click(function (e) { 
        e.preventDefault();
        var value = $(this).val();
        Swal.fire({
            title: 'Confirmation',
            text: "Do you want to remove this product in your cart?",
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: 'No',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                // alert(value);
                $.ajax({
                    type: "GET",
                    url: "assets/includes/deleteCart-inc.php",
                    data: "cartID=" + value,
                    success: function (data) {
                    Swal.fire({
                        title: 'Successfully!',
                            text: 'Product removed successfully!',
                            icon: 'success',
                            button: true
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            }
        })        
    });

    //CONFIRMATION TO REMOVE ALL PRODUCTS IN CART
    $('#deleteAll').click(function() {
        Swal.fire({
            title: 'Confirmation',
            text: "Do you want to remove <?=$cartChecked?> product in your cart?",
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: 'No',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                document.location = 'assets/includes/clearCart-inc.php';
            }
        })
    });

    //notif if walang checked item bago click checkout
    $('#checkOUT').click(function() {
        Swal.fire({
            title: 'Oops...',
            text: 'You did not choose any items for checkout',
            icon: 'info',
            button: true
        });
    });

    $('#select-all').click(function() {
        $.ajax({
            type: "POST",
            url: "assets/includes/selectAll-inc.php",
            dataType: 'json',
            data: {
                checked: $(this).is(":checked")
            },
            success: function(data) {

                console.log(data);
                console.log('sadsadsa');
                location.reload();
            },
            error: function(e) {
                console.log(e.response);
                console.log('adsadsad22');
            }
        });
    });

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
                    $(".badge").html(data);
                }
            });
        }

        fetchMessageNotif();
        //auto update every .5 sec
        setInterval(fetchMessageNotif, 500);
</script>