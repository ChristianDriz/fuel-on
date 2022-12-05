<?php
session_start();

if(isset($_SESSION['userID'])){
    require '../classes/dbHandler.php';
    $dbh = new Config();

    if(isset($_GET['prodID'])){
        $prodID = $_GET['prodID'];
    }
    else{
        $prodID = 0;
    }
    
    if(isset($_POST['stock'])){
        $stock = $_POST['stock'];
    }

    $products = $dbh->oneProduct($prodID);
    $prod = $products[0];

    if($stock < $prod['quantity']){
        echo "<script>alert('Product update failed! You cannot set quantity lower than the current stock.');document.location='../../store-inventory.php'</script>";
    }
    else{
        $dbh->updateQuantity($stock, $prodID);
        echo "<script>alert('Product stock updated successfully!');document.location='../../store-inventory.php'</script>";

    }

}