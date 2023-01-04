<?php
    session_start();

    if(isset($_SESSION['userID'])){
        $customerID = $_SESSION['userID'];
    }

    if(isset($_POST['checkout'])){

        $payment = "Cash Upon Pickup";
        
        require_once('../classes/dbHandler.php');
        $dbh = new Config();

        $shops = $dbh->divideShops($customerID);
        $total = 0;

        foreach($shops as $shop){
            $storeID = $shop['shopID'];
            $prefix = 'ORD';
            $randID = rand(time(), 100000000);
            $orderID = $prefix."-$randID";
            $sort = $dbh->sortCartCheckOut($customerID, $storeID);

            foreach($sort as $val){
                $shopID = $shop['shopID'];
                $prodID = $val['productID'];
                $prodName = $val['product_name'];
                $prodPrice = $val['price'];

                $prodquants = $dbh->quantity($prodID);
                $quants = $prodquants[0];

                $quantity = $val['quantity'];
                //pang bawas sa quantity ng products
                $updatedStock = $quants['stocks'] - $quantity;   

                $total =  $prodPrice * $val['quantity'];
                $orderID;
                $payment;

                $type = "Ordered";
            
                date_default_timezone_set('Asia/Manila');
                $orderDate = date("Y-m-d H:i:s");

                $dbh->checkOut($shopID, $customerID, $prodID, $prodName, $prodPrice, $quantity, $total, $orderID, $payment, $orderDate);
                $dbh->createNotif($shopID, $customerID, $orderID, $type, $orderDate);
                $dbh->updateProdStock($updatedStock, $prodID);

                // echo 'shop ID: ' . $shopID . '</br>';
                // echo 'customer ID: ' . $customerID . '</br>';
                // echo 'product ID: ' . $prodID . '</br>';
                // echo 'product Name: ' . $prodName . '</br>';
                // echo 'price: ' . $prodPrice . '</br>';
                // echo 'quantity: ' . $quantity . '</br>';
                // echo 'total: ' . $total . '</br>';
                // echo 'order ID: ' . $orderID . '</br>';
                // echo 'payment: ' . $payment . '</br>';
                // echo 'order date: ' . $date . '</br></br>';
            }
        }
        $dbh->removeProdAftrChkout($customerID);
        $dbh->success("../../customer-purchases-pending.php", "Checked out successfully!");

    }
