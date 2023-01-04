<?php
session_start();
 if(isset($_POST['submit'])){
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['password'] = $_POST['password'];

    $email = $_POST['email'];
    $password = $_POST['password'];
    
    require_once("../classes/dbHandler.php");
    $dbh = new Config();

    $user = $dbh->getVerified($email);
    $users = $user[0];

    $userID = $users['userID'];
    $userType = $users['user_type'];
    $ver = $users['verified'];

    if($userType == 1)
    {
        if($ver == 1){
            header('location: login-inc.php');
        }
        elseif($ver == 0){
        
            $_SESSION["userID"] = $users["userID"];
            $_SESSION["email"] = $users["email"];
            $_SESSION["fname"] = $users["firstname"];
            $_SESSION["userType"] = $users["user_type"];
            $_SESSION["userPic"] = $users["user_image"];

            $otp = rand(100000,999999);

            require "../mail/phpmailer/PHPMailerAutoload.php";
            require "../mail/phpmailer/class.phpmailer.php";
            require "../mail/phpmailer/class.smtp.php";

            $mail = new PHPMailer(true);
                
            $mail->isSMTP();
            $mail->Host='smtp.gmail.com';
            $mail->Port=587;
            $mail->SMTPAuth=true;
            $mail->SMTPSecure='tls';
                
            $mail->Username='fueloninformation@gmail.com';
            $mail->Password='wkhqldpcwafmjksh';
                
            $mail->setFrom('fueloninformation@gmail.com', 'Fuel On');
            $mail->addAddress($email);
                
            $mail->isHTML(true);
            $mail->Subject="Your Verification Code";
            $mail->Body="<p>Dear user, </p> <h3>Your Email Verification code is $otp <br></h3>
                        <br><br>
                        <p>With regards,</p>
                        <b>The Fuel ON Team</b>";
                
            if(!$mail->send()){
                ?>
                    <script>
                    alert("<?php echo "Register Failed! Invalid Email."?>");
                    </script>
                <?php
            }else{
                $_SESSION['otp'] = $otp;

                $dbh->updateOTP($otp, $userID);
                $dbh->success("../../login-otp.php", "The verification code has been successfully sent to " . $email.". Please check your inbox.");
            }
        }
    }
    else if ($userType == 2)
    {
        if($ver == 1){
            header('location: login-inc.php');
        }
        else if ($ver == 0){
            $dbh->info("../../login.php", "Your account has not been verified by the Admin. Kindly wait for an email.");
        }
        else if ($ver == 2){
            $dbh->info("../../login.php", "Your account has been declined by the admin. If you have any inquiries please send us an email at fueloninformation@gmail.com");
        }
    }
    else{
        header('location: login-inc.php');
    }
}