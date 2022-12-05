<?php
session_start();
    if(isset($_SESSION['userID'])){
        $email = $_SESSION['email'];
        $userID = $_SESSION['userID'];
        $username = $_SESSION['fname'];
        $userpic = $_SESSION['userPic'];
        $userType = $_SESSION['userType'];
    }
    else{
        header('location: login.php');
    }

    include "../classes/dbHandler.php";
    $dbh = new Config();

    if(isset($_POST['verify'])){
        $otp = $_POST['otp'];
        $user = $dbh->getVerified($email);
        foreach($user as $users){};
        $userID = $users['userID'];
        $otp_code = $users['otp_code'];

        if($otp_code != $otp){
            $dbh->error("../../login-otp.php", "Incorrect code. Please re-enter.");
        }
        elseif($otp_code == $otp){
            $dbh->updateVerified($userID);
            header('location: login-inc.php');
            $dbh->verified("../../customer-home.php", "Verification Success.");
        }
    }