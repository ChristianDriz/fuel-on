<?php

require_once("../classes/dbHandler.php");
$dbh = new Config();

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
}
if ($email == "fuelon@admin.com" && $password == "fuelonline") {
    header.location('../../admin-home-panel.php');
}
else{
    echo $dbh->checkUser($_POST['email'], $_POST['password']);
}
