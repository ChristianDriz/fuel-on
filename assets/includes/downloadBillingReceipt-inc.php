<?php
    session_start();
    if (isset($_SESSION['userID'])) {
        $userID = $_SESSION['userID'];
        $username = $_SESSION['fname'];
        $userpic = $_SESSION['userPic'];
        $userType = $_SESSION['userType'];

        if($userType == 0)
        { 
            header('location: index.php');
        }

    } else {
        header('location: index.php');
    }

    require_once('../classes/dbHandler.php');
    require_once('../../vendor/autoload.php');

    $data = new Config();

    $orderId = $_GET['orderId'];
    $shopID = $_GET['shopID'];
    $customerID = $_GET['customerID'];

    $invoiceDate = $data->getInvoiceDate($orderId);
    $invoice_date = $invoiceDate[0];

    // if(empty($invoice_date['invoice_date'])){
    //     $data->insertInvoiceDate($orderId);
    // }


    use Knp\Snappy\Pdf;

if (!function_exists('base_url')) {
    function base_url($atRoot = FALSE, $atCore = FALSE, $parse = FALSE)
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
            $hostname = $_SERVER['HTTP_HOST'];
            $dir =  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
            $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), NULL, PREG_SPLIT_NO_EMPTY);
            $core = $core[0];
            $tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
            $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
            $base_url = sprintf($tmplt, $http, $hostname, $end);
        } else $base_url = 'http://localhost/';
        if ($parse) {
            $base_url = parse_url($base_url);
            if (isset($base_url['path'])) if ($base_url['path'] == '/') $base_url['path'] = '';
        }
        return $base_url;
    }
}

// @Todo remove /FuelOn
$url = base_url(true) . 'FuelOn/invoice.php?orderId=' . $orderId . '&shopID=' . $shopID . ' &customerID=' . $customerID;
// @Todo change this later before uploading
$snappy = new Pdf('D://"Program Files"/wkhtmltopdf/bin/wkhtmltopdf.exe');
header('Content-Type: application/pdf');

//for auto print
// header('Content-Disposition: attachment; '. 'filename="Order Invoice #' . $orderId  . ".pdf");

//for manual print
header('Content-Disposition: '. 'filename="' . $orderId  . ".pdf");
echo $snappy->getOutput($url);