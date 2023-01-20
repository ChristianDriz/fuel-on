<?php 

    include '../db.conn.php';
    require_once("../classes/dbHandler.php");
    $dbh = new Config();

    if(isset($_POST['customerID'])){
        $customerID = $_POST['customerID'];
    }
    else{
        $customerID = 0;
    }

    if(isset($_POST['key'])){

        $key = "%{$_POST['key']}%";
    }

    $sql = 'SELECT * 
    FROM tbl_transactions 
    WHERE customerID = ? 
    AND orderID 
    LIKE ? 
    GROUP BY orderID 
    ORDER BY date_ordered DESC';
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$customerID, $key]);

    if($stmt->rowCount() > 0){
        $prods = $stmt->fetchAll();

        foreach($prods as $order){   
            $station = $dbh->customerGetShop($order['orderID']);
            $shopDetails = $station[0];
    ?>
    <div class="view-order-container">
        <div class="header-div">
            <div class="seller-div">
                <img src="assets/img/profiles/<?php echo $shopDetails['user_image']?>">
                <a href="admin-viewmore-stores-allratings.php?shopID=<?= $shopDetails['shopID']?>"><?php echo $shopDetails['station_name'].' '. $shopDetails['branch_name']?></a>
            </div>
            <div>
                <span><?php echo $order['orderID']?></span><span class="divider">|</span><span class="status"><?php echo $order['order_status']?></span>
            </div>
        </div>
        <?php
            //ordered
            if($order['order_status'] == "Ordered"){
        ?>
        <div class="transac-date-div">
            <div class="col-12 col-md-4 details-col">
                <div class="payment-div">
                    <span>Payment:</span>
                    <p><?php echo $order['payment_method']?></p>
                </div>
                <div class="note-div">
                    <span>Waiting for the station to confirm the order.</span>
                </div>
            </div>
            <div class="col-12 col-md-8 order-first order-md-last timeline-div">
                <div>
                    <div class="date">
                        <span>Date Ordered:</span>
                        <p><?php echo $dbh->datetimeconverter($order['date_ordered'])?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
            //to pickup
            }elseif($order['order_status'] == "To Pickup"){
        ?>
        <div class="transac-date-div">
            <div class="col-12 col-md-4 details-col">
                <div class="payment-div">
                    <span>Payment:</span>
                    <p><?php echo $order['payment_method']?></p>
                </div>
                <div class="note-div">
                    <span><i class="fas fa-exclamation-circle"></i>Customer must pickup the order within 2 days upon approval.</span>
                </div>
            </div>
            <div class="col-12 col-md-8 order-first order-md-last timeline-div">
                <div>
                    <div class="date">
                        <span>Date Approved:</span>
                        <p><?php echo $dbh->datetimeconverter($order['date_approved'])?></p>
                    </div>
                    <div class="date">
                        <span>Date Ordered:</span>
                        <p><?php echo $dbh->datetimeconverter($order['date_ordered'])?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
            //completed
            }elseif($order['order_status'] == "Completed"){
        ?>
        <div class="transac-date-div">
            <div class="col-12 col-md-4 details-col">
                <div class="payment-div">
                    <span>Payment:</span>
                    <p><?php echo $order['payment_method']?></p>
                </div>
            </div>
            <div class="col-12 col-md-8 order-first order-md-last timeline-div">
                <div>
                    <div class="date">
                        <span>Date Completed:</span>
                        <p><?php echo $dbh->datetimeconverter($order['date_completed'])?></p>
                    </div>
                    <div class="date">
                        <span>Date Approved:</span>
                        <p><?php echo $dbh->datetimeconverter($order['date_approved'])?></p>
                    </div>
                    <div class="date">
                        <span>Date Ordered:</span>
                        <p><?php echo $dbh->datetimeconverter($order['date_ordered'])?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
            //declined, cancelled
            }elseif($order['order_status'] == "Declined" || $order['order_status'] == "Cancelled"){
        ?>
        <div class="transac-date-div">
            <div class="col-12 col-md-4 details-col">
                <div class="payment-div">
                    <span>Payment:</span>
                    <p><?php echo $order['payment_method']?></p>
                </div>
                <div class="payment-div">
                    <span>Cancellation Reason:</span>
                    <p><?php echo $dbh->cancelreason($order['cancel_reason'])?></p>
                </div>
            </div>
            <div class="col-12 col-md-8 order-first order-md-last timeline-div">
                <div>
                    <div class="date">
                        <span>Date Cancelled:</span>
                        <p><?php echo $dbh->datetimeconverter($order['date_cancelled'])?></p>
                    </div>
                    <div class="date">
                        <span>Date Ordered:</span>
                        <p><?php echo $dbh->datetimeconverter($order['date_ordered'])?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
            //pickup failed
            }elseif($order['order_status'] == "Pickup Failed"){
        ?>
        <div class="transac-date-div">
            <div class="col-12 col-md-4 details-col">
                <div class="payment-div">
                    <span>Payment:</span>
                    <p><?php echo $order['payment_method']?></p>
                </div>
            </div>
            <div class="col-12 col-md-8 order-first order-md-last timeline-div">
                <div>
                    <div class="date">
                        <span>Date Cancelled:</span>
                        <p><?php echo $dbh->datetimeconverter($order['date_cancelled'])?></p>
                    </div> 
                    <div class="date">
                        <span>Date Approved:</span>
                        <p><?php echo $dbh->datetimeconverter($order['date_approved'])?></p>
                    </div>
                    <div class="date">
                        <span>Date Ordered:</span>
                        <p><?php echo $dbh->datetimeconverter($order['date_ordered'])?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
            }
        ?>
        <div class="prodak">
            <?php
                $grandtotal = 0;
                $subtotal = 0;
                $taxrate = 0.12;
                $vat = 0;
                $records = $dbh->customerOrders($order['orderID']);
                foreach($records as $key => $val){
                    $subtotal += $val['total'];
                    $vat = $subtotal * $taxrate;
                    $grandtotal =  $subtotal + $vat;
            ?>
            <div class="sa-products">
                <a class="product-col">
                    <div class="imeds-n-neym">
                        <div class="imeyds-div">
                            <img class="product-img" src="assets/img/products/<?php echo $val['prod_image']?>">
                        </div>
                        <div>
                            <div class="neym-div">
                                <p class="product-name"><?php echo $val['product_name']?></p>
                            </div>
                            <div class="unit-price-div"><span>₱<?php echo $val['price']?></span></div>
                            <div class="quantity-div"><span>Quantity: <?php echo $val['quantity']?></span></div>
                        </div>
                    </div>
                    <div class="total-price-div"><span>₱<?php echo number_format($val['total'], 2)?></span></div>
                </a>
            </div>
            <?php
                }
            ?>
            <div class="row g-0 total-div">
                <div class="col-12">
                    <div class="row g-0 total-row">
                        <div class="col-7 col-sm-9 left-col"><span>Order Subtotal</span></div>
                        <div class="col-5 col-sm-3">
                            <p>₱<?php echo number_format($subtotal, 2) ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row g-0 total-row">
                        <div class="col-7 col-sm-9 left-col"><span>VAT (12%)</span></div>
                        <div class="col-5 col-sm-3">
                            <p>₱<?php echo number_format($vat, 2) ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row g-0 total-row">
                        <div class="col-7 col-sm-9 d-flex justify-content-end align-items-center left-col"><span>Order Total</span></div>
                        <div class="col-5 col-sm-3">
                            <p class="order-total-p">₱<?php echo number_format($grandtotal, 2) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
            }
        }else{
    ?>
    <h5 class="no-rate">No result found for order id '<?=htmlspecialchars($_POST['key'])?>'</h5>
    <?php
       }
    ?>