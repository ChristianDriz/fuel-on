<?php
    session_start();
    if(isset($_SESSION['userID'])){
        $userID = $_SESSION["userID"];

    }
    else{
        header('location: index.php');
    }

    include '../db.conn.php';

    require_once("../classes/dbHandler.php");
    $dbh = new Config();


    if(isset($_POST['key'])){

        $key = "%{$_POST['key']}%";
    }

        $sql = 'SELECT tbl_users.*, tbl_station.*
		FROM tbl_users, tbl_station
        WHERE tbl_users.userID = tbl_station.shopID
        AND verified = 1 
        AND CONCAT(tbl_station.station_name, " ", tbl_station.branch_name) 
        LIKE ?';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$key]);


        if($stmt->rowCount() > 0){
            $prods = $stmt->fetchAll();

            foreach($prods as $val){

                $get = $dbh->getFeedback($val['shopID']);
                $count = $dbh->getRatings($val['shopID']);
                if(!empty($get) || !empty($count)){
                    $rateSum = 0;
                    foreach($get as $rate){
                        $rateSum += $rate['rating'];
                    }
                    $totalRate = $rateSum / $count;
                }else{
                    $totalRate = 0;
                }
    ?>
        <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4 col-xxl-3 kolum">
            <a href="customer-viewstore-timeline.php?stationID=<?php echo $val['shopID']?>">
                <div class="product-div">
                    <div class="product-image-div">
                        <img class="img" src="assets/img/profiles/<?php echo $val['user_image']?>">
                    </div>
                    <div class="product-desc-div">
                        <h6 class="prod-name"><?php echo $val['station_name'] ?> <?php echo $val['branch_name'] ?></h6>
                        <div class="ratings-div">
                            <div><i class="fas fa-star"></i></div>
                            <p><?= number_format($totalRate, 1)?> (<?php echo $dbh->numberconverter($count)?> Rating)</p>
                        </div>
                        <p class="prod-location">
                            <i class="fas fa-map-marker-alt"></i><?php echo $val['station_address'] ?>
                        </p>
                    </div>
                </div>
            </a>
        </div>
    <?php 
            } 
        }else{
    ?>
        <div class="row">
            <div class="col no-result-col">
                <i class="fas fa-search"></i>
                <p>No result found for '<?=htmlspecialchars($_POST['key'])?>'</p>
            </div>
        </div>
    <?php
        }
    ?>
