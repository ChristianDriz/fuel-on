<?php
    session_start();

    if(isset($_SESSION['userID'])){
        $userID = $_SESSION['userID'];
    }

    if(isset($_GET['shopID'])){
        $stationID = $_GET['shopID'];
    }
    elseif(isset($_GET[''])){
        $stationID = 0;
    }

    if(isset($_GET['prodID'])){
        $prodID = $_GET['prodID'];
    }
    elseif(isset($_GET[''])){
        $prodID = 0;
    }

    if(isset($_GET['prodName'])){
        $prodName = $_GET['prodName'];
    }
    else{
        $prodName = '';
    }

    if(isset($_GET['image'])){
        $image = $_GET['image'];
    }
    else{
        $image = '';
    }

    if(isset($_POST['quantity'])){
        $quantity = $_POST['quantity'];
    }
    else{
        $quantity = 1;
    }

    require_once('../classes/dbHandler.php');

    $dbh = new Config();

    $records = $dbh->checkCart($prodID, $userID);
    $product = $dbh->oneProduct($prodID);
    $prod = $product[0];

    foreach($records as $record){
        $prodStocks = $dbh->getProdQuantityforCart($record['cartID']);
        $cartQuant = $dbh->getCartProdQuantity($record['cartID']);
    }
 
    if($quantity <= $prod['quantity']){
        if(empty($records)){
            $dbh->addToCart($userID, $stationID, $prodID, $prodName, $quantity);
        }
        else{
            //pang compare if cart quantity is equal to the product quantity
            foreach ($prodStocks as $prodq){
                $id = $prodq['productID'];
                $prodQuantity = $prodq['quantity'];    
            }

            foreach ($cartQuant as $cartq){
                $cartQuantity = $cartq['quantity'];    
            }

            if($prodQuantity >= $cartQuantity){
                $dbh->info("../../customer-view-products.php?prodID=$prodID&stationID=$stationID", "Add to cart failed! You have " . $cartQuantity ." item/s of this product in your cart and it would exceed your purchasing limit.");
            }else{
                $cart = $records[0];
                $quantity = $quantity + $cart['quantity'];
                $dbh->updateCart($quantity, $prodID, $userID);
            }
        }
    }
    else{
        $dbh->info("../../customer-view-products.php?prodID=$prodID&stationID=$stationID", "Add to cart failed! The quantity you input exceeds the stocks available.");        
    }
    
    $dbh->success("../../customer-view-products.php?prodID=$prodID&stationID=$stationID", "Added to cart successfully!");