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


    if(isset($_POST['request'])){
        $filter = $_POST['request'];
    }

        if($filter == "low"){
            $price =  $data->lowestProductPrice();
        } 
        else if($filter == "high"){
            $price =  $data->highestProductPrice();
        }
            foreach($price as $val){
                $getShop = $data->shopDetails($val['shopID']);
                $station = $getShop[0];
                $sold = $data->countShopSold($val['productID']);
    ?>
        <div class="col-6 col-sm-6 col-md-4 col-lg-3 kolum">
            <a href="customer-view-products.php?prodID=<?php echo $val['productID']?>&stationID=<?php echo $val['shopID'] ?>">
                <div class="product-div">
                    <div class="product-image-div">
                        <img class="img" src="assets/img/products/<?php echo $val['prod_image'] ?>">
                    </div>
                    <div class="product-desc-div">
                        <h6 class="prod-name"><?=$val['product_name']?></h6>
                        <div class="price-sold-div">
                            <p class="prod-price"><?= "₱" . $val['price'] ?></p>
                            <p class="sold"><?php echo $sold ?> sold</p>
                        </div>
                        <p class="prod-location"><i class="fas fa-store"></i>   <?php echo $station['station_name'].' '.$station['branch_name']?></p>
                    </div>
                </div>
            </a>
        </div>
    <?php
            }
    ?>