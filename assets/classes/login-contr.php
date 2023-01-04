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
            $this->info("../../login.php?email=$this->email", "Please complete the input fields");
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