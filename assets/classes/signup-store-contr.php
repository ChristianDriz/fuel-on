<?php
// session_start();

class SignupStoreContr extends SignupStore{

    private $email;
    private $firstname;
    private $lastname;
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



    public function __construct($email, $firstname, $lastname, $station_name, $branch, $address, $phone, $password, $confirm, $type, $tin_num, $mapLat, $mapLng, $filename, $filesize, $tmp, $opening, $closing){
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
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
        $this->registerStore($this->email, $this->firstname, $this->lastname, $this->station_name, $this->branch, $this->address, $this->phone, $this->password, $this->type, $this->tin_num, $this->mapLat, $this->mapLng, $this->filename, $this->tmp, $this->opening, $this->closing);
    }

    public function checkInput(){

        $email = $_SESSION['email'];
        $firstname = $_SESSION['firstname'];
        $lastname = $_SESSION['lastname'];
        $station_name = $_SESSION['station_name'];
        $branch = $_SESSION['branch'];
        $address = $_SESSION['address'];
        $phone = $_SESSION['phone'];
        $tin_num = $_SESSION['tin_num'];

        if($this->emptyInput() == false){
            $this->info("../../register-store.php?email=$email&firstname=$firstname&lastname=$lastname&station_name=$station_name&branch=$branch&address=$address&phone=$phone&tin=$tin_num", "All fields must be filled out.");
            exit();
        }

        if($this->invalidEmail() == false){
            $this->info("../../register-store.php?email=$email&firstname=$firstname&lastname=$lastname&station_name=$station_name&branch=$branch&address=$address&phone=$phone&tin=$tin_num", "Invalid email format.");
            exit();
        }

        if($this->invalidFName() == false){
            $this->info("../../register-store.php?email=$email&firstname=$firstname&lastname=$lastname&station_name=$station_name&branch=$branch&address=$address&phone=$phone&tin=$tin_num", "Invalid first name format.");
            exit();
        }

        if($this->invalidLName() == false){
            $this->info("../../register-store.php?email=$email&firstname=$firstname&lastname=$lastname&station_name=$station_name&branch=$branch&address=$address&phone=$phone&tin=$tin_num", "Invalid last name format.");
            exit();
        }

        // if($this->invalidPhone() == false){
        //     $this->info("../../register-store.php?email=$email&name=$name&branch=$branch&address=$address&phone=$phone&tin=$tin_num", "Invalid phone number format.");
        //     exit();
        // }

        if($this->passwordMatch() == false){
            $this->info("../../register-store.php?email=$email&firstname=$firstname&lastname=$lastname&station_name=$station_name&branch=$branch&address=$address&phone=$phone&tin=$tin_num", "Password does not match.");
            exit();
        }

        if($this->userTaken() == false){
            $this->info("../../register-store.php?email=$email&firstname=$firstname&lastname=$lastname&station_name=$station_name&branch=$branch&address=$address&phone=$phone&tin=$tin_num", "Account already taken. Please use another email.");
            exit();
        }

        if($this->invalidTIN() == false){
            $this->info("../../register-store.php?email=$email&firstname=$firstname&lastname=$lastname&station_name=$station_name&branch=$branch&address=$address&phone=$phone&tin=$tin_num", "Invalid TIN format");
            exit();
        }

        // get the file extension
        $extension = pathinfo($this->filename, PATHINFO_EXTENSION);

        if (!in_array($extension, ['pdf', 'png', 'jpeg', 'jpg'])) {
            $this->info("../../register-store.php?email=$email&firstname=$firstname&lastname=$lastname&station_name=$station_name&branch=$branch&address=$address&phone=$phone&tin=$tin_num", "File type must be pdf, png, jpg, or jpeg");
            die();
        } elseif ($this->filesize > 2000000) { // file shouldn't be larger than 2Megabyte
            $this->info("../../register-store.php?email=$email&firstname=$firstname&lastname=$lastname&station_name=$station_name&branch=$branch&address=$address&phone=$phone&tin=$tin_num", "File size too large");
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
        || empty($this->firstname)
        || empty($this->lastname)
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


    private function invalidFName(){
        $result;
        if(!preg_match("/^[a-zA-Z0-9_ -]*$/", $this->firstname)){
            $result = false;
        }
        else{
            $result = true;
        }
        return $result;
    }

    private function invalidLName(){
        $result;
        if(!preg_match("/^[a-zA-Z0-9_ -]*$/", $this->lastname)){
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

    // private function invalidPhone(){
    //     $result;
    //     if(!preg_match("/^(09)[0-9]{0,9}$/", $this->phone)){
    //         $result = false;
    //     }
    //     else{
    //         $result = true;
    //     }
    //     return $result;
    // }

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