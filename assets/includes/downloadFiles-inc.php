<?php
// session_start();
// Downloads files
if (isset($_GET['stationID'])) {
    require_once('../classes/dbHandler.php');
    $dbh = new Config();

    $id = $_GET['stationID'];

    $file =  $dbh->downloadFile($id);
    $filepath = '../../uploads/' . $file['file_name'];

    if (file_exists($filepath)) {
        //Define header information
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: 0");
        header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
        header('Content-Length: ' . filesize($filepath));
        header('Pragma: public');

        //Clear system output buffer
        flush();

        //Read the size of the file
        readfile($filepath);

        //Terminate from the script
        die();
    } else {

        // echo $filepath;
        echo 'file does not exist';
    }
}
