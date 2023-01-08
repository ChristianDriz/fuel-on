<?php
session_start();
if(isset($_SESSION['userID'])){
   $userID = $_SESSION['userID'];
}
    require_once('../classes/dbHandler.php');
    $dbh = new Config();

    $storeLocations = $dbh->shopDetails($userID);
    $location = [];

    foreach($storeLocations as $storeLocation){
        if($storeLocation['map_lang'] && $storeLocation['map_lat'])

            if($storeLocation['opening'] == "00:00:00" && $storeLocation['closing'] == "00:00:00"){
                $sched = "Open 24 hours";

            }else{
                //open hour
                $openTime = $storeLocation['opening'];
                $createdate = date_create($openTime);
                $Timeopen = date_format($createdate, "h:i a");

                //close hour
                $closeTime = $storeLocation['closing'];
                $createdate = date_create($closeTime);
                $Timeclose = date_format($createdate, "h:i a");

                $sched = "Open: " . $Timeopen . " Close: " . $Timeclose;
            }

        $location[] = [
            'name' => $storeLocation['station_name'] . ' ' . $storeLocation['branch_name'],
            'address' => $storeLocation['station_address'],
            'sched' => $sched,
            'lng' => $storeLocation['map_lang'],
            'lat' => $storeLocation['map_lat'],
        ];
    }
    echo json_encode($location);