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

    if(isset($_GET['customerID'])){
        $customerID = $_GET['customerID'];
    }

    if(isset($_GET['shopID'])){
        $shopID = $_GET['shopID'];
    }

    if($status == 'declined'){
        $newStatus = 'Declined';
        $dbh->updateOrder($date, $newStatus, $reason, $orderID);
        $dbh->createNotif($shopID, $customerID, $orderID, $newStatus, $date);

    }
    elseif($status == 'cancelled'){

        //pang balik ng quantity if cancelled order
        $solver = $dbh->getOrdProducts($orderID);

        foreach($solver as $data){
            $stocks = $data['stocks'];
            $quantity = $data['quantity'];
            $prodID = $data['productID'];
            $newstock = $stocks + $quantity;

            $dbh->updateQuantity($newstock, $prodID);
        }
        
        $newStatus = 'Cancelled';
        $dbh->updateOrder($date, $newStatus, $reason, $orderID);
        $dbh->createNotif($shopID, $customerID, $orderID, $newStatus, $date);

    }
    // pang cancel if the user did not pickup the order
    elseif($status == 'pickup_failed'){

        //pang balik ng quantity if cancelled order
        $solver = $dbh->getOrdProducts($orderID);

        foreach($solver as $data){
            $stocks = $data['stocks'];
            $quantity = $data['quantity'];
            $prodID = $data['productID'];
            $newstock = $stocks + $quantity;

            $dbh->updateQuantity($newstock, $prodID);
        }
        
        $newStatus = 'Pickup Failed';
        $dbh->updateOrder($date, $newStatus, $reason, $orderID);
        $dbh->createNotif($shopID, $customerID, $orderID, $newStatus, $date);

    }
    elseif($status == 'approved'){

        $newStatus = 'To Pickup';

        $dbh->updateOrder($date, $newStatus, '', $orderID);
        $dbh->createNotif($shopID, $customerID, $orderID, $newStatus, $date);

    }
    elseif($status == 'received'){

        $newStatus = 'Completed';

        $dbh->updateOrder($date, $newStatus, '', $orderID);
        $dbh->createNotif($shopID, $customerID, $orderID, $newStatus, $date);
    }
}
else{
    header('location: index.php');
}
