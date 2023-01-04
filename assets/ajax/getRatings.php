<?php
    session_start();

    if(isset($_SESSION['userID'])){
        $userID = $_SESSION["userID"];
        $username = $_SESSION["fname"];
        $userType = $_SESSION["userType"];
        $userpic = $_SESSION["userPic"];

    }
    else{
        header('location: index.php');
    }   

    require_once("../classes/dbHandler.php");
    $data = new Config();

    if(isset($_GET['stationID'])){
        $station = $_GET['stationID']; 
    }
    else{
        $station = 0;
    }

    if(isset($_GET['request'])){
        $request = $_GET['request']; 
    }

        if($request == "fiveStar"){
            $Stars = $data->viewRatingsFiveStar($station);

        }elseif($request == "fourStar"){
            $Stars = $data->viewRatingsFourStar($station);  

        }elseif($request == "threeStar"){
            $Stars = $data->viewRatingsThreeStar($station);  

        }elseif($request == "twoStar"){
            $Stars = $data->viewRatingsTwoStar($station);  
       
        }elseif($request == "oneStar"){
            $Stars = $data->viewRatingsOneStar($station);  

        }elseif($request == "allStar"){
            $Stars = $data->viewRatings($station);         
        }

            if(!empty($Stars)){
                foreach($Stars as $ratings){ 
        ?>
        <div class="ratings-div">
            <div>
                <div class="rates-pic-div"><img class="rates-img" src="assets/img/profiles/<?=$ratings['user_image']?>"></div>
            </div>
            <div class="rates-content">
                <div class="user-div">
                    <p class="user-name"><?=$ratings['firstname'].' '.$ratings['lastname']?></p>
                </div>
                <div class="star-div">
                    <?php
                        $star = 0;
                        while($star < $ratings['rating']){
                    ?>
                            <i class="fas fa-star"></i>
                    <?php
                        $star++;
                        }
                    ?>
                </div>
                <div class="comment-div">
                    <p><?=$ratings['feedback']?></p>
                </div>
                <div class="date-div">
                    <p class="rate-date"><?php echo $data->datetimeconverter($ratings['rating_date']) ?></p>
                </div>
            </div>
        </div>
        <?php 
                }
            }else{
        ?>
        <div class="no-ratings-div">
            <p>No Ratings Yet</p>
        </div>
        <?php
            }
        ?>