<?php
session_start();

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

    if(isset($_GET['payment'])){
        $payment = $_GET['payment'];
    }
    else{
        $payment = '';
    }

    if(isset($_GET['orderID'])){
        $orderID = $_GET['orderID'];
    }

    if(isset($_GET['transactionID'])){
        $transactionID = $_GET['transactionID'];
    }

    if(isset($_GET['reason'])){
        $reason = $_GET['reason'];
    }

    // echo $status;
    // echo $payment;
    // echo $transactionID;
    // echo $orderID;
    // echo $reason;

    if($status == 'declined'){
        //pang balik ng quantity if declined order
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
            $prodID = $id[$i];
            $dbh->updateQuantity($newQ, $prodID);
            $i++;
        }

        $newStatus = 'Declined';
        $dbh->updateCancelledOrder($newStatus, $reason, $orderID);
        $dbh->createNotification($transactionID, Notifications::TYPE_DECLINE_ORDER);
        //$dbh->success("../../store-orders-cancelled.php", "Transaction declined successfully!");
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
        $dbh->createNotification($transactionID, Notifications::TYPE_CANCEL_ORDER );
        //$dbh->success("../../customer-purchases-cancelled.php", "Transaction cancelled successfully!");

    }
    elseif($status == 'approved' && $payment == 'Cash Upon Pickup'){
        $newStatus = 'To Pickup';
        $dbh->updateOrder($newStatus, $orderID);
        $dbh->createNotification($transactionID, Notifications::TYPE_APPROVE_ORDER );
        //$dbh->success("../../store-orders-pickup.php", "Transaction approved!");
    }
    elseif($status == 'received' && $payment == 'Cash Upon Pickup'){
        $newStatus = 'Completed';
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
            $newQ = $prod[$i] - $tran[$i].'<br>';
            $prodID = $id[$i];
            $dbh->updateQuantity($newQ, $prodID);
            $i++;
        }

        $dbh->updateOrder($newStatus, $orderID);
        $dbh->createNotification($transactionID, Notifications::TYPE_RECEIVED_ORDER );

        //$dbh->success("../../store-orders-completed.php", "Transaction Completed!");
    }

    //WAG IDEDELETE RESERVED CODE SA ONLINE PAYMENT
    // elseif($status == 'approved' && $payment == 'Online Payment'){
    //     $newStatus = 'Unpaid';
    //     $dbh->updateOrder($newStatus, $orderID);
    //     echo "<script>alert('Transaction approved!');document.location='../../store-orders-unpaid.php'</script>";
    // }
    // elseif($status == 'paid' && $payment == 'Online Payment'){
    //     $newStatus = 'To Pickup';
    //     $dbh->updateOrder($newStatus, $orderID);
    //     echo "<script>alert('Transaction approved!');document.location='../../store-orders-pickup.php'</script>";
    // }
    // elseif($status == 'received' && $payment == 'Online Payment'){
    //     $newStatus = 'Completed';
    //     $prodQuant = $dbh->getProdQuantity($orderID);
    //     $transQuant = $dbh->getTransQuantity($orderID);
    //     $solver = $dbh->getOrdProducts($orderID);

    //     foreach ($prodQuant as $prodq){
    //         $id[] = $prodq['productID'];
    //         $prod[] = $prodq['quantity'];    
    //     }

    //     foreach($transQuant as $transq){
    //         $tran[] = $transq['quantity'];
    //     }

    //     $i = 0;

    //     foreach($solver as $newq){
    //         $newQ = $prod[$i] - $tran[$i].'<br>';
    //         $prodID = $id[$i];
    //         $dbh->updateQuantity($newQ, $prodID);
    //         $i++;
    //     }

    //     $dbh->updateOrder($newStatus, $orderID);

    //     echo "<script>alert('Transaction approved!');document.location='../../store-orders-completed.php'</script>";
    // }
    // else{
        
    // }

}
else{
    header('location: index.php');
}
