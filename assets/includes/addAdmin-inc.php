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
    $account = $dbh->checkAdminAccount();
    $acc = $account[0];

    if($email == $acc['email'] || $phone == $acc['phone_num']){
        $dbh->info("../../admin-table.php", "Account already taken. Please use other email or phone.");
    }
    elseif (!preg_match("/^(09)[0-9]{0,9}$/", $phone)){
        $dbh->info("../../admin-table.php", "Invalid phone number format.");
    }
    elseif($pass != $confirmpass){
        $dbh->info("../../admin-table.php", "Password does not match.");
    }
    else{
        $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

        $dbh->addAdmin($email, $fname, $lname, $phone, $hashedPass, $type, $verified);
        $dbh->success("../../admin-table.php", "Account added successfully!");
    }
