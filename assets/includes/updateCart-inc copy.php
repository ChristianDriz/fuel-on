<?php
session_start();

require_once('../classes/dbHandler.php');
$dbh = new Config();

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
}

if (isset($_POST['prodID'])) {
    $prodID = $_POST['prodID'];
} elseif (isset($_POST[''])) {
    $prodID = 0;
}
else
    $prodID = 0;

if (isset($_POST['quantity'])) {
    if ($_POST['quantity'] <= 0) {
        $newquantity = 1;
    } else {
        $quantity = $_POST['quantity'];
    }
} else {
    $quantity = 0;
}

$checked = isset($_REQUEST['checked']) ? 1 : 0;
$records = $dbh->checkCart($prodID, $userID);

foreach($records as $record){
    $prodStocks = $dbh->getProdQuantityforCart($record['cartID']);
}
    //pang compare if cart quantity is equal to the product quantity
    foreach ($prodStocks as $prodq){
        $id = $prodq['productID'];
        $prodQuantity = $prodq['quantity'];    
    }
    
    if($quantity > $prodQuantity){
        $dbh->updateCart($prodQuantity, $prodID, $userID, $checked);
        $dbh->info("../../customer-cart.php", "There are only $prodQuantity item(s) and you have reach the maximum quantity.");
    }
    elseif($quantity == 0){
        $dbh->updateCart($quan = 1, $prodID, $userID, $checked);
        $dbh->info("../../customer-cart.php", "Minimum quantity is 1");
    }
    else{
        $dbh->updateCart($quantity, $prodID, $userID, $checked);
    }

// Check is select all per store
if (isset($_POST['select-all-shop'])) {
    $shopId = $_POST['select-all-shop-id'];
    $dbh->selectAllFromCart($shopId, $userID, true, 'store');
} 
if (isset($_POST['deselect-all-shop'])) {
    $shopId = $_POST['select-all-shop-id'];
    $dbh->selectAllFromCart($shopId, $userID, false, 'store');
} 

// echo "<script>alert('Quantity updated successfully!');document.location='../../customer-cart.php'</script>";
echo "<script>document.location='../../customer-cart.php'</script>";
