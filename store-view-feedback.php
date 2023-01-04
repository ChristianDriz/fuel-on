<?php
session_start();
if(isset($_SESSION['userID'])){
    $userID = $_SESSION["userID"];
    $username = $_SESSION["fname"];
    $userpic = $_SESSION["userPic"];
    $userType = $_SESSION["userType"];

    if ($userType == 1 || $userType == 0) 
    {
        header('location: index.php');
    }
}
else{
    header('location: index.php');
}

require_once("assets/classes/dbHandler.php");
$dbh = new Config();

if(isset($_GET['stationID'])){
    $station = $_GET['stationID'];
}
else{
    $station = $userID;
}

$feedback = $dbh->viewRatings($userID);
$get = $dbh->getFeedback($station);
$count = $dbh->getRatings($station);
if(!empty($get) || !empty($count)){
$rateSum = 0;
foreach($get as $rate){
    $rateSum += $rate['rating'];
}
$totalRate = $rateSum / $count;
}else{
    $totalRate = 0;
}

$feedback = $dbh->viewRatings($station);

$countAll = $dbh->countRatings($station);
$countOne = $dbh->countOneStar($station);
$countTwo = $dbh->countTwoStar($station);
$countThree = $dbh->countThreeStar($station);
$countFour = $dbh->countFourStar($station);
$countFive = $dbh->countFiveStar($station);

$shop = $dbh->shopDetails($userID);
$shopDetails = $shop[0];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | Station Feedbacks</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-navigation.css">
    <link rel="stylesheet" href="assets/css/Store%20css%20files/store-view-feedback.css">
</head>

<body>
    <?php
        //top navigation
        include 'top-navigation.php';
    ?>
    <div id="wrapper">
        <?php
            //side navigation
            include 'side-navigation.php';
        ?>
        <div class="page-content-wrapper">
            <?php
                    $records = $dbh->oneShop($station);
                    foreach($records as $key => $val){
            ?>
            <div class="container ratings-container">
                <h4>My Station Ratings</h4>
                <div class="div-div">
                    <div id="store-ratings-total" class="ratings-summary">
                        <h2><?= number_format($totalRate, 1)?> out of 5<i class="fas fa-star"></i></h2>
                        <div class="row justify-content-center">
                            <div class="col-auto"><button class="btn ratings-btn active" type="button" id="allStar" value="<?php echo $val['userID']; ?>">All<span>(<?=$countAll?>)</span></button></div>
                            <div class="col-auto"><button class="btn ratings-btn" type="button" id="fiveStar" value="<?php echo $val['userID']; ?>"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><span>(<?=$countFive?>)</span></button></div>
                            <div class="col-auto"><button class="btn ratings-btn" type="button" id="fourStar" value="<?php echo $val['userID']; ?>"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><span>(<?=$countFour?>)</span></button></div>
                            <div class="col-auto"><button class="btn ratings-btn" type="button" id="threeStar" value="<?php echo $val['userID']; ?>"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><span>(<?=$countThree?>)</span></button></div>
                            <div class="col-auto"><button class="btn ratings-btn" type="button" id="twoStar" value="<?php echo $val['userID']; ?>"><i class="fas fa-star"></i><i class="fas fa-star"></i><span>(<?=$countTwo?>)</span></button></div>
                            <div class="col-auto"><button class="btn ratings-btn" type="button" id="oneStar" value="<?php echo $val['userID']; ?>"><i class="fas fa-star"></i><span>(<?=$countOne?>)</span></button></div>
                        </div>
                    </div>

                    <!--Start ng loop-->
                    <div class="dito">
                    <?php
                        if(!empty($feedback)){
                            foreach($feedback as $ratings){   
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
                                <p class="rate-date"><?php echo $dbh->datetimeconverter($ratings['rating_date']) ?></p>
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
                    </div>
                    <!--End ng loop-->
                </div>
            </div>
            <?php
                }
            ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/ratings.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script>
        var stars = document.querySelectorAll(".ratings-btn");
        stars.forEach(button => {
            button.addEventListener("click",()=> {
                resetActive();
                button.classList.add("active");
            })
        })

        function resetActive(){
            stars.forEach(button => {
                button.classList.remove("active");
            })
        }

        //for last seen update
        let lastSeenUpdate = function(){
      	    $.get("assets/ajax/active_status.php");
        }
        lastSeenUpdate();
        setInterval(lastSeenUpdate, 1000);

        
        //for message notif
        let fetchMessageNotif = function(){
      	$.get("assets/ajax/unread_message_count.php", 
            {
            userID: <?php echo $userID ?>
            },
            function(data){
                if (data != 0){
                    $(".message-counter").html(data);
                }
            });
        }
        fetchMessageNotif();
        //auto update every .5 sec
        setInterval(fetchMessageNotif, 500);
    </script>
</body>
</html>