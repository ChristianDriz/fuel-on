<?php

class Validate extends DBHandler{

    public function valUser($email, $password){
        $stmt = $this->connect()->prepare('SELECT password FROM tbl_users WHERE email = ? OR phone_num = ?;');


        if(!$stmt->execute(array($email, $password))){
            $stmt = null;
            echo "<script>alert('Connection Failed!');document.location='../../customer-account-settings.php'</script>";
            exit();
        }

        if($stmt->rowCount() == 0){
            $stmt = null;
            echo "<script>alert('User Not Found!');document.location='../../customer-account-settings.php'</script>";
            exit();
        }

        $hashedPass = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $checkPass = password_verify($password, $hashedPass[0]["password"]);

        if($checkPass == false){
            $stmt = null;
            echo "<script>alert('Wrong Password Input!');document.location='../../customer-account-settings.php'</script>";
            exit();
        }
        else if($checkPass == true){
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_users WHERE email = ? OR phone_num = ? AND password = ?;');

            if(!$stmt->execute(array($email, $email, $password))){
                $stmt = null;
                echo "<script>alert('Connection Failed!');document.location='../../customer-account-settings.php'</script>";
                exit();
            }

            if($stmt->rowCount() == 0){
                $stmt = null;
                echo "<script>alert('User Not Found!');document.location='../../customer-account-settings.php'</script>";
                exit();
            }

            $user = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $_SESSION["userID"] = $user[0]["userID"];
            $_SESSION["fname"] = $user[0]["firstname"];
            $_SESSION["userType"] = $user[0]["user_type"];
            $_SESSION["userPic"] = $user[0]["user_image"];
        }

        $stmt = null;
    }



}