<?php
session_start();

date_default_timezone_set('Asia/Manila');
$date = date("Y-m-d H:i:s");

if(isset($_SESSION['userID'])){

    require '../classes/dbHandler.php';
    require_once '../classes/Notifications.php';

    $dbh = new Config();

    if(isset($_GET['status'])){
        $status = $_GET['status'];
    }
    else{
        $status = '';
    }

    if(isset($_GET['orderID'])){
        $orderID = $_GET['orderID'];
    }

    if(isset($_GET['reason'])){
        $reason = $_GET['reason'];
    }
    // echo $reason;

    if(isset($_GET['customerID'])){
        $customerID = $_GET['customerID'];
    }

    if(isset($_GET['shopID'])){
        $shopID = $_GET['shopID'];
    }

    if($status == 'declined'){
        //pang balik ng quantity if declined order
        // $prodQuant = $dbh->getProdQuantity($orderID);
        // $transQuant = $dbh->getTransQuantity($orderID);
        // $solver = $dbh->getOrdProducts($orderID);

        // foreach ($prodQuant as $prodq){
        //     $id[] = $prodq['productID'];
        //     $prod[] = $prodq['quantity'];    
        // }

        // foreach($transQuant as $transq){
        //     $tran[] = $transq['quantity'];
        // }

        // $i = 0;

        // foreach($solver as $newq){
        //     $newQ = $prod[$i] + $tran[$i].'<br>';
        //     $prodID = $id[$i];
        //     $dbh->updateQuantity($newQ, $prodID);
        //     $i++;
        // }

        $newStatus = 'Declined';
        $dbh->updateCancelledOrder($newStatus, $reason, $orderID);
        $dbh->createNotif($shopID, $customerID, $orderID, $newStatus, $date);

    }
    elseif($status == 'cancelled'){

        //pang balik ng quantity if cancelled order
        $prodQuant = $dbh->getProdQuantity($orderID);
        $transQuant = $dbh->getTransQuantity($orderID);
        $solver = $dbh->getOrdProducts($orderID);

        foreach ($prodQuant as $prodq){
            $id[] = $prodq['productID'];
            $prod[] = $prodq['quantity'];    
        }

        foreach($transQuant as $transq){
            $tran[] = $transq['quantity'];
        }

        $i = 0;

        foreach($solver as $newq){
            $newQ = $prod[$i] + $tran[$i].'<br>';

            // echo $newQ;
            $prodID = $id[$i];
            // echo $prodID;
            $dbh->updateQuantity($newQ, $prodID);
            $i++;
        }
        
        $newStatus = 'Cancelled';
        $dbh->updateCancelledOrder($newStatus, $reason, $orderID);
        $dbh->createNotif($shopID, $customerID, $orderID, $newStatus, $date);

    }
    // pang cancel if the user did not pickup the order
    elseif($status == 'pickup_failed'){

        //pang balik ng quantity if cancelled order
        $prodQuant = $dbh->getProdQuantity($orderID);
        $transQuant = $dbh->getTransQuantity($orderID);
        $solver = $dbh->getOrdProducts($orderID);

        foreach ($prodQuant as $prodq){
            $id[] = $prodq['productID'];
            $prod[] = $prodq['quantity'];    
        }

        foreach($transQuant as $transq){
            $tran[] = $transq['quantity'];
        }

        $i = 0;

        foreach($solver as $newq){
            $newQ = $prod[$i] + $tran[$i].'<br>';

            // echo $newQ;
            $prodID = $id[$i];
            // echo $prodID;
            $dbh->updateQuantity($newQ, $prodID);
            $i++;
        }
        
        $newStatus = 'Pickup Failed';
        $dbh->updateCancelledOrder($newStatus, $reason, $orderID);
        $dbh->createNotif($shopID, $customerID, $orderID, $newStatus, $date);

    }
    elseif($status == 'approved'){

        $newStatus = 'To Pickup';

        $dbh->updateOrder($newStatus, $orderID);
        $dbh->createNotif($shopID, $customerID, $orderID, $newStatus, $date);

    }
    elseif($status == 'received'){

        $newStatus = 'Completed';

        $dbh->updateOrder($newStatus, $orderID);
        $dbh->createNotif($shopID, $customerID, $orderID, $newStatus, $date);
    }

}
else{
    header('location: index.php');
}
