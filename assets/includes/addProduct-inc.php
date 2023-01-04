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

        $data = 'prodName=' .$prodName. '&description=' .$desc. '&price=' .$price. '&stocks=' .$stocks. '&level=' .$level;
    }
    if(empty($image) || empty($prodName) || empty($stocks) || empty($level) || empty($price) || empty($desc)){
        $dbh->info("../../store-myproducts.php?$data", "All fields should be filled out!");
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
                $dbh->info("../../store-myproducts.php?$data", "Your file is too large.");
            }else
            {
                if (!in_array($img_extension, $allowed_extension)) {
                    $dbh->info("../../store-myproducts.php?$data", "You cannot upload this type of file.");
                }
                else
                {   
                    $path = '../img/products/'.$image;
                    move_uploaded_file($tmp_name, $path); 
                    
                    $dbh->insertProducts($prodName, $desc, $image, $stocks, $level, $price, $userID);
                    $dbh->success("../../store-myproducts.php", "Product added successfully!");  
                }
            }
        }
        else{
        $dbh->info("../../store-myproducts.php?$data", "You did not choose any image.");
        }
    }