<?php
session_start();

    if(isset($_SESSION['email'])){
        $email = $_SESSION["email"];
    }
    if(isset($_SESSION['email'])){
        $password = $_SESSION["password"];
    }

    include "../classes/dbHandler.php";
    include "../classes/login-classes.php";
    include "../classes/login-contr.php";

    $login = new LoginContr($email, $password);
    $dbh = new Config();

    $login->loginUser();

    if($_SESSION['userType'] == 1){
        // header("location: ../../customer-home.php");
        $dbh->success("../../customer-home.php", "Login successfully!");
    }else if($_SESSION['userType'] == 2){
        // header("location: ../../store-home.php"); 
        $dbh->success("../../store-home.php", "Login successfully!");
    }
    else if($_SESSION['userType'] == 0){
        $dbh->success("../../admin-home-panel.php", "Login successfully!");
        
    }
    