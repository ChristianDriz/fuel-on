<?php
// Downloads files
if (isset($_GET['type'])) {
    require_once('../classes/dbHandler.php');
    $dbh = new Config();

    $storeId = $_GET['storeId'];
    $type = $_GET['type'];
    $reason = $_GET['reason'];

    $updateStore = $dbh->updateStoreStatus($storeId, $type);

    // Send mail if db query is successful
    if ($updateStore) {
        $storeData = $dbh->getStoreData($storeId);

        require "../mail/phpmailer/PHPMailerAutoload.php";
        require "../mail/phpmailer/class.phpmailer.php";
        require "../mail/phpmailer/class.smtp.php";

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';

        $mail->Username = 'fueloninformation@gmail.com';
        $mail->Password = 'wkhqldpcwafmjksh';

        $mail->setFrom('fueloninformation@gmail.com', 'Fuel On');
        $mail->addAddress($storeData['email']);

        $mail->isHTML(true);
        $mail->Subject = $type == 1 ? "Your account has been approved" : "Your account has been declined";

        if ($type == 1) {
            $mail->Body = "<p>Dear user, </p> <h3>Your account has been approved <br></h3>
            <br>
            You can now login using your credentials.
            <br><br>
            <p>With regards,</p>
            <b>The Fuel ON Team</b>";
        } else {
            $mail->Body = "<p>Dear user, </p> <h3>We're sorry to inform you that your account has been declined <br></h3>
            <br>
            <h4>For the reason that '$reason' </h4>
            <br><br>
            If you have any inquiries please send us an email.
            <br><br>
            <p>With regards,</p>
            <b>The Fuel ON Team</b>";

            //adding the declined reason to database
            $dbh->StationDeclinedReason($reason, $storeId);
        }

        $mail->send();
    }
    echo $updateStore;
}
