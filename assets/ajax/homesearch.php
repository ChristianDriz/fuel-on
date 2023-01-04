<?php
    session_start();

    if(isset($_SESSION['userID'])){
        $userID = $_SESSION['userID'];
        $username = $_SESSION['fname'];
        $userpic = $_SESSION['userPic'];
        $userType = $_SESSION['userType'];
    
        if($userType == 2)
        { 
            header('location: index.php');
        }
    }
    else{
        header('location: index.php');
    }

    include '../db.conn.php';
    
    require_once("../classes/dbHandler.php");
    $data = new Config();
    
    
    if(isset($_POST['key'])){

        $key = "%{$_POST['key']}%";
    }

        $sql = 'SELECT * FROM tbl_fuel 
        WHERE fuel_type 
        LIKE ? 
        OR fuel_category 
        LIKE ? 
        ORDER BY new_price ASC';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$key, $key]);

        if($stmt->rowCount() > 0){
            $allfuels = $stmt->fetchAll();

            foreach($allfuels as $fuel){ 
            $station = $data->customerGetStationforFuel($fuel['shopID']);                
            $shopDetails = $station[0];
    ?>
        <div class="row g-0 justify-content-start feed-row">
            <div class="col-12 feed-head-col">
                <div class="feed-head-img-div">
                    <img src="assets/img/profiles/<?php echo $shopDetails['user_image'] ?>">
                </div>
                <div class="feed-head-name-div">
                    <a href="customer-viewstore-timeline.php?stationID=<?php echo $shopDetails['shopID']; ?>">
                        <?= $shopDetails['station_name'].' '.$shopDetails['branch_name'] ?>
                    </a>
                    <p><?php echo $shopDetails['station_address']?></p>
                </div>
            </div>
            <div class="col-12 feed-body-col">
                <div class="row g-0 justify-content-center">
                    <?php
                        $fueldata = $data->oneFuel($fuel['fuelID']); 
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
            }else{
        ?>
        <div class="no-result-div"><i class="fas fa-search"></i>
            <p>No result found for '<?=htmlspecialchars($_POST['key'])?>'</p>
        </div>
        <?php
            }
        ?>