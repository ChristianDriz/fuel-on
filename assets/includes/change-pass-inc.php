<?php
    session_start();

    require_once('../classes/dbHandler.php');
    $dbh = new Config();

    if(isset($_POST['changePass'])){

        if(isset($_SESSION['userID'])) {
            $userID = $_SESSION['userID'];
            $userType = $_SESSION['userType'];
            $email = $_SESSION['email'];

        } else {
            $userID = '';
        }

        if(isset($_POST['old_pass'])){
            $old_pass = $_POST['old_pass'];
        }

        if(isset($_POST['new_pass'])){
            $new_pass = $_POST['new_pass'];
        }

        if(isset($_POST['confirm_pass'])){
            $confirm_pass = $_POST['confirm_pass'];
        }

        //to check if the input fields are empty
        if(empty($old_pass) || empty($new_pass) || empty($confirm_pass)){
            //1 is customer, 2 is store, 3 is admin
            if ($userType == 1) {
                $dbh->info("../../customer-account-settings.php", "Input fields are empty");
            }else if ($userType == 2){
                $dbh->info("../../store-account-settings.php", "Input fields are empty");
            }else if ($userType == 0){
                $dbh->info("../../admin-account-settings.php", "Input fields are empty");
            } 
        }
        //if pass is less than 8 characters
        elseif(strlen($new_pass) < 8){
            //1 is customer, 2 is store, 3 is admin
            if ($userType == 1) {
                $dbh->info("../../customer-account-settings.php", "Password must be 8 characters in length.");
            }else if ($userType == 2){
                $dbh->info("../../store-account-settings.php", "Password must be 8 characters in length.");
            }else if ($userType == 0){
                $dbh->info("../../admin-account-settings.php", "Password must be 8 characters in length.");
            } 
        }
        else{
            $hashedPass = $dbh->checkPassword($userID, $email);
            $checkPass = password_verify($old_pass, $hashedPass[0]['password']);

            //to the verify if the password is correct
            if($checkPass == false){   
                //1 is customer, 2 is store, 3 is admin
                if ($userType == 1) {
                    $dbh->error("../../customer-account-settings.php", "Incorrect Password");
                }else if ($userType == 2){
                    $dbh->error("../../store-account-settings.php", "Incorrect Password");
                }else if ($userType == 0){
                    $dbh->error("../../admin-account-settings.php", "Incorrect Password");
                }  
            }else if($checkPass == true){
                //to check if the inputed new password is the same as the old password
                if($new_pass == $old_pass){
                    //1 is customer, 2 is store, 3 is admin
                    if ($userType == 1) {
                        $dbh->info("../../customer-account-settings.php", "New password cannot be the same as old password.");
                    }else if ($userType == 2){
                        $dbh->info("../../store-account-settings.php", "New password cannot be the same as old password.");
                    }else if ($userType == 0){
                        $dbh->info("../../admin-account-settings.php", "New password cannot be the same as old password.");
                    } 
                }else{
                    //to check if the new password is the same as confirm password
                    if($new_pass != $confirm_pass){
                        //1 is customer, 2 is store, 3 is admin
                        if ($userType == 1) {
                            $dbh->info("../../customer-account-settings.php", "Password does not match. Please check carefully.");
                        }else if ($userType == 2){
                            $dbh->info("../../store-account-settings.php", "Password does not match. Please check carefully.");
                        }else if ($userType == 0){
                            $dbh->info("../../admin-account-settings.php", "Password does not match. Please check carefully.");
                        } 
                    }
                    else{
                        //making hashed password and updating the password
                        $newhashedPass = password_hash($new_pass, PASSWORD_DEFAULT);
                        $newpass = $dbh->updatePass($newhashedPass, $userID, $email);
                        //1 is customer, 2 is store, 3 is admin
                        if ($userType == 1) {
                            $dbh->success("../../customer-account-settings.php", "Password Changed Successfully.");
                        }else if ($userType == 2){
                            $dbh->success("../../store-account-settings.php", "Password Changed Successfully.");
                        }else if ($userType == 0){
                            $dbh->success("../../admin-account-settings.php", "Password Changed Successfully.");
                        } 
                    }
                }
            }
        }
    }
    
