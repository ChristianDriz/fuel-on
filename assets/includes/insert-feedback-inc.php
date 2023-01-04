<?php

session_start();

require_once('../classes/dbHandler.php');
$dbh = new Config();

if(isset($_SESSION['userID'])){
    $userID = $_SESSION['userID'];
    
}
    $shopID = $_GET['shopID'];

    date_default_timezone_set('Asia/Manila');

    $date = date("Y-m-d H:i:s");

    if(isset($_POST['submit1'])){
        $feedback = $_POST['feedback'];
        $rating = $_POST['rate'];

        if (empty($rating)){
            $dbh->info("../../customer-viewstore-timeline.php?stationID=$shopID", "You need to set star ratings.");
        }
        else{
            $dbh->insertRating($userID, $shopID, $rating, $feedback, $date);
            $dbh->success("../../customer-view-feedback.php?stationID=$shopID", "Your feedback has been successfully submitted.");
        }
    }
