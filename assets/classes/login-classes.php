<?php

class Login extends DBHandler{

    public function getUser($email, $password){
        
        $stmt = $this->connect()->prepare('SELECT password FROM tbl_users WHERE email = ?');

        if(!$stmt->execute(array($email))){
            $stmt = null;
            // echo "<script>alert('Connection Failed!');document.location='../../login.php'</script>";
            $this->error("../../login.php", "Connection Failed!");
            exit();
        }

        if($stmt->rowCount() == 0){
            $stmt = null;
            $this->info("../../login.php", "Account does not exist.");
            exit();
        }

        $hashedPass = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $checkPass = password_verify($password, $hashedPass[0]["password"]);

        if($checkPass == false){
            $stmt = null;
            $this->error("../../login.php?email=$email", "Please check your details carefully.");
            exit();
        }
        else if($checkPass == true){
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_users WHERE email = ? AND password = ?;');

            if(!$stmt->execute(array($email, $hashedPass[0]["password"]))){
                $stmt = null;
                $this->error("../../login.php", "Connection Failed!");
                exit();
            }

            if($stmt->rowCount() == 0){
                $stmt = null;
                $this->info("../../login.php", "Account does not exist.");
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

    public function success($url, $message)
    {
        $_SESSION['message'] = $message;
        header('Location: ' . $url);
        exit();
    }

    public function info($url, $message)
    {
        $_SESSION['info_message'] = $message;
        header('Location: ' . $url);
        exit();
    }

    public function error($url, $message)
    {
        $_SESSION['error_message'] = $message;
        header('Location: ' . $url);
        exit();
    }

}