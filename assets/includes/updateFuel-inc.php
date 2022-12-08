<?php
ini_set('display_errors', 1);
error_reporting(-1);
session_start();
if(isset($_SESSION['userID'])){
    $userID = $_SESSION['userID'];
}

date_default_timezone_set('Asia/Manila');
$date = date("Y-m-d H:i:s");

require_once("../classes/dbHandler.php");
$dbh = new Config();

if(isset($_POST['save'])){

    if(isset($_GET['fuelID'])){
        $fuelID = $_GET['fuelID'];
    }

    if(isset($_POST['fuelType'])){
        $fuelType = $_POST['fuelType'];
    }
    else{
        $fuelType = '';
    }

    if(isset($_FILES['image']['name'])){
        $image = $_FILES['image']['name'];
    }

    if(isset($_POST['newPrice'])){
        $newPrice = $_POST['newPrice'];
    }

    if(isset($_POST['oldPrice'])){
        $oldPrice = $_POST['oldPrice'];
    }

    if(isset($_POST['fuel_status'])){
        $fuel_status = $_POST['fuel_status'];
    }

    if($newPrice == $oldPrice){
        if(empty($image)){
            $dbh->updateFuel($fuelID, $fuelType);
            $dbh->updateFuelStatus($fuelID, $fuel_status);
        }
        else{
            $image = $_FILES['image']['name'];
            $size = $_FILES['image']['size'];
            $tmp_name = $_FILES['image']['tmp_name'];
            $img_extension = pathinfo($image, PATHINFO_EXTENSION);
            $allowed_extension = array("jpg", "jpeg", "png");
            $error = $_FILES['image']['error'];
        
            if ($error === 0){
                if ($size > 1000000) {
                        $dbh->info("../../store-update-myfuel.php?fuelID=$fuelID", "Your file is too large.");
                }else{
                    if (!in_array($img_extension, $allowed_extension)) {
                        $dbh->info("../../store-update-myfuel.php?fuelID=$fuelID", "You cannot upload this type of file.");
                    }
                    else
                    {   
                        $path = '../img/products/'.$image;
                        move_uploaded_file($tmp_name, $path); 
            
                        $dbh->updateFuelPic($fuelID, $fuelType, $image);
                        $dbh->updateFuelStatus($fuelID, $fuel_status); 
                    }
                }
            }
        }    
    }
    elseif($newPrice !== $oldPrice){
        if(empty($image)){
            $dbh->updateFuel($fuelID, $fuelType);
            $dbh->updateFuelStatus($fuelID, $fuel_status);
        }
        else{
            $image = $_FILES['image']['name'];
            $size = $_FILES['image']['size'];
            $tmp_name = $_FILES['image']['tmp_name'];
            $img_extension = pathinfo($image, PATHINFO_EXTENSION);
            $allowed_extension = array("jpg", "jpeg", "png");
            $error = $_FILES['image']['error'];
        
            if ($error === 0){
                if ($size > 1000000) {
                        $dbh->info("../../store-update-myfuel.php?fuelID=$fuelID", "Your file is too large.");
                }else{
                    if (!in_array($img_extension, $allowed_extension)) {
                        $dbh->info("../../store-update-myfuel.php?fuelID=$fuelID", "You cannot upload this type of file.");
                    }
                    else
                    {   
                        $path = '../img/products/'.$image;
                        move_uploaded_file($tmp_name, $path); 
            
                        $dbh->updateFuelPic($fuelID, $fuelType, $image);
                        $dbh->updateFuelStatus($fuelID, $fuel_status); 
                    }
                }
            }
        }
        $dbh->updateFuelPrice($newPrice, $oldPrice, $date, $fuelID);
        $dbh->updateFuelStatus($fuelID, $fuel_status);
    }       
    $dbh->success("../../store-mytimeline.php", "Fuel updated successfully!");
}