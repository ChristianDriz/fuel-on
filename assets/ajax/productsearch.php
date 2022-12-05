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
    $data = new Config();


    if(isset($_POST['key'])){

        $key = "%{$_POST['key']}%";
    }

        $sql = 'SELECT * FROM `tbl_products` WHERE quantity != 0 AND product_name LIKE ? ORDER BY price ASC';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$key]);


        if($stmt->rowCount() > 0){
            $prods = $stmt->fetchAll();

            foreach($prods as $val){
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
                        <p class="prod-location"><i class="fas fa-store"></i><?php echo $station['station_name'].' '.$station['branch_name']?></p>
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