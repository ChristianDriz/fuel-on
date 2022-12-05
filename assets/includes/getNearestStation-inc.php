<?php
session_start();
if(isset($_SESSION['userID'])){
    $userID = $_SESSION["userID"];
}
else{
    header('location: index.php');
}

require_once('../classes/dbHandler.php');
$dbh = new Config();

$userLoc = $dbh->getUserLocation($userID);
$loc = $userLoc[0];

    $nearest = $dbh->getNearestStation($loc['map_lat'], $loc['map_lang'], $loc['map_lat']);
    $nearestLocations = [];

    foreach ($nearest as $near){
        $shop = $dbh->shopDetails($near['userID']);
        $shops = $shop[0];

        $distance = number_format($near['distance'], 2);

        if($shops['opening'] == "00:00:00" && $shops['closing'] == "00:00:00"){
            $sched = "24 Hours Open";
        }else{
            //open hour
            $openTime = $shops['opening'];
            $createdate = date_create($openTime);
            $Timeopen = date_format($createdate, "h:i a");

            //close hour
            $closeTime = $shops['closing'];
            $createdate = date_create($closeTime);
            $Timeclose = date_format($createdate, "h:i a");

            $sched = $Timeopen . " to " . $Timeclose;
        }

        $nearestLocations[] = [
            'id' => $shops['shopID'],
            'sched' => $sched,
            'name' => $shops['station_name'] . ' ' . $shops['branch_name'],
            'address' => $shops['station_address'],
            'lng' => $shops['map_lang'],
            'lat' => $shops['map_lat'],
            'distance' => $distance
        ];
    }
    echo json_encode($nearestLocations);