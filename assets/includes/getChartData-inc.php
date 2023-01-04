<?php

session_start();

require_once('../classes/dbHandler.php');
$dbh = new Config();


$results = $dbh->getchartData($_POST['shopId'], $_POST['status']);

// $data = [];

// foreach ($results as $result) {
//     // $month[] = $result['Month'];
//     // $amount[] = $result['amount'];

//     $data[] = [
//         'month' => $result['Month'],
//         'amount' => $result['amount'],
//     ];
// }

// echo json_encode($data);


// $results = $dbh->getchartData(5, "completed");

$data = [];

$rData = [];
foreach ($results as $result) {
    $data['categories'][] = $result['Month'];
    $rData[] = [$result['Month'], (int)$result['amount']];
}
$data['series'][] = ['name' => '', 'data' => $rData];

echo json_encode($data);