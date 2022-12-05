<?php
    session_start();

    require_once('../classes/dbHandler.php');
    $dbh = new Config();

    if(isset($_POST['submit'])){

        if(isset($_GET['userID'])){
            $userID = $_GET['userID'];
        }

        if(isset($_POST['mapLat'])){
            $mapLat = $_POST['mapLat'];
        }

        if(isset($_POST['mapLng'])){
            $mapLang = $_POST['mapLng'];
        }
    }

    $dbh->setUserLocation($mapLat, $mapLang, $userID);
    $dbh->success("../../customer-setlocation.php", "Location has been updated.");