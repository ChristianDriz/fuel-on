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

        if(isset($_POST['station_name'])) {
            $station_name = $_POST['station_name'];
        } else {
            $station_name = '';
        }

        if(isset($_POST['branch'])) {
            $branch = $_POST['branch'];
        } else {
            $branch = '';
        }

        if(isset($_POST['address'])) {
            $address = $_POST['address'];
        } else {
            $address = '';
        }

        if(isset($_POST['phone'])) {
            $phone_num = $_POST['phone'];
        } else {
            $phone_num = '';
        }

        if(isset($_FILES['image']['name'])){
            $image = $_FILES['image']['name'];
        }

        if(isset($_POST['tin'])) {
            $tin_num = $_POST['tin'];
        } else {
            $tin_num = '';
        }

        if(isset($_FILES['permit']['name'])){
            $permit = $_FILES['permit']['name'];
        }
        
        if(isset($_POST['sched'])) {
            $sched = $_POST['sched'];
        }


        if($sched == "withClosing"){
            if(isset($_POST['opening'])) {
                $opening = $_POST['opening'];
            } else {
                $opening = '';
            }

            if(isset($_POST['closing'])) {
                $closing = $_POST['closing'];
            } else {
                $closing = '';
            }
        }elseif ($sched == "24hrs"){
            $opening = '00:00:00';
            $closing = '00:00:00';
        }

    }
    if(empty($image)){

        if(empty($permit)){
            $dbh->updateStoreAccount($userID, $firstname, $lastname, $phone_num);
            $dbh->updateShopDetails($station_name, $branch, $address, $tin_num, $opening, $closing, $userID);
            $dbh->success("../../store-account-settings.php", "Profile Updated Successfully!");
        }
        else{
            $permit = $_FILES['permit']['name'];
            $filesize = $_FILES['permit']['size'];
            $temp = $_FILES['permit']['tmp_name'];
            $extension = pathinfo($permit, PATHINFO_EXTENSION);

            //to check the file extension
            if (!in_array($extension, ['pdf', 'docx', 'png', 'jpeg', 'jpg', 'doc'])) {
                $dbh->info("../../store-account-settings.php", "You file extension must be pdf, png, jpg, or jpeg");
                die();
            } elseif ($filesize > 1000000) { // file shouldn't be larger than 1Megabyte
                $dbh->info("../../store-account-settings.php", "Your file is too large.");
                die();
            } else {
                if (file_exists($permit)) {
                    chmod($permit, 0755); //Change the file permissions if allowed
                    unlink($permit); //remove the file
                }

                $destination = '../../uploads/'.$permit;
                move_uploaded_file($temp, $destination);

                $dbh->updateShopDetailsWithPermit($permit, $station_name, $branch, $address, $tin_num, $opening, $closing, $userID);
            }
                $dbh->success("../../store-account-settings.php", "Permit Updated Successfully!");
        }
    }
    else{
        //for image
        $image = $_FILES['image']['name'];
        $size = $_FILES['image']['size'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $img_extension = pathinfo($image, PATHINFO_EXTENSION);
        $allowed_extension = array("jpg", "jpeg", "png");
        $error = $_FILES['image']['error'];
        
        if ($size > 2000000) {
            $dbh->info("../../store-account-settings.php", "Your file is too large.");

        }else if(!in_array($img_extension, $allowed_extension)){
            $dbh->info("../../store-account-settings.php", "You cannot upload this type of file.");
        }
        else
        {   

            $path = '../img/profiles/'.$image;
            move_uploaded_file($tmp_name, $path); 

            $dbh->updateStoreAccountsImage($userID, $firstname, $lastname, $phone_num, $image);

            $_SESSION['userPic'] = $image;

            $dbh->success("../../store-account-settings.php", "Profile Updated Successfully!");

        }  
    }