<?php

session_start();

require_once('../classes/dbHandler.php');
$dbh = new Config();

if (isset($_POST['shopId'])) {
    echo $dbh->getchartData($_POST['shopId'], $_POST['status']);
}