<?php
// session_start();

class SignupContr extends Signup{

    private $email;
    private $fname;
    private $lname;
    private $phone;
    private $password;
    private $confirm;
    private $type;
    private $status;
    private $otp;
    private $verified;

    public function __construct($email, $fname, $lname, $phone, $password, $confirm, $type){
        $this->email = $email;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->phone = $phone;
        $this->password = $password;
        $this->confirm = $confirm;
        $this->type = $type;
        // $this->status = $status;
        //$this->otp = $otp;
        // $this->verified = $verified;
    }

    public function signUp(){
        $this->setUser($this->email, $this->fname, $this->lname, $this->phone, $this->password, $this->type);
    }

    public function checkInput(){

        $email = $_POST['email'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $phone = $_POST['phone'];

        $data = 'email=' .$email. '&fname=' .$fname. '&lname=' .$lname. '&phone=' .$phone;

        if($this->emptyInput() == false){
            // echo "<script>alert('Empty Input!');document.location='../../register-customer.php'</script>";
            $this->info("../../register-customer.php?$data", "All fields must be filled out.");
            exit();
        }

        if($this->invalidEmail() == false){
            // echo "<script>alert('Invalid Email!');document.location='../../register-customer.php'</script>";
            $this->info("../../register-customer.php?$data", "Invalid email format.");
            exit();
        }

        if($this->invalidName() == false){
            // echo "<script>alert('Invalid Name Input!');document.location='../../register-customer.php'</script>";
            $this->info("../../register-customer.php?$data", "Invalid name format.");
            exit();
        }

        if($this->invalidPhone() == false){
            // echo "<script>alert('Invalid Name Input!');document.location='../../register-customer.php'</script>";
            $this->info("../../register-customer.php?$data", "Invalid phone number format.");
            exit();
        }

        if($this->passwordMatch() == false){
            // echo "<script>alert('Passwords don't match!');document.location='../../register-customer.php'</script>";
            $this->info("../../register-customer.php?$data", "Password does not match.");
            exit();
        }

        if($this->passwordLength() == false){
            // echo "<script>alert('Passwords don't match!');document.location='../../register-customer.php'</script>";
            $this->info("../../register-customer.php?$data", "Password must be 8 characters in length.");
            exit();
        }

        if($this->userTaken() == false){
            // echo "<script>alert('Account already taken! Use another email and phone number.');document.location='../../register-customer.php'</script>";
            $this->info("../../register-customer.php?$data", "Account already taken. Please use another email or phone number.");
            exit();
        }
    }

    private function emptyInput(){
        $result;
        if(empty($this->email) || empty($this->fname) || empty($this->lname) || empty($this->phone) || empty($this->password) || empty($this->confirm)){
            $result = false;
        }
        else{
            $result = true;
        }
        return $result;
    }

    private function invalidName(){
        $result;
        if(!preg_match("/^[a-zA-Z0-9_ -]*$/", $this->fname) || !preg_match("/^[a-zA-Z0-9_ -]*$/", $this->lname)){
            $result = false;
        }
        else{
            $result = true;
        }
        return $result;
    }

    private function invalidEmail(){
        $result;
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            $result = false;
        }
        else{
            $result = true;
        }
        return $result;
    }

    private function invalidPhone(){
        $result;
        if(!preg_match("/^(09)[0-9]{0,9}$/", $this->phone)){
            $result = false;
        }
        else{
            $result = true;
        }
        return $result;
    }

    private function passwordMatch(){
        $result;
        if($this->password !== $this->confirm){
            $result = false;
        }
        else{
            $result = true;
        }
        return $result;
    }

    private function passwordLength(){
        $result;
        if(strlen($this->password) < 8){
            $result = false;
        }
        else{
            $result = true;
        }
        return $result;
    }

    private function userTaken(){
        $result;
        if(!$this->checkUser($this->email)){
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