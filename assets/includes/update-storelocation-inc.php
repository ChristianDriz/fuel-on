<?php
    session_start();

    require_once('../classes/dbHandler.php');
    $dbh = new Config();

    if(isset($_POST['update_loc'])){
        $userID = $_POST['userID'];
        $mapLat  = $_POST['mapLat'];
        $mapLng  = $_POST['mapLng'];
    }

    $dbh->updateStoreLocation($mapLat, $mapLng, $userID);
    $dbh->success("../../store-location.php", "Location Updated Successfully!");