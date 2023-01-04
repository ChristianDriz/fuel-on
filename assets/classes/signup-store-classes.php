<?php
class SignupStore extends DBHandler{

    protected function registerStore($email, $firstname, $lastname, $station_name, $branch, $address, $phone, $password, $type, $tin_num, $mapLat, $mapLng, $filename, $tmp, $opening, $closing){

        $conn = $this->connect();
        try {
            $stmt = $conn->prepare('INSERT INTO tbl_users (email, firstname, lastname, phone_num, password, user_image, user_type, map_lat, map_lang) VALUES (?, ?, ?, ?, ?, "default-station-img.jpg", ?, ?, ?);');
            $hashedPass = password_hash($password, PASSWORD_DEFAULT);
            $stmt->execute(array($email, $firstname, $lastname, $phone, $hashedPass, $type, $mapLat, $mapLng));
            $this->insertStoreDetails($conn->lastInsertId(), $filename, $tmp, $station_name, $branch, $address, $tin_num, $opening, $closing);
            return  $conn->lastInsertId();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    protected function insertStoreDetails($shopID, $filename, $tmp, $station_name, $branch, $address, $tin_num, $opening, $closing)
    {
        // connect to the database
        $conn = $this->connect();

        // destination of the file on the server
        $destination = '../../uploads/'.$filename;
        
        // move the uploaded (temporary) file to the specified destination
        if (move_uploaded_file($tmp, $destination)) {
            try {
                $stmt = $conn->prepare('INSERT INTO tbl_station (shopID, permit_name, station_name, branch_name, station_address, tin_number, opening, closing) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?);');
                $stmt->execute(array($shopID, $filename, $station_name, $branch, $address, $tin_num, $opening, $closing));
                return  $conn->lastInsertId();
            } catch (\PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        } else {
            echo "Failed to upload file.";
        }
        
    }

    protected function checkUser($email){
        $stmt = $this->connect()->prepare('SELECT email FROM tbl_users WHERE email = ?;');

        if(!$stmt->execute(array($email))){
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

    function success($url, $message)
    {
        $_SESSION['message'] = $message;
        header('Location: ' . $url);
        exit();
    }

    function info($url, $message)
    {
        $_SESSION['info_message'] = $message;
        header('Location: ' . $url);
        exit();
    }

}