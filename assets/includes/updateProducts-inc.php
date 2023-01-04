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

        if(isset($_POST['stocks'])){
            $stocks = $_POST['stocks'];
        }

        if(isset($_POST['criticalLevel'])){
            $level = $_POST['criticalLevel'];
        }

        if(isset($_POST['price'])){
            $price = $_POST['price'];
        }

        if(isset($_POST['description'])){
            $desc = $_POST['description'];
        }

        if(empty($image)){
            $dbh->updateProducts($prodName, $desc, $stocks, $level, $price, $prodID); 

            //to update the quantity in cart by prod ID setting value to zero
            if($stocks == 0){
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
                if ($size > 2000000) {
                        $dbh->info("../../store-myproducts.php", "Your file is too large.");
                }else{
                    if (!in_array($img_extension, $allowed_extension)) {
                        $dbh->info("../../store-myproducts.php", "You cannot upload this type of file.");
                    }
                    else
                    {   
                        $path = '../img/products/'.$image;
                        move_uploaded_file($tmp_name, $path); 
                        
                        $dbh->updateProdImage($prodName, $desc, $image, $stocks, $level, $price, $prodID);
                        //to update the quantity in cart by prod ID setting value to zero
                        if($stocks == 0){
                            $dbh->setCartQuantityZero($prodID);     
                        }
                        // elseif($quantity == 1){
                        //     $dbh->setCartQuantityOne($prodID);    
                        // }
                    }
                }
            }
        }
        $dbh->success("../../store-myproducts.php", "Product updated successfully!");
    }