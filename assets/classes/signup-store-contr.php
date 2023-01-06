<?php
// session_start();

class SignupStoreContr extends SignupStore{

    private $email;
    private $fname;
    private $lname;
    private $station_name;
    private $branch;
    private $address;
    private $phone;
    private $tin_num;
    private $opening;
    private $closing;    
    private $password;
    private $confirm;
    private $type;
    private $mapLat;
    private $mapLng;
    private $filename;
    private $filesize;
    private $tmp;


    public function __construct($email, $fname, $lname, $station_name, $branch, $address, $phone, $password, $confirm, $type, $tin_num, $mapLat, $mapLng, $filename, $filesize, $tmp, $opening, $closing){
        $this->email = $email;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->station_name = $station_name;
        $this->branch = $branch;
        $this->address = $address;
        $this->phone = $phone;
        $this->tin_num = $tin_num;
        $this->opening = $opening;
        $this->closing = $closing;
        $this->password = $password;
        $this->confirm = $confirm;
        $this->type = $type;
        $this->mapLat = $mapLat;
        $this->mapLng = $mapLng;
        $this->filename = $filename;
        $this->filesize = $filesize;
        $this->tmp = $tmp;
    }
    

    public function signUp(){
        $this->registerStore($this->email, $this->fname, $this->lname, $this->station_name, $this->branch, $this->address, $this->phone, $this->password, $this->type, $this->tin_num, $this->mapLat, $this->mapLng, $this->filename, $this->tmp, $this->opening, $this->closing);
    }

    public function checkInput(){

        $email = $_POST['email'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $station_name = $_POST['station_name'];
        $branch = $_POST['branch'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $tin_num = $_POST['tin_num'];

        $data = 'email='.$email.'&fname='.$fname.'&lname='.$lname.'&station_name='.$station_name.'&branch='.$branch.'&address='.$address.'&phone='.$phone.'&tin_num='.$tin_num;

        if($this->emptyInput() == false){
            $this->info("../../register-store.php?$data", "All fields must be filled out.");
            exit();
        }

        if($this->invalidEmail() == false){
            $this->info("../../register-store.php?$data", "Invalid email format.");
            exit();
        }

        if($this->invalidName() == false){
            $this->info("../../register-store.php?$data", "Invalid name format.");
            exit();
        }

        if($this->invalidPhone() == false){
            $this->info("../../register-store.php?$data", "Invalid phone number format must be starts with 09.");
            exit();
        }

        if($this->passwordMatch() == false){
            $this->info("../../register-store.php?$data", "Password does not match.");
            exit();
        }

        if($this->passwordLength() == false){
            $this->info("../../register-store.php?$data", "Password must be 8 characters in length.");
            exit();
        }

        if($this->userTaken() == false){
            $this->info("../../register-store.php?$data", "Account already taken. Please use another email address.");
            exit();
        }

        if($this->invalidTIN() == false){
            $this->info("../../register-store.php?$data", "Invalid TIN format");
            exit();
        }

        // get the file extension
        $extension = pathinfo($this->filename, PATHINFO_EXTENSION);

        if (!in_array($extension, ['pdf', 'png', 'jpeg', 'jpg'])) {
            $this->info("../../register-store.php?$data", "File type must be pdf, png, jpg, or jpeg");
            die();
        } elseif ($this->filesize > 2000000) { // file shouldn't be larger than 2Megabyte
            $this->info("../../register-store.php?$data", "File size too large");
            die();
        } else {
            if (file_exists($this->filename)) {
                chmod($this->filename, 0755); //Change the file permissions if allowed
                unlink($this->filename); //remove the file
            }
        }
    }

    private function emptyInput(){
        $result;
        if(empty($this->email)
        || empty($this->fname)
        || empty($this->lname)
        || empty($this->station_name)
        || empty($this->branch)
        || empty($this->address)
        || empty($this->phone)
        || empty($this->password)
        || empty($this->confirm)
        || empty($this->tin_num)
        || empty($this->opening)
        || empty($this->closing)
        || empty($this->filename)
        || empty($this->mapLat) && empty($this->mapLng)){
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
        if(!preg_match("/^(09)[0-9]\d{8}$/", $this->phone)){
            $result = false;
        }
        else{
            $result = true;
        }
        return $result;
    }

    private function invalidTIN(){
        $result;
        if(preg_match("/^\d{3}-\d{3}-\d{3}$/", $this->tin_num)){
            $result = true;
        }
        elseif(preg_match("/^\d{3}-\d{3}-\d{3}-\d{3}$/", $this->tin_num)){
            $result = true;
        }
        else{
            $result = false;
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