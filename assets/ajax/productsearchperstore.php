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
        $shopID = $_POST['shopID'];

    }

        $sql = 'SELECT * FROM tbl_products
        WHERE shopID = ?
        AND product_name LIKE ?
        ORDER BY price ASC';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$shopID, $key]);


        if($stmt->rowCount() > 0){
            $prods = $stmt->fetchAll();

            foreach($prods as $val){
                $sold = $data->countShopSold($val ['productID']);
    ?>
            <div class="col-6 col-sm-6 col-md-4 col-lg-3 kolum">
                <a href="customer-view-products.php?prodID=<?php echo $val['productID']?>&stationID=<?php echo $val['shopID'] ?>">
                    <div class="product-div">
                        <div class="product-image-div">
                            <?php
                                //if product is no stock, will display the no stock label
                                if($val['quantity'] == 0){
                            ?>
                            <img class="img no-stock-img" src="assets/img/products/<?= $val['prod_image']?>">
                            <div class="sold-out-div"><p>Out of stock</p></div>
                            <?php
                                }else{
                            ?>
                            <img class="img" src="assets/img/products/<?= $val['prod_image']?>">
                            <?php
                                }
                            ?>
                        </div>
                        <div class="product-desc-div">
                            <h6 class="prod-name"><?= $val['product_name']?></h6>
                            <div class="price-sold-div">
                                <p class="prod-price"><?= "₱".$val['price']?></p>
                                <?php
                                    if($sold == 0){
                                ?>
                                <p class="sold">0 sold</p>
                                <?php
                                    }else{
                                ?>
                                <p class="sold"><?php echo $data->numberconverter($sold)?> sold</p>
                                <?php
                                    }
                                ?>
                            </div>
                            <p class="prod-location">Stocks:<?= ' '.$val['quantity']?></p>
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