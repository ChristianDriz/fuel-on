<?php
session_start();

if(isset($_SESSION['userID'])){
    $userID = $_SESSION["userID"];
    $username = $_SESSION["fname"];
    $userType = $_SESSION["userType"];
    $userpic = $_SESSION["userPic"];

    if($userType == 2)
    { 
        header('location: index.php');
    }
    elseif($userType == 0)
    { 
        header('location: index.php');
    }
}
else{
    header('location: index.php');
}   

require_once("assets/classes/dbHandler.php");
$data = new Config();

if(isset($_GET['stationID'])){
    $station = $_GET['stationID'];

}
else{
    $station = $userID;
}

$get = $data->getFeedback($station);
$count = $data->getRatings($station);
if(!empty($get) || !empty($count)){
$rateSum = 0;
foreach($get as $rate){
    $rateSum += $rate['rating'];
}
$totalRate = $rateSum / $count;
}else{
    $totalRate = 0;
}

$stations = $data->shopDetails($station);

$feedback = $data->viewRatings($station);

$countAll = $data->countRatings($station);
$countOne = $data->countOneStar($station);
$countTwo = $data->countTwoStar($station);
$countThree = $data->countThreeStar($station);
$countFour = $data->countFourStar($station);
$countFive = $data->countFiveStar($station);

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
    <link rel="stylesheet" href="assets/fonts/material-icons.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-navigation.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-view-feedback.css">
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
            <div class="store-profile-div">
            <?php
                foreach($stations as $val){

                    //open hour
                    $openTime = $val['opening'];
                    $createdate = date_create($openTime);
                    $Timeopen = date_format($createdate, "h:i a");

                    //close hour
                    $closeTime = $val['closing'];
                    $createdate = date_create($closeTime);
                    $Timeclose = date_format($createdate, "h:i a");
            ?>
                <div class="container">
                    <div class="map-div" id="maps"></div>
                    <input type="hidden" id="mapLat" value="<?php echo $val['map_lat']?>">
                    <input type="hidden" id="mapLng" value="<?php echo $val['map_lang']?>">
                    <input type="hidden" id="station" value="<?php echo $val['station_name'].' '.$val['branch_name']?>">
                    <input type="hidden" id="address" value="<?php echo $val['station_address']?>">

                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-8 profile-col">
                            <div class="profile-image-div">
                                <div class="outline-div">
                                    <img class="img" src="assets/img//profiles/<?= $val['user_image']?>">
                                </div>
                            </div>
                            <div class="profile-name-div">
                                <h5><?= $val['station_name'].' '.$val['branch_name'] ?> Branch</h5>
                                <p style="font-weight: 600;"><?php echo $val['station_address']?></p>
                                <p class="rating">
                                    <i class="far fa-star"></i>Rating:
                                    <a><span><?= number_format($totalRate, 1)?> (<?= $count ?> Rating)</span></a>
                                </p>
                                <div class="sched-div 24hrs"><i class="far fa-clock"></i>
                                    <p class="open">
                                        <span style="font-weight: 500;">
                                        <?php
                                            if($val['opening'] == "00:00:00" && $val['closing'] == "00:00:00"){
                                                echo "Open 24 Hours"; 
                                            }else{
                                                echo 'Open: ' . $Timeopen . ' Close: ' . $Timeclose;
                                            }
                                        ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                } 
            ?>
            </div>
            <div class="container ratings-container">
                <div id="store-ratings-total" class="ratings-summary">
                    <div class="rates-heading">
                        <a class="back">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-arrow-left">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <line x1="5" y1="12" x2="11" y2="18"></line>
                                <line x1="5" y1="12" x2="11" y2="6"></line>
                            </svg>
                        </a>
                        <h4>Station Ratings</h4>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-auto"><button class="btn ratings-btn active" type="button" id="allStar" value="<?php echo $val['userID'];?>">All<span>(<?=$countAll?>)</span></button></div>
                        <div class="col-auto"><button class="btn ratings-btn" type="button" id="fiveStar" value="<?php echo $val['userID'];?>"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><span>(<?=$countFive?>)</span></button></div>
                        <div class="col-auto"><button class="btn ratings-btn" type="button" id="fourStar" value="<?php echo $val['userID'];?>"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><span>(<?=$countFour?>)</span></button></div>
                        <div class="col-auto"><button class="btn ratings-btn" type="button" id="threeStar" value="<?php echo $val['userID'];?>"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><span>(<?=$countThree?>)</span></button></div>
                        <div class="col-auto"><button class="btn ratings-btn" type="button" id="twoStar" value="<?php echo $val['userID'];?>"><i class="fas fa-star"></i><i class="fas fa-star"></i><span>(<?=$countTwo?>)</span></button></div>
                        <div class="col-auto"><button class="btn ratings-btn" type="button" id="oneStar" value="<?php echo $val['userID'];?>"><i class="fas fa-star"></i><span>(<?=$countOne?>)</span></button></div>
                    </div>
                </div>

                <!--Start ng loop-->
                <div class="dito" id="dito">
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
                </div>
                <!--End ng loop-->
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/ratings.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <!--galing kay rose-->
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBznw3cpC9HWF3r7VOvfpTpFaC_3s2lPMY"></script> -->
    
    <!--galing kay michelle-->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_OEm-GWs2MhtvKaabGYVDO1wOE6LI9i0"></script>

    <script src="assets/js/customer-viewstore-location.js"></script>
    <script>
        $('.back').click(function () { 
            window.history.back();
        });

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
    
        <?php if(isset($_SESSION['message'])) 
            { ?>
        Swal.fire({
            title: 'Thankyou!',
            text: '<?php echo $_SESSION['message']?>',
            icon: 'success',
            button: true
        });
        <?php 
        unset($_SESSION['message']);
            }
        ?> 

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