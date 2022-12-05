<?php
    session_start();
    if(isset($_GET['cartID'])) {
        $cartID = $_GET['cartID'];
    } else {
        $cartID = 0;
    }

    require_once('../classes/dbHandler.php');
    $dbh = new Config();
    $dbh->deleteOneinCart($cartID);

    $dbh->success("../../customer-cart.php", "Product removed successfully!");