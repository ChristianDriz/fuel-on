<?php
session_start();
if (isset($_POST['submit'])) {
    include "../classes/dbHandler.php";
    include "../classes/signup-store-classes.php";
    include "../classes/signup-store-contr.php";

    $dbh = new Config();

    // $_SESSION['email'] = $_POST['email'];
    // $_SESSION['firstname'] = $_POST['firstname'];
    // $_SESSION['lastname'] = $_POST['lastname'];
    // $_SESSION['station_name'] = $_POST['station_name'];
    // $_SESSION['branch'] = $_POST['branch'];
    // $_SESSION['address'] = $_POST['address'];
    // $_SESSION['phone'] = $_POST['phone'];
    // $_SESSION['tin_num'] = $_POST['tin_num'];

    $email = $_POST['email'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $station_name = $_POST['station_name'];
    $branch = $_POST['branch'];
    $address = $_POST['address'];
    $contact_num = $_POST['phone'];
    $tin_num = $_POST['tin_num'];
    $filename = $_FILES['myfile']['name'];
    $filesize = $_FILES['myfile']['size'];
    $tmp_name = $_FILES['myfile']['tmp_name'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    $mapLat = $_POST['mapLat'];
    $mapLng = $_POST['mapLng'];
    $type = $_GET['type'];

    $sched = $_POST['schedule'];

    if($sched == "withclosing"){
        $opening = $_POST['opening'];
        $closing = $_POST['closing'];
    }
    elseif($sched == "24/7"){
        $opening = "00:00:00";
        $closing = "00:00:00";
    }

    $signup = new SignupStoreContr($email, $fname, $lname, $station_name, $branch, $address, $contact_num, $password, $confirm, $type, $tin_num, $mapLat, $mapLng, $filename, $filesize, $tmp_name, $opening, $closing);
    $signup->checkInput();

    $signup->signUp();
    $dbh->success("../../register-store.php", "Registration has been sent to admin for approval, kindly wait for an email.");

}