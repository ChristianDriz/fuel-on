<?php

class ValidContr extends Validate{

    private $email;
    private $password;

    public function __construct($email, $password){
        $this->email = $email;
        $this->password = $password;
    }

    public function validateUser(){
        if($this->emptyInput() == false){
            echo "<script>alert('Empty Input!');document.location='../../customer-account-settings.php'</script>";
            exit();
        }

        $this->valUser($this->email, $this->password);
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

}