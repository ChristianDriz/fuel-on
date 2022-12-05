<?php
        if(isset($_GET['prodID'])) {
            $prodID = $_GET['prodID'];
        } else {
            $prodID = 0;
        }

        require_once('../classes/dbHandler.php');
        $dbh = new Config();
        $dbh->deleteProducts($prodID);

        echo "<script>alert('Product deleted successfully!');document.location='../../store-myproducts.php'</script>";