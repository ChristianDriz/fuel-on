<?php    

    require_once("assets/classes/dbHandler.php");
    $data = new Config();

    //$orderID = "ORD-312438465";
    // $shopID = 5;
    // $customerID = 10;

    $orderID = $_GET['orderId'];
    $shopID = $_GET['shopID'];
    $customerID = $_GET['customerID'];

    $shop = $data->shopDetails($shopID);
    $shopdetails = $shop[0];

    $customer = $data->oneCustomer($customerID);
    $customerdetails = $customer[0];

    $records = $data->getOrders($orderID);
    $transacID = $records[0]['transacID'];
    $status = $records[0]['order_status'];
    $grandTotal = 0;

    $date = $records[0]['transac_date']; 
    $createdate = date_create($date);
    $new_date = date_format($createdate, "F d, Y");

    require __DIR__ . "/vendor/autoload.php";

    use Dompdf\Dompdf;
    use Dompdf\Options;

    // $orderId = $_GET['orderId'];
    // $shopID = $_GET['shopID'];
    // $customerID = $_GET['customerID'];

    /**
     * Set the Dompdf options
     */
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
    <link rel="stylesheet" href="assets/css/generate-invoice.css">
</head>

<body>
    <div class="kontainer">
        <div class="head">
            <div class="logo">
                <img src="assets/img/profiles/'.$shopdetails['user_image']. '">
            </div>
            <div class="shop-details">
                <p class="address">'.$shopdetails['station_name'].', '. $shopdetails['branch_name'].'</p>
                <p class="tin">TIN:'.$shopdetails['tin_number'].'</p>
                <p class="email">'.$shopdetails['email'].'</p>
                <p class="phone">'.$shopdetails['phone_num'].'</p>
            </div>
            <div class="invoice">
                <p class="invoice-title">INVOICE</p>
                <p class="date">'.$new_date.'</p>
                <p class="invoice-id">Invoice #: '.$transacID.'</p>
            </div>
        </div>
        <div class="line"></div>
        <div class="inside">
            <div>
                <table class="table-head">
                    <thead>
                        <tr>
                            <th class="order-details">Order Details:</th>
                            <th class="invoice-to">Bill to:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="order-id">'.$orderID.'</td>
                            <td class="to-details">'.$customerdetails['firstname'].' '.$customerdetails['lastname'].'</td>
                        </tr>
                        <tr>
                            <td class="status">'.$status.'</td>
                            <td class="to-details">'.$customerdetails['phone_num'].'</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div>
                <table class="prod-table">
                    <thead>
                        <tr>
                            <th class="prod-det">Product Details</th>
                            <th class="cost">Unit Cost</th>
                            <th class="qty">Qty</th>
                            <th class="amount">Amount</th>
                        </tr>
                    </thead>
                    <tbody>';

                        foreach ($records as $record) {
                            $grandTotal += $record['total'];

                    $html .='
                        <tr>
                            <td class="prod-det">'.$record['product_name'].'</td>
                            <td class="cost">P'.number_format($record['price'], 2).'</td>
                            <td class="qty">'.$record['quantity'].'</td>
                            <td class="amount">P'.number_format($record['total'], 2).'</td>
                        </tr>';
                        }

                        for($i = 0; $i < 10 - count($records); $i++){

                    $html .='      
                        <tr>
                            <td class="prod-det" style="color: white;">.</td>
                            <td class="cost"></td>
                            <td class="qty"></td>
                            <td class="amount"></td>
                        </tr>';
                        }

                    $html .='
                    </tbody>
                    <tfoot>
                        <tr>
                        <td colspan="3">TOTAL</td>
                        <td>P'.number_format($grandTotal, 2).'</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="signature-div">
            <p>Signature</p>
        </div>
        <div class="foot">
            <p>Thankyou for choosing us!</p>
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

/**
 * Set the paper size and orientation
 */
$dompdf->setPaper("A4", "portrait");

/**
 * Load the HTML and replace placeholders with values from the form
 */

$dompdf->loadHtml($html);
/**
 * Create the PDF and set attributes
 */
$dompdf->render();

/**
 * Send the PDF to the browser
 */
$dompdf->stream($orderID.".pdf", ["Attachment" => 0]);

/**
 * Save the PDF file locally
 */
$output = $dompdf->output();
// file_put_contents("file.pdf", $output);