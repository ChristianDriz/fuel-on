<?php
 
session_start();
if(isset($_SESSION['userID'])){
   $userID = $_SESSION['userID'];
}

require_once("../classes/dbHandler.php");
$dbh = new Config();

 if(isset($_POST['save'])){

    if(isset($_POST['prodName'])){
      $prodName = $_POST['prodName'];
    }

    if(isset($_POST['price'])){
      $price = $_POST['price'];
    }

    if(isset($_POST['category'])){
      $category = $_POST['category'];
    }

    if(isset($_POST['fuel_status'])){
      $fuel_status = $_POST['fuel_status'];
    }

    if(isset($_FILES['image']['name'])){
      $image = $_FILES['image']['name'];
    }
  }
  if(empty($image) || empty($prodName) || empty($price) || empty($category) || empty($fuel_status)){
    $dbh->info("../../store-add-fuel.php", "All fields should be filled out!");
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
              $dbh->info("../../store-add-fuel.php", "Your file is too large.");
      }else{
          if (!in_array($img_extension, $allowed_extension)) {
              $dbh->info("../../store-add-fuel.php", "You cannot upload this type of file.");
          }
          else
          {   
              $path = '../img/products/'.$image;
              move_uploaded_file($tmp_name, $path); 

              date_default_timezone_set('Asia/Manila');
              $date = date("Y-m-d H:i:s");
              
              $dbh->insertFuel($prodName, $category, $image, $price, $fuel_status, $date, $userID);
              $dbh->success("../../store-mytimeline.php", "Fuel added successfully!");  
          }
      }
    }
    else{
      $dbh->info("../../store-add-fuel.php", "You did not choose any image.");
    }
  }
