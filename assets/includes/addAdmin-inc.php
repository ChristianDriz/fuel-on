<?php
session_start();

require_once("../classes/dbHandler.php");
$dbh = new Config();

if(isset($_POST['addAdmin'])){

    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $pass = $_POST['pass'];
    $confirmpass = $_POST['confirmpass'];
    $type = 0;
    $verified = 1;

}
    $account = $dbh->getVerified($email);
    $data = 'fname=' .$fname. '&lname=' .$lname. '&email=' .$email. '&phone=' .$phone;

    //checking email if already used
    if(!empty($account)){
        $dbh->info("../../admin-table.php?$data", "Email already taken. Please use other email.");
    }
    //fname and lname format validation
    elseif(!preg_match("/^[a-zA-Z0-9_ -]*$/", $fname) || !preg_match("/^[a-zA-Z0-9_ -]*$/", $lname)){
        $dbh->info("../../admin-table.php?$data", "Invalid name format.");
    }
    //email format validation
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $dbh->info("../../admin-table.php?$data", "Invalid email format.");
    }
    //phone num format validation
    elseif (!preg_match("/^(09)[0-9]{0,9}$/", $phone)){
        $dbh->info("../../admin-table.php?$data", "Invalid phone number format must be 11 digits in length.");
    }
    //pass must be equal to confirm pass
    elseif($pass != $confirmpass){
        $dbh->info("../../admin-table.php?$data", "Password does not match.");
    }
    //pass lenght must be 8 chars
    elseif(strlen($pass) < 8){
        $dbh->info("../../admin-table.php?$data", "Password must be 8 characters in length.");
    }
    else{
        $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

        $dbh->addAdmin($email, $fname, $lname, $phone, $hashedPass, $type, $verified);
        $dbh->success("../../admin-table.php", "Account added successfully!");
    }
