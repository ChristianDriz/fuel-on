<?php
session_start();
if(isset($_SESSION['userID'])){
    $userID = $_SESSION["userID"];
    $username = $_SESSION["fname"];
    $userType = $_SESSION["userType"];
    $userpic = $_SESSION["userPic"];

}
else{
    header('location: index.php');
}

require_once("assets/classes/dbHandler.php");
require_once('vendor/autoload.php');

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options;
$options->setChroot(__DIR__);
$options->setIsRemoteEnabled(true);

$data = new Config();

    // $orderID = "ORD-1090463931";
    // $shopID = 5;
    // $customerID = 10;

    $orderID = $_GET['orderId'];
    $shopID = $_GET['shopID'];
    $customerID = $_GET['customerID'];

    $shop = $data->oneShop($shopID);
    $shopdetails = $shop[0];

    $customer = $data->oneCustomer($customerID);
    $customerdetails = $customer[0];

    $records = $data->getShopBillingReceipt($orderID);
    $customerName = $records[0]['customerName'];
    $grandTotal = 0;



$html = '<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON</title>
    <style>
    body {
        font-family: sans-serif;
    }

    .store-deets{
        margin-bottom: 30px;
    }

    .store-deets p{
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
        border-bottom: 2px solid #3e3d3d;
        text-align: left;
        padding: 0 0 10px 0;
    }

    .data-table tbody td {
        padding: 8px 2px;
    }

    .total{
        margin-top: 30px;
    }

    .total th{
        text-align: left;
        padding-top: 50px;
    }
    </style>
</head>

<body>';

        $date = $records[0]['transac_date']; 
        $createdate = date_create($date);
        $new_date = date_format($createdate, "F d, Y h:i a");


        $html .= '
        <div class="store-deets">
            <h3 style="margin: 0;">' .$shopdetails['firstname'].'</h3>
            <p>'. $shopdetails['lastname'] .'</p>
            <p>TIN: '. $shopdetails['tin_num'].'</p>
            <p>'.$shopdetails['email'].'</p>
            <p>'. $shopdetails['phone_num'].'</p>
        </div>
        <table class="details">
            <thead>
                <tr>
                    <th class="col-6">Order ID</th>
                    <th class="col-6">Customer Details</th>
                </tr>
                <tr>
                    <td>'.$records[0]['orderID'] .'</th>
                    <td>'. $customerdetails['firstname'].' '.$customerdetails['lastname'].'</th>
                </tr>
                <tr>
                    <th>Order Status</th>
                    <td>'. $customerdetails['phone_num'].'</th>
                </tr>
                <tr>
                    <td>'. $records[0]['order_status'] .'</th>
                </tr>
                <tr>
                    <th>Order Date</th>
                </tr>
                <tr>
                    <td>'.$new_date.'</th>
                </tr>
            </thead>
        </table>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 60%;">Description</th>
                    <th colspan="1">Unit Cost</th>
                    <th colspan="1">Qty</th>
                    <th colspan="1">Amount</th>
                </tr>
            </thead>
            <tbody>';
    
                    foreach ($records as $record) {
                        $grandTotal += $record['total'];
                
                $html .= '
                <tr class="item-descript">
                    <td>' .$record['product_name']. '</td>
                    <td>P' . number_format($record['price'], 2). '</td>
                    <td>' .$record['quantity']. '</td>
                    <td>P' . number_format($record['total'], 2). '</td>
                </tr>';
                    }
                    
                $html .= '
                <tr class="total">
                    <th colspan="3">Note</th>
                    <th>Total</th>
                </tr>
                <tr class="total">
                    <td colspan="3">Please pay to the cashier of the station</th>
                    <td style="font-weight: bold; font-size: 20px;">P'. number_format($grandTotal, 2) .'</th>
                </tr>
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
$dompdf = new Dompdf;
$dompdf->loadHtml($html);
$dompdf->setPaper("A4", "portrait");
$dompdf->render();

$dompdf->stream($orderID. ".pdf", ["Attachment" => 0]);

$output = $dompdf->output();
// file_put_contents("file.pdf", $output);