<?php

session_start();

require_once('../classes/dbHandler.php');
$dbh = new Config();

if(isset($_SESSION['userID'])){
    $userID = $_SESSION['userID'];
    
}

    date_default_timezone_set('Asia/Manila');

    $date = date("Y-m-d H:i:s");

    if(isset($_POST['submit2'])){
        $rating = $_POST['rate'];
        $feedback = $_POST['feedback'];
        $shopID = $_GET['shopID'];
        $dbh->updateRating($rating, $feedback, $date, $userID, $shopID);
        
        $dbh->success("../../customer-view-feedback.php?stationID=$shopID", "Your feedback has been successfully updated.");
        // echo "<script>alert('Feedback updated successfully!');document.location='../../customer-view-feedback.php?stationID=$shopID'</script>";
    }

    // header('location:../../view-store.php?stationID='.$shopID);