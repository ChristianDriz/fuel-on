<?php

class Signup extends DBHandler{

    protected function setUser($email, $fname, $lname, $phone, $password, $type){
        $stmt = $this->connect()->prepare('INSERT INTO tbl_users (email, firstname, lastname, phone_num, password, user_type) VALUES (?, ?, ?, ?, ?, ?);');

        $hashedPass = password_hash($password, PASSWORD_DEFAULT);

        if(!$stmt->execute(array($email, $fname, $lname, $phone, $hashedPass, $type))){
            $stmt = null;
            echo "<script>alert('Connection Failed!');document.location='../../register-customer.php'</script>";
            exit();
        }

        $stmt = null;
    }

    protected function checkUser($email, $phone){
        $stmt = $this->connect()->prepare('SELECT email FROM tbl_users WHERE email = ? OR phone_num = ?;');

        if(!$stmt->execute(array($email, $phone))){
            $stmt = null;
            echo "<script>alert('Connection Failed!');document.location='../../register-customer.php'</script>";
            exit();
        }

        $resultCheck;

        if($stmt->rowCount() > 0){
            $resultCheck = false;
        }
        else{
            $resultCheck = true;
        }

        return $resultCheck;
    }

}