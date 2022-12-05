<?php
        if(isset($_GET['fuelID'])) {
            $fuelID = $_GET['fuelID'];
        } else {
            $fuelID = 0;
        }

        require_once('../classes/dbHandler.php');
        $dbh = new Config();
        $dbh->deleteFuel($fuelID);

        // echo "<script>alert('Product deleted successfully!');document.location='../../store-mytimeline.php'</script>";
        // $dbh->info("../../store-mytimeline.php", "Product deleted successfully!");

