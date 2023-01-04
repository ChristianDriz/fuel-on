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

$checked = $_POST['checkbox'] == 'true' ? 1 : 0;
$dbh->updateCartCheckbox($checked, $prodID, $userID);
