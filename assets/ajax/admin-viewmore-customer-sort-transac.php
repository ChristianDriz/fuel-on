<?php 

    require_once("../classes/dbHandler.php");
    $dbh = new Config();

    if(isset($_GET['customerID'])){
        $customerID = $_GET['customerID'];
    }
    else{
        $customerID = 0;
    }

    if(isset($_GET['request'])){
        $filter = $_GET['request'];
    }


    if($filter == "ordered"){
        $status = 'Ordered';
        $orders = $dbh->customerOrderCount($customerID, $status);
        
    }elseif($filter == "pickup"){
        $status = 'To Pickup';
        $orders = $dbh->customerOrderCount($customerID, $status);

    }elseif($filter == "completed"){
        $status = 'Completed';
        $orders = $dbh->customerOrderCount($customerID, $status);

    }elseif($filter == "cancelled"){
        $orders = $dbh->customerOrderCountCancelled($customerID);

    }elseif($filter == "all"){
        $orders = $dbh->CustomerAllOrder($customerID);
    }

        if(empty($orders)){
        ?>
        <h5 class="no-rate">No Transactions Yet</h5>
        <?php
        }else{
            foreach($orders as $order){   
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
                        <span>Customer must pickup the order within 2 days upon approval.</span>
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
                    $records = $dbh->customerOrders($order['orderID']);
                    foreach($records as $key => $val){
                        $grandtotal += $val['total'];
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
                <div class="total-div"><span>Order Total:</span>
                    <p>₱<?php echo number_format($grandtotal, 2) ?></p>
                </div>
            </div>
        </div>
    <?php
            }
        }
    ?>
