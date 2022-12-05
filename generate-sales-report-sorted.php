<?php
session_start();
if(isset($_SESSION['userID'])){
    $userID = $_SESSION["userID"];
    $username = $_SESSION["fname"];
    $userType = $_SESSION["userType"];
    $userpic = $_SESSION["userPic"];

    if($userType == 1)
    { 
        header('location: index.php');
    }
    elseif($userType == 0)
    { 
        header('location: index.php');
    }
}
else{
    header('location: index.php');
}

if(isset($_GET['from_date']) && isset($_GET['to_date'])){
    $from_date = $_GET['from_date'];
    $to_date = $_GET['to_date'];
}

require_once("assets/classes/dbHandler.php");
require_once('vendor/autoload.php');

$data = new Config();

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options;
$options->setChroot(__DIR__);
$options->setIsRemoteEnabled(true);

$shop = $data->oneShop($userID);
$shopdetails = $shop[0];

$fromdate = $from_date;
$createfromdate = date_create($fromdate);
$new_fromdate = date_format($createfromdate, "M d, Y");

$todate = $to_date;
$createtodate = date_create($todate);
$new_todate = date_format($createtodate, "M d, Y");


$html = '<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <style>
    body {
        font-family: Arial, Helvetica, sans-serif ;
    }

    .title{
        text-align: center; 
        margin-bottom: 30px;
        font-weight: bold;
        font-size: 22px;
    }

    .logo {
        text-align: right;
    }

    .logo img{
        width: 110px;
        height: 100px;
        border: 2px solid #fbf7f7;
        object-fit: cover;
    }

    .name{
        font-size: 19px;
        font-weight: bold;
    }

    .store-deets{
        margin-top: -100px;
        margin-bottom: 30px;
    }

    .store-deets p{
        margin: 0;
    }
        
    .sale-date p{
        margin: 0;
    }
    
    .details{
        width: 100%;
        
    }

    .details thead th {
        text-align: left;
    }

    .data-table{
        width: 100%;
        margin-top: 50px;
        border-collapse: collapse;
    }

    .data-table thead th {
        text-align: left;
        padding: 10px 5px;
        background-color: bisque;
        border: 1px solid black;
        border-bottom: 2px solid #3e3d3d;
    }

    .data-table tbody .td {
        padding: 8px 5px;
        border: 1px solid black;
    }

    .total{
        margin-top: 30px;
    }

    .total th{
        text-align: left;
        padding-top: 50px;
    }

    .summary{
        width: 100%;
        margin-top: 50px;
    }

    .summary th{
        text-align: left;
    }
    </style>
</head>

<body>
        <p class="title">Sales Report</p>
        <div class="logo">
            <img src="assets/img/profiles/'.$shopdetails['user_image'].'"/>
        </div>
        <div class="store-deets">
            <p class="name">' .$shopdetails['firstname'].'</p>
            <p>'. $shopdetails['lastname'] .'</p>
            <p>TIN: '. $shopdetails['tin_num'].'</p>
            <p>'.$shopdetails['email'].'</p>
            <p>'. $shopdetails['phone_num'].'</p>
        </div>
        <div class="sale-date">
            <p>Date</p>
            <p>From: '. $new_fromdate.'</p>
            <p>To: '. $new_todate.'</p>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Product Name</th>
                    <th style="width: 15%;">Date</th>
                    <th style="width: 12%;">Unit Cost</th>
                    <th style="width: 8%;">Qty</th>
                    <th style="width: 15%;">Amount</th>
                </tr>
            </thead>
            <tbody>';

                $status = 'Completed';
                $data = $data->salesReportSorted($from_date, $to_date, $userID);
            
                    $grandtotal = 0;
                    foreach ($data as $row) {
                        $grandtotal += $row['total'];

                        $date = $row['transac_date'];
                        $createdate = date_create($date);
                        $new_date = date_format($createdate, "M d, Y");
                
                $html .= '
                <tr class="item-descript">
                    <td class="td">' .$row['product_name']. '</td>
                    <td class="td">' .$new_date. '</td>
                    <td class="td">P' . number_format($row['price']). '</td>
                    <td class="td">' .$row['quantity']. '</td>
                    <td class="td">P' . number_format($row['total']). '</td>
                </tr>';
                    }
                    
                $html .= '
            </tbody>
        </table>
        <table class="summary">
            <thead>
                <th style="width: 85%;"></th>
                <th style="width: 15%;">Total</th>
            </thead>
            <tbody>
                <td></th>
                <td style="font-size: 20px; font-weight: bold;">P'.number_format($grandtotal).'</th>
            </tbody>
        </table>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/editproduct.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
</body>

</html>';
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper("A4", "portrait");
$dompdf->render();

$dompdf->stream(".pdf", ["Attachment" => 0]);

$output = $dompdf->output();
// file_put_contents("file.pdf", $output);