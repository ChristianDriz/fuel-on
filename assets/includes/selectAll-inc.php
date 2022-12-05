<?php

require_once("../classes/dbHandler.php");
$dbh = new Config();
session_start();

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
}

$checked = $_POST['checked'] == 'true' ? 1 : 0;
echo $dbh->selectAllFromCart('', $userID, $checked, 'all');
