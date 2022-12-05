<?php    
    require_once("assets/classes/dbHandler.php");
    $data = new Config();

    $orderID = "ORD-1090463931";
    $shopID = 5;
    $customerID = 10;

    // $orderID = $_GET['orderId'];
    // $shopID = $_GET['shopID'];
    // $customerID = $_GET['customerID'];

    $shop = $data->oneShop($shopID);
    $shopdetails = $shop[0];

    $customer = $data->oneCustomer($customerID);
    $customerdetails = $customer[0];

    $records = $data->getShopBillingReceipt($orderID);
    $customerName = $records[0]['customerName'];
    $grandTotal = 0;

    $date = $records[0]['transac_date']; 
    $createdate = date_create($date);
    $new_date = date_format($createdate, "F d, Y h:i a");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/css/invoice-template.css">
</head>

<body>
    <div class="container print-container">
        <h2>Invoice</h2>
        <div class="logo">
            <img src="assets/img/profiles/<?= $shopdetails['user_image']?>"/>
        </div>
        <div class="heading">
            <h5><?= $shopdetails['firstname']?></h5>
            <p><?= $shopdetails['lastname']?></p>
            <p><?= $shopdetails['tin_num']?></p>
            <p><?= $shopdetails['email']?></p>
            <p><?= $shopdetails['phone_num']?></p>
        </div>
        <div>
            <table class="order-details-table">
                <tbody>
                    <tr>
                        <th>Order ID</th>
                        <th>Billed to</th>
                    </tr>
                    <tr>
                        <td><?= $records[0]['orderID'] ?></td>
                        <td><?= $customerdetails['firstname'].' '.$customerdetails['lastname']?></td>
                    </tr>
                    <tr>
                        <th>Order Status</th>
                        <td><?= $customerdetails['phone_num']?></td>
                    </tr>
                    <tr>
                        <td><?= $records[0]['order_status'] ?></td>
                    </tr>
                    <tr>
                        <th>Date</th>
                    </tr>
                    <tr>
                        <td><?= $new_date?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <table class="details-table">
                <thead>
                    <tr>
                        <th style="width: 53%;">Description</th>
                        <th style="width: 22%;">Unit Cost</th>
                        <th style="width: 10%;">Qty</th>
                        <th style="width: 15%;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($records as $record) {
                        $grandTotal += $record['total'];
                    ?>
                    <tr>
                        <td><?= $record['product_name']?></td>
                        <td>₱<?= number_format($record['price'])?></td>
                        <td><?= $record['quantity']?></td>
                        <td>₱<?= number_format($record['total'])?></td>
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div>
            <table class="total-table">
                <tbody>
                    <tr class="item-descript">
                        <th style="width: 85%;">Note</th>
                        <th style="width: 15%;">Total</th>
                    </tr>
                    <tr class="item-descript">
                        <td>Please pay to the cashier of the station</td>
                        <td>₱<?= number_format($grandTotal)?></td>
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