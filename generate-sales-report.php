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

    require_once("assets/classes/dbHandler.php");
    $data = new Config();

    if(isset($_GET['from_date']) && isset($_GET['to_date'])){
        $fromdate = $_GET['from_date'];
        $todate = $_GET['to_date'];

        $status = 'Completed';
        $orders = $data->salesReportSorted($fromdate, $todate, $userID);

        $shop = $data->shopDetails($userID);
        $shopdetails = $shop[0];
    }
    else{
        $date = $data->salesreportdate($userID);
        $salesdate = $date[0];
    
        $fromdate = $salesdate['From_date'];
        $todate = $salesdate['To_date'];

        $status = 'Completed';
        $orders = $data->shopOrdersCompleted($userID, $status);

        $shop = $data->shopDetails($userID);
        $shopdetails = $shop[0];
    }

    // $fromdate = $salesdate['From_date'];
    $createfromdate = date_create($fromdate);
    $new_fromdate = date_format($createfromdate, "M d, Y");

    // $todate = $salesdate['To_date'];
    $createtodate = date_create($todate);
    $new_todate = date_format($createtodate, "M d, Y");



    require __DIR__ . "/vendor/autoload.php";

    use Dompdf\Dompdf;
    use Dompdf\Options;

    $options = new Options;
    $options->setChroot(__DIR__);
    $options->setIsRemoteEnabled(true);
    $options->setIsPhpEnabled(true);
    $options->setisHtml5ParserEnabled(true);


$html = '
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900&amp;display=swap">
    <link rel="stylesheet" href="assets/css/generate-sales-report.css">
</head>

<body>
    <div class="kontainer">
        <div class="head">
            <div class="logo">
                <img src="assets/img/profiles/'.$shopdetails['user_image'].'"/>
            </div>
            <div class="shop-details">
                <p class="address">'.$shopdetails['station_name'].', '. $shopdetails['branch_name'].'</p>
                <p class="tin">TIN: '.$shopdetails['tin_number'].'</p>
                <p class="email">'.$shopdetails['email'].'</p>
                <p class="phone">'.$shopdetails['phone_num'].'</p>
            </div>
            <div class="invoice">
                <p class="invoice-title">SALES REPORT</p>
            </div>
        </div>
        <div class="line"></div>
        <div class="inside">
            <div>
                <table class="table-head">
                    <thead>
                        <tr>
                            <th class="order-details">Date</th>            
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="order-id">From: '. $new_fromdate.'</td> 
                        </tr>
                        <tr>
                            <td class="status">To: '.  $new_todate.'</td>                      
                        </tr>
                    </tbody>
                </table>
            </div>
            <div>
                <table class="table prod-table">
                    <thead>
                        <tr>
                            <th class="prod-det">Product Details</th>
                            <th class="date-cell">Date</th>
                            <th class="cost">Unit Cost</th>
                            <th class="qty">Qty</th>
                            <th class="amount">Amount</th>
                        </tr>
                    </thead>
                    <tbody>';

                        // $status = 'Completed';
                        // $orders = $data->shopOrdersCompleted($userID, $status);

                        $grandtotal = 0;
                        foreach ($orders as $row) {
                            $grandtotal += $row['total'];
    
                            $date = $row['transac_date'];
                            $createdate = date_create($date);
                            $new_date = date_format($createdate, "M d, Y");

                    $html .='
                        <tr>
                            <td class="prod-det">' .$row['product_name']. '</td>
                            <td class="date-cell">' .$new_date. '</td>
                            <td class="cost">P' . number_format($row['price'], 2). '</td>
                            <td class="qty">'.$row['quantity'].'</td>
                            <td class="amount">P'.number_format($row['total'], 2). '</td>
                        </tr>';
                        }

						for($i = 0; $i < 8 - count($orders); $i++){

					$html .='
					<tr>
						<td class="prod-det" style="color: white;">.</td>
						<td class="date-cell"></td>
						<td class="cost"></td>
						<td class="qty"></td>
						<td class="amount"></td>
					</tr>';
						}

                    $html .='
                    </tbody>
                    <tfoot>
                        <tr>
                          <td colspan="4">TOTAL</td>
                          <td>P'.number_format($grandtotal, 2).'</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="above-signature">
            <div class="prepaired">
                <p>Prepaired by:&nbsp;<span> </span></p>
            </div>
            <div class="received">
                <p>Received by: <strong>'.$shopdetails['firstname'].' '. $shopdetails['lastname'].'</strong></p>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/editproduct.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <script src="assets/js/notif.js"></script>
</body>

</html>';

$dompdf = new Dompdf($options);

$dompdf->setPaper("A4", "portrait");

$dompdf->loadHtml($html);

$dompdf->render();

$dompdf->stream("Sales-Report.pdf", ["Attachment" => 0]);

$output = $dompdf->output();
// file_put_contents("file.pdf", $output);