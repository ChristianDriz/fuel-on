<?php

    if (isset($_GET['userID'])) {
        $userID = $_GET['userID'];
    }

    require_once("../classes/dbHandler.php");
    $data = new Config();

    $stations = $data->divideShops($userID);
    foreach ($stations as $station) {
        $shopID = $station['shopID'];
        $records = $data->sortCart($userID, $shopID);
        $val = $records[0];
    }
    ?>
    <p class="stocks"><?php echo $val['stocks']?> items left</p>