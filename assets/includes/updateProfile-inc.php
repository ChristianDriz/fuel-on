<?php
    ini_set('display_errors', 1);
    error_reporting(-1);
    session_start();

    require_once('../classes/dbHandler.php');
    $dbh = new Config();

    //PANG IMAGE
    if(isset($_POST['save'])){

        if(isset($_SESSION['userID'])) {
            $userID = $_SESSION['userID'];
            $userType = $_SESSION['userType'];

        } else {
            $userID = '';
        }

        if(isset($_POST['email'])) {
            $email = $_POST['email'];
        } else {
            $email = '';
        }

        if(isset($_POST['firstname'])) {
            $firstname = $_POST['firstname'];
        } else {
            $firstname = '';
        }

        if(isset($_POST['lastname'])) {
            $lastname = $_POST['lastname'];
        } else {
            $lastname = '';
        }

        if(isset($_POST['phone'])) {
            $phone_num = $_POST['phone'];
        } else {
            $phone_num = '';
        }

        if(isset($_FILES['image']['name'])){
            $image = $_FILES['image']['name'];
        }
    }
    if(empty($image)){
        $dbh->updateAccounts($userID, $firstname, $lastname, $phone_num);
        $_SESSION['fname'] = $firstname;

        if ($userType == 1) {
            $dbh->success("../../customer-account-settings.php", "Profile Updated Successfully!");
        }else if ($userType == 2){
            $dbh->success("../../store-account-settings.php", "Profile Updated Successfully!");
        }else if ($userType == 0){
            $dbh->success("../../admin-account-settings.php", "Profile Updated Successfully!");
        }              
    }
    else{
        $image = $_FILES['image']['name'];
        $size = $_FILES['image']['size'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $img_extension = pathinfo($image, PATHINFO_EXTENSION);
        $allowed_extension = array("jpg", "jpeg", "png");
        $error = $_FILES['image']['error'];
        
        if ($size > 1000000) {
            if ($userType == 1) {
                $dbh->info("../../customer-account-settings.php", "Your file is too large.");
            }
            else if ($userType == 2){
                $dbh->info("../../store-account-settings.php", "Your file is too large.");
            }
            else if ($userType == 0){
                $dbh->info("../../admin-account-settings.php", "Your file is too large.");
            }  
        }else if(!in_array($img_extension, $allowed_extension)){
            if ($userType == 1) {
                $dbh->info("../../customer-account-settings.php", "You cannot upload this type of file.");
            }
            else if ($userType == 2){
                $dbh->info("../../store-account-settings.php", "You cannot upload this type of file.");
            }
            else if ($userType == 0){
                $dbh->info("../../admin-account-settings.php", "You cannot upload this type of file.");
            }
        }
        else{   
            $path = '../img/profiles/'.$image;
            move_uploaded_file($tmp_name, $path); 
            
            $dbh->updateAccountsImage($userID, $firstname, $lastname, $phone_num, $image);
            $_SESSION['fname'] = $firstname;
            $_SESSION['userPic'] = $image;

            if ($userType == 1) {
                $dbh->success("../../customer-account-settings.php", "Profile Updated Successfully!");
            }
            else if ($userType == 2){
                $dbh->success("../../store-account-settings.php", "Profile Updated Successfully!");
            }
            else if ($userType == 0){
                $dbh->success("../../admin-account-settings.php", "Profile Updated Successfully!");
            }       
        }  
    }