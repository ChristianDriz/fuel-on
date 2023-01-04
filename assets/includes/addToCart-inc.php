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

    require_once('../classes/dbHandler.php');

    $dbh = new Config();

    $records = $dbh->checkCart($prodID, $userID);
    $product = $dbh->oneProduct($prodID);
    $prod = $product[0];

    foreach($records as $record){
        $prodStocks = $dbh->getProdQuantityforCart($record['cartID']);
        $cartQuant = $dbh->getCartProdQuantity($record['cartID']);
    }

    // echo $quantity;

    if(empty($records)){
        //quantity can change by the user not greater than the stock available which is the prod[quantity]
        if($quantity > $prod['quantity']){
            $dbh->info("../../customer-view-products.php?prodID=$prodID&stationID=$stationID", "Add to cart failed! The quantity you input exceeds the stocks available.");        
        }
        elseif($quantity == 0 || empty($quantity)){
            $dbh->info("../../customer-view-products.php?prodID=$prodID&stationID=$stationID", "Add to cart failed! Minimum quantity is 1.");        
        }
        else{
            $dbh->addToCart($userID, $stationID, $prodID, $prodName, $quantity);
            $dbh->success("../../customer-view-products.php?prodID=$prodID&stationID=$stationID", "Added to cart successfully!");
        }
    }
    else{
        //product stocks
        $prodq = $prodStocks[0];
        $prodQuantity = $prodq['quantity'];    
    
        //cart quantity
        $cartq = $cartQuant[0];
        $cartQuantity = $cartq['quantity'];    

        //remaining quantity that the user can add to cart
        $remaining = $prodQuantity - $cartQuantity;  

        //new quantity
        $newquantity = $quantity + $cartQuantity;

        if($quantity > $prodQuantity){
            $dbh->info("../../customer-view-products.php?prodID=$prodID&stationID=$stationID", "Add to cart failed! The quantity you input exceeds the stocks available.");        
        }
        elseif($quantity > $remaining){
            $dbh->info("../../customer-view-products.php?prodID=$prodID&stationID=$stationID", "Add to cart failed! You have " . $cartQuantity ." item/s of this product in your cart and it would exceed your purchasing limit.");
        }
        elseif($quantity == 0){
            $dbh->info("../../customer-view-products.php?prodID=$prodID&stationID=$stationID", "Add to cart failed! Minimum quantity is 1.");        
        }
        else{
            $dbh->updateCartQuantity($newquantity, $prodID, $userID);
            $dbh->success("../../customer-view-products.php?prodID=$prodID&stationID=$stationID", "Added to cart successfully!");
        }
    }