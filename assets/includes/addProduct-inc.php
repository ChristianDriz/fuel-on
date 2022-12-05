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

      if(isset($_FILES['image']['name'])){
        $image = $_FILES['image']['name'];
      }

      if(isset($_POST['quantity'])){
        $quantity = $_POST['quantity'];
      }

      if(isset($_POST['price'])){
        $price = $_POST['price'];
      }

      if(isset($_POST['description'])){
        $desc = $_POST['description'];
      }
  }
  if(empty($image) || empty($prodName) || empty($quantity) || empty($price) || empty($desc)){
    $dbh->info("../../store-add-products.php", "All fields should be filled out!");
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
              $dbh->info("../../store-add-products.php", "Your file is too large.");
      }else{
          if (!in_array($img_extension, $allowed_extension)) {
              $dbh->info("../../store-add-products.php", "You cannot upload this type of file.");
          }
          else
          {   
              $path = '../img/products/'.$image;
              move_uploaded_file($tmp_name, $path); 
              
              $dbh->insertProducts($prodName, $desc, $image, $quantity, $price, $userID);
              $dbh->success("../../store-myproducts.php", "Product added successfully!");  
          }
      }
    }
    else{
      $dbh->info("../../store-add-products.php", "You did not choose any image.");
    }
 }