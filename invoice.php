<?php    
    require_once("assets/classes/dbHandler.php");
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

    // $date = $records[0]['transac_date']; 
    // $createdate = date_create($date);
    // $new_date = date_format($createdate, "F d, Y h:i a");

    $invoiceDate = $data->getInvoiceDate($orderID);

    $date = $invoiceDate[0]['invoice_date']; 
    $createdate = date_create($date);
    $new_date = date_format($createdate, "F d, Y");

?>

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
    <link rel="stylesheet" href="assets/css/invoice.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-navigation-copy.css">
</head>

<body>
    <div class="kontainer">
        <div class="logo">
            <img src="assets/img/profiles/<?= $shopdetails['user_image']?>">
        </div>
        <div class="invoice">
            <p class="name"><?= $new_date?></p>
            <p class="name">Invoice ID: <?= $records[0]['orderID'] ?></p>
        </div>
        <div>
            <table class="tabol-head">
                <thead>
                    <tr>
                        <th class="station-dets">Station:</th>
                        <th class="invoice-to">Customer:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="station-dets"><?= $shopdetails['firstname']?>, <?= $shopdetails['lastname']?></td>
                        <td class="to-details"><?= $customerdetails['firstname'].' '.$customerdetails['lastname']?></td>
                    </tr>
                    <tr>
                        <td class="station-dets"><?= $shopdetails['tin_num']?></td>
                        <td class="to-details"><?= $customerdetails['phone_num']?></td>
                    </tr>
                    <tr>
                        <td class="station-dets"><?= $shopdetails['email']?></td>
                    </tr>
                    <tr>
                        <td class="station-dets"><?= $shopdetails['phone_num']?></td>
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
                <tbody>
                    <?php
                    foreach ($records as $record) {
                        $grandTotal += $record['total'];
                    ?>
                    <tr>
                        <td class="prod-det"><?= $record['product_name']?></td>
                        <td class="cost">₱<?= number_format($record['price'], 2)?></td>
                        <td class="qty"><?= $record['quantity']?></td>
                        <td class="amount">₱<?= number_format($record['total'], 2)?></td>
                    </tr>
                    <?php
                        }
                    ?>
                    <tr class="grand-total">
                        <td class="total">Grand Total</td>
                    </tr>
                    <tr>
                        <td class="total total-amount">₱<?= number_format($grandTotal, 2)?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/editproduct.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
</body>

</html>