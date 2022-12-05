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

        $sql = 'SELECT * FROM tbl_fuel WHERE fuel_type LIKE ? OR fuel_category LIKE ? ORDER BY new_price ASC';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$key, $key]);

        if($stmt->rowCount() > 0){
            $allfuels = $stmt->fetchAll();

            foreach($allfuels as $fuel){ 
            $station = $data->customerGetStationforFuel($fuel['shopID']);                
            $shopDetails = $station[0];
    ?>
        <div class="row feed-row">
            <div class="col-12 feed-head-col">
                <div class="feed-head-img-div"><img src="assets/img/profiles/<?php echo $shopDetails['user_image'] ?>"></div>
                <div class="feed-head-name-div">
                    <a href="customer-viewstore-timeline.php?stationID=<?php echo $fuel['shopID']; ?>">
                        <?= $shopDetails['station_name'].' '.$shopDetails['branch_name'] ?>
                    </a>
                </div>
            </div>  
                <div class="col-12 feed-body-col">
                <div class="feed-body-div">
                    <img class="fuel-img" src="assets/img/products/<?php echo $fuel['fuel_image'] ?>">
                    <div class="fuel-details-div">
                        <h1 class="fuel-name"><?php echo $fuel['fuel_type'] ?></h1>
                        <?php
                            if(empty($fuel['old_price'])){
                        ?>
                        <div class="price-div">
                            <h1>₱<?php echo $fuel['new_price'] ?></h1>
                        </div>
                        <?php
                            }
                            else{
                        ?>
                        <div class="price-div">
                            <h1>₱<?php echo $fuel['old_price'] ?></h1>
                            <i class="icon ion-arrow-right-a"></i>
                            <h1>₱<?php echo $fuel['new_price'] ?></h1>
                            <?php
                                if($fuel['new_price'] > $fuel['old_price']){
                            ?>
                            <div class="price-change-div up"><i class="icon ion-arrow-up-a arrow-up"></i>
                                <p>+<?php echo number_format($fuel['new_price'] - $fuel['old_price'], 2) ?></p>
                            </div>
                            <?php
                                }elseif($fuel['new_price'] < $fuel['old_price']){
                            ?>
                            <div class="price-change-div down"><i class="icon ion-arrow-down-a arrow-up"></i>
                                <p><?php echo number_format($fuel['new_price'] - $fuel['old_price'], 2) ?></p>
                            </div>
                            <?php
                                }
                            ?>
                        </div>
                        <?php
                            }
                            $date = $fuel['date_updated'];
                            $createdate = date_create($date);
                            $new_date = date_format($createdate, "F d, Y");
                        ?>
                        <p class="date-p">Price as of <?php echo $new_date ?></p>
                        <p class="status-p"><span>Status:</span><?php echo $fuel['fuel_status']?></p>
                    </div>
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