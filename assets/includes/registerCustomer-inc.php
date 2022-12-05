<?php
session_start();
if (isset($_POST['submit'])){
    include "../classes/dbHandler.php";
    include "../classes/signup-classes.php";
    include "../classes/signup-contr.php";

    $dbh = new Config();

    $_SESSION['email'] = $_POST['email'];
    $_SESSION['fname'] = $_POST['fname'];
    $_SESSION['lname'] = $_POST['lname'];
    $_SESSION['phone'] = $_POST['phone'];
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['confirm'] = $_POST['confirm'];
    $_SESSION['type'] = $_GET['type'];
    // $_SESSION['status'] = 'activated';

    $email = $_POST['email'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    $type = $_GET['type'];
    $status = 'activated';
    $verified = 0;

    // echo $email;

    $signup = new SignupContr($email, $fname, $lname, $phone, $password, $confirm, $type);
    $signup->checkInput();
    
    $signup->signUp();
    $dbh->success("../../login.php", "Registration Successful! You can login now.");

    // // if($signup){
        // $otp = rand(100000,999999);
        // $_SESSION['otp'] = $otp;

        // require "../mail/phpmailer/PHPMailerAutoload.php";
        // require "../mail/phpmailer/class.phpmailer.php";
        // require "../mail/phpmailer/class.smtp.php";

        // $mail = new PHPMailer(true);

        // $mail->isSMTP();
        // $mail->Host='smtp.gmail.com';
        // $mail->Port=587;
        // $mail->SMTPAuth=true;
        // $mail->SMTPSecure='tls';
            
        // $mail->Username='fueloninformation@gmail.com';
        // $mail->Password='wkhqldpcwafmjksh';
            
        // $mail->setFrom('fueloninformation@gmail.com', 'Fuel On');
        // $mail->addAddress($email);
            
        // $mail->isHTML(true);
        // $mail->Subject="Your Verification Code";
        // $mail->Body="<p>Dear $fname, </p> <h3>Your Registration OTP verification code is $otp <br></h3>
        //              <br><br>
        //              <p>With regards,</p>
        //              <b>The Fuel ON Team</b>";
            
        // if(!$mail->send()){
        //     $dbh->error("../../register-customer.php", "Registration Failed! Invalid Email.");
        // }else{
        //     $dbh->success("../../registration-otp.php", "The Registration code successfully sent to " . $email.". Please check your inbox");
        // }
}