<?php
session_start();

require_once('../classes/dbHandler.php');
$dbh = new Config();

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
}

if (isset($_POST['prodID'])) {
    $prodID = $_POST['prodID'];
}

if (isset($_POST['quantity'])) {
    $quantity = $_POST['quantity'];
}

$dbh->updateCartQuantity($quantity, $prodID, $userID);

echo 'success';