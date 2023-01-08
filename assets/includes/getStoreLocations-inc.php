<?php
// Downloads files

require_once('../classes/dbHandler.php');
$dbh = new Config();

$storeLocations = $dbh->getStoreLocations();
$locations = [];

foreach($storeLocations as $shop){
    if($shop['map_lang'] && $shop['map_lat'])

        if($shop['opening'] == "00:00:00" && $shop['closing'] == "00:00:00"){
            $sched = "Open 24 Hours";

        }else{
            //open hour
            $openTime = $shop['opening'];
            $createdate = date_create($openTime);
            $Timeopen = date_format($createdate, "h:i a");

            //close hour
            $closeTime = $shop['closing'];
            $createdate = date_create($closeTime);
            $Timeclose = date_format($createdate, "h:i a");

            $sched = "Open: " . $Timeopen . " Close: " . $Timeclose;
        }

    $locations[] = [
        'id' => $shop['userID'],
        'sched' => $sched,
        'name' => $shop['station_name'] . ' ' . $shop['branch_name'],
        'address' => $shop['station_address'],
        'lng' => $shop['map_lang'],
        'lat' => $shop['map_lat'],
    ];
}
echo json_encode($locations);

