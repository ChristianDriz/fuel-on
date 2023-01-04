<?php
session_start();

    include '../db.conn.php';
    require_once("../classes/dbHandler.php");
    $dbh = new Config();
    
    //if user click continue button in forgot password form
    if(isset($_POST['email'])){
        $email = $_POST['email'];

        $user = $dbh->getVerified($email);
        foreach($user as $users){
            $userID = $users['userID'];
        };

        $sql = 'SELECT email FROM tbl_users WHERE email = ?';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);

        if($stmt->rowCount() > 0){
            
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
            $mail->Subject="Password Reset Code";
            $mail->Body="<p>Dear user, </p> <h3>Your Password Reset Code is $otp <br></h3>
                        <br><br>
                        <p>With regards,</p>
                        <b>The Fuel ON Team</b>";
                
            if(!$mail->send()){
                $dbh->error("../../forgot-pass.php", "Error! Invalid Email");
            }else{
                $_SESSION["userID"] = $users["userID"];
                $_SESSION["email"] = $users["email"];
                $dbh->updateOTP($otp, $userID);
                $dbh->success("../../reset-code.php", "We have sent a password reset otp to your email " . $email.". Please check your inbox.");
            }
        }
        else{
            $dbh->info("../../forgot-pass.php", "Account does not exist.");
        }
    }

    //to check if the code is equal to the sent code
    if(isset($_POST['submit-code'])){
        $code = $_POST['code'];

        $email = $_SESSION['email'];
        $userID = $_SESSION['userID'];

        $user = $dbh->getVerified($email);

        foreach($user as $users){
            $otp_code = $users['otp_code'];
        };

        if($otp_code == $code){
            $dbh->verified("../../new-pass.php", "Verification Success.");
        }
        else{
            $dbh->error("../../reset-code.php", "Incorrect code. Please re-enter");
        }
    }

    //if user click change password button
    if(isset($_POST['change-password'])){
        $newpass = $_POST['password'];
        $confirmnewpass = $_POST['confirm-password'];

        $email = $_SESSION['email'];
        $userID = $_SESSION['userID'];

        if(strlen($newpass) < 8){
            $dbh->info("../../new-pass.php", "Password must be 8 characters in length.");
        }
        elseif($newpass == $confirmnewpass){

            $hashedPass = password_hash($newpass, PASSWORD_DEFAULT);

            $update_pass = "UPDATE tbl_users SET password = '$hashedPass' WHERE email = ?";
            $stmt = $conn->prepare($update_pass);
            $stmt->execute([$email]);

            if($stmt){
            ?>
                <script>document.location="../../password-changed.php"</script>
            <?php
            }else{
                $dbh->error("../../new-pass.php", "Failed to change your password!");
            }

        }else{
            $dbh->error("../../new-pass.php", "Password does not match. Please check carefully.");
        }
    }
    



