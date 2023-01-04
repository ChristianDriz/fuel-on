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

        require_once("../classes/dbHandler.php");
        $data = new Config();

        $cartItemCount = $data->cartTotalItems($userID);
        $cartItemCountwithQuantity = $data->cartTotalItemswithQuantity($userID);
        $cartChecked = $data->cartAllChecked($userID);
        $cartCheckedwithQuant = $data->cartAllCheckedwithQuant($userID);

        //to divide the shops in cart
        $stations = $data->divideShops($userID);
        $grandtotal = 0;
        $i = 0;
        $selectAllChecked = 'checked';
        foreach ($stations as $station) {
            //to get the station details 
            $sellers = $data->cartGetShop($station['shopID'], $userID);
            $shops = $sellers[0];
            
            //to display all the added products in cart per shop
            $records = $data->sortCartwithStock($userID, $station['shopID']);
        
            //Check if all shop products are checked
            $shopAllChecked = 'checked';
            foreach ($records as $sortcart) {
                if ($sortcart['checked'] == 0){
                    $shopAllChecked = '';
                    $selectAllChecked = ''; 
                }
            }
    
            if(!empty($records)){
                //if all products under the shop is out of stock, the seller name div is hidden else, visible
                $cartdata = $records[0];
                if($cartdata['stocks'] != 0){
        ?>
        <div class="prodak prodak-with-stock">
            <div class="seller-name">
                <!-- Select all in shop -->
                <div class="checkbox-div">
                    <input <?= $shopAllChecked ?> type="checkbox" name="check" class="tsekbox selectall-in-shop">
                    <input type="hidden" class="shopID" value="<?= $shops['shopID'] ?>">
                </div>
                <a href="customer-viewstore-timeline.php?stationID=<?php echo $shops['shopID']?>">
                    <i class="fas fa-store"></i>
                    <?php echo $shops['station_name'] . ' ' . $shops['branch_name']; ?>
                </a>
                <a class="message-icon" href="chat-box.php?userID=<?=$shops['shopID']?>&userType=<?=$shops['user_type']?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-message">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M4 21v-13a3 3 0 0 1 3 -3h10a3 3 0 0 1 3 3v6a3 3 0 0 1 -3 3h-9l-4 4"></path>
                        <line x1="8" y1="9" x2="16" y2="9"></line>
                        <line x1="8" y1="13" x2="14" y2="13"></line>
                    </svg>
                </a>
            </div>  
            <?php
            //to show all products in cart per shop 
            foreach ($records as $val) {
                $subtotal = $val['price'] * $val['quantity'];
                
                // Only include to total if checked
                if ($val['checked'] == 1)
                    $grandtotal += $val['price'] * $val['quantity'];
                    $checked = $val['checked'] == 1 ? 'checked' : '';

                //set the quantity equal to the avail stocks if the stocks is below or equal to the added quantity of the user
                if($val['stocks'] < $val['quantity']){
                    $data->setCartQuantityEqualtoStock($val['stocks'], $val['productID'], $userID);
                }                
                
                // set the quantity to 1 if the stocks is returned
                if($val['quantity'] == 0 && $val['stocks'] != 0){
                    $data->setCartQuantityOne($val['productID']);   
                }
            ?>
            <div class="sa-products product-data">
                <input type="hidden" class="product-id" value="<?php echo $val['productID']?>">
                <div class="form">
                    <div class="checkbox-div">
                        <input type="checkbox" class="tsekbox select-one" name="check" <?= $checked ?>/>
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
                            <span class="price" data-price="<?= $val['price'] ?>">₱<?= number_format($val['price'], 2) ?></span>
                        </div>
                        <div class="quantity-container">
                            <div class="input-group quantity-div">
                                <button class="btn decrement-btn update-quantity" type="button"><i class="fas fa-minus"></i></button>
                                <input class="form-control quantity" type="number" value="<?= $val['quantity'] ?>">
                                <button class="btn increment-btn update-quantity" type="button"><i class="fas fa-plus"></i></button>
                            </div>
                            <p class="with-stocks" data-stocks="<?php echo $val['stocks']?>"><?php echo $val['stocks']?> items left</p>
                        </div>
                        <div class="total-price-div">
                            <span class="subtotal">₱<?=number_format($subtotal, 2)?></span>
                        </div>
                        <div class="del-prod-div">
                            <a class="btn deleteOne" href="assets/includes/deleteOneinCart-inc.php?cartID=<?=$val['cartID']?>">
                                <i class="far fa-trash-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div> 
            <?php
            }
            ?>
            <div class="empty-div"></div>
        </div>
        <?php
                }
            }
        }
        $nostock = $data->sortCartnoStock($userID);
        if(!empty($nostock)){
        ?>
        <div class="prodak no-stock">
            <div class="seller-name">
                <div class="checkbox-div">
                    <input type="checkbox">
                </div>
                <p class="no-stock-p">Out of stock</p>
            </div>  
            <?php
                foreach($nostock as $val){
                    $subtotal = $val['price'] * $val['quantity'];
                    $data->setCartQuantityZero($val['productID']);
            ?>
            <div class="sa-products no-stock">
                <div class="form">
                    <div class="sold-out-div">
                        <p>No Stock</p>
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
                        <div class="unit-price-div no-stock">
                            <span>₱<?= number_format($val['price'], 2) ?></span>
                        </div>
                        <div class="quantity-container no-stock">
                            <div class="input-group quantity-div">
                                <button class="btn disabled" type="button"><i class="fas fa-minus"></i></button>
                                <input class="form-control" type="number" value="<?= $val['quantity'] ?>" disabled>
                                <button class="btn disabled" type="button"><i class="fas fa-plus"></i></button>
                            </div>
                            <p><?php echo $val['stocks']?> items left</p>
                        </div>
                        <div class="total-price-div">
                            <span>₱<?=number_format($subtotal, 2)?></span>
                        </div>
                        <div class="del-prod-div">
                            <a class="btn deleteOne" href="assets/includes/deleteOneinCart-inc.php?cartID=<?=$val['cartID']?>">
                                <i class="far fa-trash-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div> 
            <?php
                }
            ?>
            <div class="empty-div no-stock">
                <a class="remove-nostock">Remove this product/s</a>
            </div>
        </div>
        <?php
        }
        ?>
        <div class="row g-0" id="checkout">
            <div class="col bottom-action">
                <div class="checkbox-div">
                    <input type="checkbox" id="select-all" class="tsekbox delete-all" name="check" <?php echo $selectAllChecked?>/>
                    <label for="select-all" class="select-all" data-count="<?=$cartItemCountwithQuantity ?>">Select all (<?php echo $cartItemCountwithQuantity?>)</label>
                    <label for="select-all" class="all">All</label>
                </div>
                <?php if ($cartCheckedwithQuant == 0){?>
                <a class="btn del" role="button" id="no-selected-to-delete"><i class="far fa-trash-alt"></i></a>
                <?php
                }else{?>
                <a class="btn del" role="button" id="deleteAll"><i class="far fa-trash-alt"></i></a>
                <?php
                }?>
            </div>
            <div class="col-auto checkout-col">
                <div class="total">
                    <span class="item-count">Total (<?=$cartCheckedwithQuant?> item):&nbsp;</span>
                    <p class="price grand-total">₱<?= number_format($grandtotal, 2); ?></p>
                </div>
                <?php 
                    if($cartCheckedwithQuant != 0){
                ?>
                    <div class="checkout">
                        <a class="btn btn-primary place-order" role="button" href="customer-checkout.php">Check Out</a>
                    </div>
                <?php 
                    }else if($cartCheckedwithQuant == 0){
                ?>
                    <div class="checkout">
                        <a class="btn btn-primary place-order disabled" role="button" href="customer-checkout.php">Check Out</a>
                    </div>
                <?php
                    }
                ?>
            </div>
        </div>
    <script src="assets/js/cart.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('#no-selected-to-delete').click(function (e) { 
            e.preventDefault();
            Swal.fire({
                text: 'Please select a product(s) to be deleted',
                icon: 'info',
                showConfirmButton: false,
                timer: 2000
            });
        });

        //CONFIRMATION TO REMOVE ALL PRODUCTS IN CART
        $('#deleteAll').click(function() {

            // alert ('asdsads');
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
                    document.location = 'assets/includes/deleteAllinCart-inc.php';
                }
            })
        });
    </script>