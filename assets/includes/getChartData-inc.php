<?php

session_start();

require_once('../classes/dbHandler.php');
$dbh = new Config();


$results = $dbh->getchartData($_POST['shopId'], $_POST['status']);

// $month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

$data = [];

foreach ($results as $result) {
    // $month[] = $result['Month'];
    // $amount[] = $result['amount'];

    $data[] = [
        'month' => $result['Month'],
        'amount' => $result['amount'],
    ];
}

echo json_encode($data);



// if (isset($_POST['shopId'])) {
    // echo $dbh->getchartData(5, "completed");
// }


// if (isset($_POST['shopId'])) {
//     echo $dbh->getchartData($_POST['shopId'], $_POST['status']);
// }