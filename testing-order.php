<?php
    require_once("assets/classes/dbHandler.php");
    $dbh = new Config();

    $orderID = "ORD-1580486896";

    $solver = $dbh->getOrdProducts($orderID);

    foreach($solver as $data){
        echo 'stocks: ' . $stocks = $data['stocks'];
        echo '<br>';
        echo 'quantity: ' . $quantity = $data['quantity'];
        echo '<br>';
        echo 'prod ID: ' . $prodID = $data['productID'];
        echo '<br>';
        echo 'new stock: ' . $newQ = $stocks + $quantity;
        echo '<br>';
        echo '<br>';

    }