<?php

session_start();

# check if the user is logged in
if (isset($_SESSION['userID'])) {
    $type = $_SESSION['userType'];

    # check if the key is submitted
    if(isset($_POST['key'])){
        # database connection file
	    include '../db.conn.php';
        
        require_once("../classes/dbHandler.php");
        $dbh = new Config();

	    # creating simple search algorithm :) 
	    $key = "%{$_POST['key']}%";

        //normal user
        if($type == 1){
            $sql = "SELECT tbl_users.*, tbl_station.*
            FROM tbl_users, tbl_station
            WHERE tbl_users.userID = tbl_station.shopID
            AND user_type = 2
            AND verified = 1
            AND CONCAT(tbl_station.station_name, ' ', tbl_station.branch_name) LIKE ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$key]);
        }
        //stations
        elseif($type == 2){
            $sql = "SELECT * FROM tbl_users
            WHERE user_type = 1 
            AND verified = 1
            AND CONCAT(firstname, ' ', lastname) LIKE ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$key]);
        }
        //admin
        elseif($type == 0){
            $sql = "SELECT * FROM tbl_users
            WHERE (user_type = 1 OR user_type = 2) 
            AND verified = 1
            AND firstname LIKE ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$key]);
        }
     
       if($stmt->rowCount() > 0){ 
         $users = $stmt->fetchAll();

        foreach ($users as $user) {
         	if ($user['userID'] == $_SESSION['userID']) continue;
        ?>
        <!-- <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-messages.css"> -->
            <a href="chat-box.php?userID=<?=$user['userID']?>&userType=<?= $user['user_type']?>">
                <div class="content">
                    <div class="chat-image">
                    <img src="assets/img/profiles/<?=$user['user_image']?>">
                    </div>
                    <div class="details">
                        <?php
                            if($type == 0){
                                if($user['user_type'] == 0 ){
                        ?>
                            <p class="chat name"><?=$user['firstname'].' '.$user['lastname']?></p>
                        <?php
                                }elseif($user['user_type'] == 1){
                        ?>
                            <p class="chat name"><?=$user['firstname'].' '.$user['lastname']?></p>
                        <?php
                                }elseif($user['user_type'] == 2){
                                $shopDetails = $dbh->shopDetails($user['userID']);
                                $shop = $shopDetails[0];
                        ?>
                            <p class="chat name"><?=$user['firstname'].' '.$user['lastname']?></p>
                            <p style="color: black;">(<?=$shop['station_name'].' '.$shop['branch_name']?>)</p>
                        <?php
                                }
                            }else{
                                if($user['user_type'] == 0 ){
                        ?>
                            <p class="chat name"><?=$user['firstname'].' '.$user['lastname']?></p>
                        <?php
                                }elseif($user['user_type'] == 1){
                        ?>
                            <p class="chat name"><?=$user['firstname'].' '.$user['lastname']?></p>
                        <?php
                                }elseif($user['user_type'] == 2){
                        ?>
                            <p class="chat name"><?=$user['station_name'].' '.$user['branch_name']?></p>
                        <?php
                                }
                            }
                        ?>
                    </div>
                </div>
            </a>
        <?php }
         }else { ?>
         <div class="alert alert-danger text-center">
		   <i class="fas fa-user-times d-block fs-big"></i>
           Name "<?=htmlspecialchars($_POST['key'])?>"
           doesn't exist.
		</div>
    <?php }
    }

}else {
	header("Location: ../../login.php");
	exit;
}