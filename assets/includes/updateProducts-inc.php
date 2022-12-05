<?php
    ini_set('display_errors', 1);
    error_reporting(-1);
    session_start();
    if(isset($_SESSION['userID'])){
        $userID = $_SESSION['userID'];
    }
    require_once("../classes/dbHandler.php");
    $dbh = new Config();

    if(isset($_POST['save'])){

    if(isset($_GET['prodID'])){
        $prodID = $_GET['prodID'];
    }

    if(isset($_POST['prodName'])){
        $prodName = $_POST['prodName'];
    }

    if(isset($_FILES['image']['name'])){
        $image = $_FILES['image']['name'];
    }

    if(isset($_POST['price'])){
        $price = $_POST['price'];
    }

    if(isset($_POST['description'])){
        $desc = $_POST['description'];
    }

    if(isset($_POST['quantity'])){
        $quantity = $_POST['quantity'];
    }

    if(empty($image)){
        $dbh->updateProducts($prodName, $desc, $quantity, $price, $prodID); 
        //to update the quantity in cart by prod ID setting value to zero
        if($quantity == 0){
            $dbh->setCartQuantityZero($prodID);    
        }
        // elseif($quantity == 1){
        //     $dbh->setCartQuantityOne($prodID);    
        // }
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
                    $dbh->info("../../store-update-myproduct.php?productID=$prodID", "Your file is too large.");
            }else{
                if (!in_array($img_extension, $allowed_extension)) {
                    $dbh->info("../../store-update-myproduct.php?productID=$prodID", "You cannot upload this type of file.");
                }
                else
                {   
                    $path = '../img/products/'.$image;
                    move_uploaded_file($tmp_name, $path); 
                    
                    $dbh->updateProdImage($prodName, $desc, $image, $quantity, $price, $prodID);
                    //to update the quantity in cart by prod ID setting value to zero
                    if($quantity == 0){
                        $dbh->setCartQuantityZero($prodID);     
                    }
                    // elseif($quantity == 1){
                    //     $dbh->setCartQuantityOne($prodID);    
                    // }
                }
            }
        }
    }
    // echo "<script>alert('Product updated successfully!');document.location='../../store-myproducts.php'</script>";
    $dbh->success("../../store-myproducts.php", "Product updated successfully!");
}