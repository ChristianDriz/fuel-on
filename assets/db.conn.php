<?php

//CHANGE THIS BEFORE UPLOADING IN HOSTING

// # server name
// $sName = "localhost";
// # user name
// $uName = "u887826340_db_fuelon";
// # password
// $pass = "Schuzoo.1227";

// # database name
// $db_name = "u887826340_db_fuelon";

# server name
$sName = "localhost";
# user name
$uName = "root";
# password
$pass = "";

# database name
$db_name = "db_fuelon";

#creating database connection
try {
    $conn = new PDO("mysql:host=$sName;dbname=$db_name", 
                    $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
  echo "Connection failed : ". $e->getMessage();
}