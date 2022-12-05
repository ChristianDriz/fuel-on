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
        if (isset($_GET['userID'])) {
            $userID = $_GET['userID'];
        }

        require_once("../classes/dbHandler.php");
        $data = new Config();

        $cartItemCount = $data->cartTotalItems($userID);

        $cartChecked = $data->cartAllChecked($userID);
        $cartCheckedwithQuant = $data->cartAllCheckedwithQuant($userID);

                //to divide the shops in cart
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
                        if ($record['checked'] == 0 && $record['quantity'] >= 0){
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
                    //to show all products in cart per shop 
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
                            }

                            // set the quantity to 1 if the stocks is returned
                            if($val['quantity'] <= 1){
                                $data->setCartQuantityOne($val['productID']);   
                            }
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
                                    <span>₱<?= number_format($val['price'], 2); ?></span>
                                </div>
                                <div class="quantity-div">
                                    <input class="form-control quant-input-update" type="number" name="quantity" value="<?= $val['quantity']; ?>" min=1 max="<?php echo $val['stocks'] ?>">
                                    <p class="stocks"><?php echo $val['stocks']?> items left</p>
                                </div>
                                <div class="total-price-div">
                                    <span>₱<?= number_format($subtotal, 2); ?></span>
                                </div>
                                <div class="del-prod-div">
                                    <a class="btn deleteOne" href="assets/includes/deleteOneinCart-inc.php?cartID=<?=$val['cartID']?>">
                                        <i class="far fa-trash-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                        <?php
                        }
                        else{
                            //set the quantity to 0 if the stocks 0
                            $data->setCartQuantityZero($val['productID']);
                        ?>
                    <div class="sa-products no-stock">
                        <form>
                            <div class="sold-out-div">
                                <p>No stock</p>
                            </div>
                            <div class="product-col">
                                <div class="imeyds-div no-stock-img">
                                    <a href="customer-view-products.php?stationID=<?php echo $val['shopID']; ?>&&prodID=<?php echo $val['productID'] ?>">
                                        <img class="product-img" src="assets/img/products/<?php echo $val['prod_image']; ?>">
                                    </a>
                                </div>
                                <div class="neym-div">
                                    <a class="product-name" href="customer-view-products.php?stationID=<?php echo $val['shopID']; ?>&&prodID=<?php echo $val['productID'] ?>"><?php echo $val['product_name']; ?></a>
                                </div>
                                <div class="unit-price-div">
                                    <span>₱<?= number_format($val['price'], 2); ?></span>
                                </div>
                                <div class="quantity-div">
                                    <input class="form-control quant-input-update" type="number" name="quantity" disabled value="<?= $val['quantity']; ?>" min=1 max="<?php echo $val['stocks'] ?>">
                                    <p class="stocks"><?php echo $val['stocks']?> items left</p>
                                </div>
                                <div class="total-price-div">
                                    <span>₱<?= number_format($subtotal, 2); ?></span>
                                </div>
                                <div class="del-prod-div">
                                    <a class="btn deleteOne no-stock-btn" style="background: #f8f5f5;" href="assets/includes/deleteOneinCart-inc.php?cartID=<?=$val['cartID']?>">
                                        <i class="far fa-trash-alt"></i>
                                    </a>
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
            <div class="row g-0" id="checkout">
                <div class="col bottom-action">
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
                <div class="col-auto checkout-col">
                    <div class="total">
                        <span>Total (<?=$cartCheckedwithQuant?> item):&nbsp;</span>
                        <p class="price">₱<?= number_format($grandtotal, 2); ?></p>
                    </div>
                    <?php 
                    if($cartCheckedwithQuant != 0){?>
                        <div class="checkout"><a class="btn btn-primary" role="button" href="customer-checkout.php">Check Out</a></div>
                    <?php 
                    }else if($cartCheckedwithQuant == 0){?>
                    <div class="checkout"><a class="btn btn-primary disabled" role="button" id="checkOUT">Check Out</a></div>
                    <?php
                    }
                    ?>
                </div>
            </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/cart.js"></script>
    <script src="assets/js/sweetalert2.js"></script>