<?php
session_start();

require_once('../classes/dbHandler.php');
$dbh = new Config();

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
}

if (isset($_POST['shopID'])) {
    $shopID = $_POST['shopID'];
}

$checked = $_POST['checked'] == 'true' ? 1 : 0;
$dbh->selectAllFromCart($shopID, $userID, $checked, 'store');
