<?php
    session_start();

    if(isset($_SESSION['userID'])){
        $customerID = $_SESSION['userID'];
    }

    // if(isset($_POST['payment'])){
    //     $payment = $_POST['payment'];
    // }
    // else{
    //     $payment = '';
    // }

    $payment = "Cash Upon Pickup";

    require_once('../classes/dbHandler.php');

    $dbh = new Config();

    $records = $dbh->displayCart($customerID);
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
            foreach($prodquants as $quants)

            $quantity = $val['quantity'];
            //pang bawas sa quantity ng products table
            $updatedStock = $quants['stocks'] - $quantity;   

            $total =  $prodPrice * $val['quantity'];
            // var_dump($total);
            // DateTime(1);
            $orderID;
            $payment;

            $type = "Ordered";
        

        $dbh->checkOut($shopID, $customerID, $prodID, $prodName, $prodPrice, $quantity, $total, $orderID, $payment);

        $dbh->createNotif($shopID, $customerID, $orderID, $type);
        $dbh->updateProdStock($updatedStock, $prodID);

        $dbh->removeProdAftrChkout($customerID);

        $dbh->success("../../customer-purchases-pending.php", "Checked out successfully!");

    }
}
