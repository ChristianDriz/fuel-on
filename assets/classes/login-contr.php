<?php

class LoginContr extends Login{

    private $email;
    private $password;

    public function __construct($email, $password){
        $this->email = $email;
        $this->password = $password;
    }

    public function loginUser(){
        if($this->emptyInput() == false){
            // echo "<script>alert('Empty Input!');document.location='../../login.php'</script>";
            $this->info("../../login.php", "Please input your details.");
            exit();
        }
        $this->getUser($this->email, $this->password);
    }

    private function emptyInput(){
        $result;
        if(empty($this->email) || empty($this->password)){
            $result = false;
        }
        else{
            $result = true;
        }
        return $result;
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

}