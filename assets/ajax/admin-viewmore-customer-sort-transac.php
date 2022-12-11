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
        $statusOne = 'Cancelled';
        $statusTwo = 'Declined';
        $statusThree = 'Pickup Failed';
        $orders = $dbh->customerOrderCountCancelled($customerID, $statusOne, $statusTwo, $statusThree);

    }elseif($filter == "all"){
        $orders = $dbh->CustomerAllOrder($customerID);
    }

        if(empty($orders)){
        ?>
        <h5 class="no-rate">No Transactions Yet</h5>
        <?php
        }else{
            foreach($orders as $row){   
                $station = $dbh->customerGetShop($row['orderID']);
                $shopDetails = $station[0];
        ?>
        <div class="prodak">
            <div class="seller-name">
                <div class="seller-div">
                    <a><i class="fas fa-store"></i>
                    <span>&nbsp;<?php echo $shopDetails['station_name'].' '. $shopDetails['branch_name']?> Branch</span><br></a>
                </div>
                <div class="order-id-div">
                    <p><?php echo $row['orderID']?></p>
                </div>
            </div>
            <?php
                $grandtotal = 0;
                $records = $dbh->customerOrders($row['orderID']);
                foreach($records as $key => $val){
                    $grandtotal += $val['total'];

                    $date = $row['transac_date'];
                    $createdate = date_create($date);
                    $new_date = date_format($createdate, "M d, Y h:i:s A");
            ?>
            <div class="sa-products">
                <div class="product-col">
                    <div class="imeds-n-neym">
                        <div class="imeyds-div">
                            <a>
                                <img class="product-img" src="assets/img/products/<?php echo $val['prod_image']?>">
                            </a>
                        </div>
                        <div>
                            <div class="neym-div">
                                <p class="product-name"><?php echo $val['product_name']?></p>
                            </div>
                            <div class="unit-price-div"><span>₱<?php echo $val['price']?></span></div>
                            <div class="quantity-div"><span>Quantity: <?php echo $row['quantity']?></span></div>
                        </div>
                    </div>
                    <div class="total-price-div"><span>₱<?php echo number_format($val['total'], 2)?></span></div>
                </div>
            </div>
            <?php 
                }
                $reason1 = 'Need to modify order';
                $reason2 = 'Found something else cheaper';
                $reason3 = 'Others / Change of mind';
                $reason4 = 'Out of stock';
                $reason5 = 'Did not picked up the order';
            ?>
            <div class="summary">
                <div class="left-div">
                    <div class="payment-div"><span>Payment:</span>
                        <p><?php echo $val['payment_method']?></p>
                    </div>
                    <div class="order-date-div"><span>Order Date:</span>
                        <p><?php echo $new_date ?></p>
                    </div>  
                    <?php
                    if($val['order_status'] == "Cancelled" || $val['order_status'] == "Declined" || $val['order_status'] == "Pickup Failed"){
                    ?>
                    <div class="cancel-div">
                        <span>Cancellation Details:</span>
                        <?php 
                        if($val['cancel_reason'] == "reason1"){
                        ?>
                            <p>Reason: <?php echo $reason1?></p>
                        <?php
                        }elseif($val['cancel_reason'] == "reason2"){
                        ?>
                            <p>Reason: <?php echo $reason2?></p>
                        <?php
                        }elseif($val['cancel_reason'] == "reason3"){
                        ?>
                            <p>Reason: <?php echo $reason3?></p>
                        <?php
                        }elseif($val['cancel_reason'] == "reason4"){
                        ?>  
                            <p>Reason: <?php echo $reason4?></p>
                        <?php
                        }elseif($val['cancel_reason'] == "reason5"){
                        ?>
                            <p>Reason: <?php echo $reason5?></p>
                        <?php
                        }?>
                    </div>
                    <?php
                    }
                    ?> 
                </div>
                <div class="right-div">
                    <div class="order-total-div"><span>Order Total:</span>
                        <p>₱<?php echo number_format($grandtotal, 2) ?></p>
                    </div>
                    <div class="status-div"><span>Status:</span>
                        <p><?php echo $val['order_status']?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php
            }
        }
    ?>
