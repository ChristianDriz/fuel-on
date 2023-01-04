<?php
    session_start();
    if(isset($_SESSION['userID'])){
        $userID = $_SESSION["userID"];
    }
    else{
        header('location: index.php');
    }

    require_once("../classes/dbHandler.php");
    $data = new Config();


    if(isset($_POST['fuel'])){
        $filter = $_POST['fuel'];
    }

        // $diesel = $data->productsFuelDiesel();
        // $unleaded = $data->productsFuelUnleaded();
        // $premium = $data->productsFuelPremium();
        // $allfuels = $data->productsFuelAll();

        if($filter == "diesel"){
            $fuels = $data->productsFuelDiesel();

        }elseif($filter == "unleaded"){
            $fuels = $data->productsFuelUnleaded();

        }elseif($filter == "premium"){
            $fuels = $data->productsFuelPremium();

        }elseif($filter == "racing"){
            $fuels = $data->productsFuelRacing();

        }elseif($filter == "all"){
            $fuels = $data->productsFuelAll();

        }
            foreach($fuels as $fuel){
    ?>
        <div class="row g-0 justify-content-start feed-row">
            <div class="col-12 feed-head-col">
                <div class="feed-head-img-div">
                    <img src="assets/img/profiles/<?php echo $fuel['user_image'] ?>">
                </div>
                <div class="feed-head-name-div">
                    <a href="customer-viewstore-timeline.php?stationID=<?php echo $fuel['shopID']; ?>">
                        <?= $fuel['station_name'].' '.$fuel['branch_name'] ?>
                    </a>
                    <p><?php echo $fuel['station_address']?></p>
                </div>
            </div>
            <div class="col-12 feed-body-col">
                <div class="row g-0 justify-content-center">
                    <?php
                        if($filter == "all"){
                            $fueldata = $data->fuelDetails($fuel['shopID']); 
                        }else{
                            $fueldata = $data->oneFuel($fuel['fuelID']); 
                        }
                            foreach($fueldata as $fuels){
                    ?>
                    <div class="col-sm-6 kolum">
                        <?php
                            //if the fuel is not available, the unavailable tag will displayed
                            if($fuels['fuel_status'] == "not available"){
                        ?>
                        <span class="status-tag">Not available</span>
                        <?php
                            }
                            if($fuels['fuel_category'] == "Diesel" ){
                        ?>
                        <div class="fuel-div diesel">
                        <?php
                            }elseif($fuels['fuel_category'] == "Unleaded"){
                        ?>
                        <div class="fuel-div unleaded">
                        <?php
                            }elseif($fuels['fuel_category'] == "Premium"){
                        ?>
                        <div class="fuel-div premium">
                        <?php
                            }elseif($fuels['fuel_category'] == "Racing"){
                        ?>
                        <div class="fuel-div racing">
                        <?php
                            }
                        ?>
                            <div class="fuel-name">
                                <h1><?php echo $fuels['fuel_category']?></h1>
                                <h6><?php echo $fuels['fuel_type']?></h6>
                            </div>
                            <div class="fuel-price">
                                <?php
                                    if(empty($fuels['old_price'])){
                                ?>
                                <h1><strong>₱</strong><?php echo $fuels['new_price']?>
                                <?php
                                    }else{
                                        if($fuels['new_price'] > $fuels['old_price']){
                                ?>
                                <h1><strong>₱</strong><?php echo $fuels['new_price']?><i class="fas fa-angle-double-up"></i><span><?php echo number_format($fuels['new_price'] - $fuels['old_price'], 2) ?></span></h1>
                                <p>from <strong>₱</strong><?php echo $fuels['old_price']?></p>
                                <?php
                                        }elseif($fuels['new_price'] < $fuels['old_price']){
                                ?>
                                <h1><strong>₱</strong><?php echo $fuels['new_price']?><i class="fas fa-angle-double-down"></i><span><?php echo abs(number_format($fuels['new_price'] - $fuels['old_price'], 2)) ?></span></h1>
                                <p>from <strong>₱</strong><?php echo $fuels['old_price']?></p>
                                <?php
                                        }
                                    }
                                ?>
                            </div>
                            <p class="date">as of <?php echo $data->dateconverter($fuels['date_updated'])?></p>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    <?php 
        }
    ?>

