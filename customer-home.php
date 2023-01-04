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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | Home</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sarabun&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Share+Tech+Mono&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-home.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-navigation.css">
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
            <div class="container contener">
                <div class="sliders">
                    <div class="slider-head">
                        <a href="customer-view-allstation.php">
                            <h5>Gasoline Stations</h5>
                        </a>
                    </div>
                    <div class="slider-body">
                        <div class="slider-wrapper">
                        <?php
                            $records = $data->allShops();
                            foreach($records as $val){
                        ?>
                            <div class="inside-body-slider">
                                <a href="customer-viewstore-timeline.php?stationID=<?php echo $val['shopID']; ?>">
                                    <div class="image-div">
                                        <img src="assets/img/profiles/<?php echo $val['user_image'] ?>" alt="Gas Station Logo">
                                    </div>
                                    <div class="name-box">
                                        <p><?php echo $val['station_name'] ?> <?php echo $val['branch_name'] ?></p>
                                    </div>
                                </a>
                            </div>            
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
                <div class="search-div">
                    <div class="input-group">
                        <input class="form-control autocomplete" type="text" placeholder="Search fuel name or fuel type here..." id="search">
                        <button class="btn" type="button" id="searchbtn"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <div class="dito">
                    <div class="sort-div"><span>Sort by</span>
                        <div class="sort-options">
                            <button class="btn sort-btn active" type="button" id="all" value="all">All types</button>
                            <button class="btn sort-btn" type="button" id="diesel" value="diesel">Diesel</button>
                            <button class="btn sort-btn" type="button" id="unleaded" value="unleaded">Unleaded</button>
                            <button class="btn sort-btn" type="button" id="premium" value="premium">Premium</button>
                            <button class="btn sort-btn" type="button" id="racing" value="racing">Racing</button>
                        </div>
                    </div>
                    <div class="timeline-container">
                        <div class="display">
                            <?php
                                $allfuels = $data->productsFuelAll();
                                    foreach($allfuels as $fuel){ 
                            ?>
                            <div class="row g-0 justify-content-start feed-row">
                                <div class="col-12 feed-head-col">
                                    <div class="feed-head-img-div">
                                        <img src="assets/img/profiles/<?php echo $fuel['user_image'] ?>">
                                    </div>
                                    <div class="feed-head-name-div">
                                        <a href="customer-viewstore-timeline.php?stationID=<?php echo $fuel['shopID']; ?>">
                                            <?= $fuel['station_name'].' '.$fuel['branch_name'] ?>
                                        </a>
                                        <p><?php echo $fuel['station_address']?></p>
                                    </div>
                                </div>
                                <div class="col-12 feed-body-col">
                                    <div class="row g-0 justify-content-center">
                                        <?php
                                            $fueldata = $data->fuelDetails($fuel['shopID']); 
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
                                                <p class="date">as of <?php echo $dbh->dateconverter($fuels['date_updated'])?></p>
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
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/sortFuel.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <!-- <script src="assets/js/Search.js"></script> -->
    <script>
    $(document).ready(function(){
       $("#search").on("input", function(){
       	 var searchText = $(this).val();
        //  alert (searchText);

         if(searchText == "") return;
         $.post('assets/ajax/homesearch.php', 
         	     {
         	     	key: searchText
         	     },
         	   function(data, status){
                  $(".display").html(data);
         	});
       });
    
       $("#searchbtn").on("click", function(){
       	 var searchText = $("#search").val();
        // alert (searchText);
         if(searchText == "") return;
         $.post('assets/ajax/homesearch.php', 
         	     {
         	     	key: searchText
         	     },
         	   function(data, status){
                  $(".display").html(data);
         	 });
        });
    });

    var stars = document.querySelectorAll(".sort-btn");
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

    <?php
    if (isset($_SESSION['message'])) { ?>
        const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
        })

        Toast.fire({
        icon: 'success',
        title: '<?php echo $_SESSION['message'] ?>'
        })
    <?php
        unset($_SESSION['message']);
    }
    if (isset($_SESSION['verify_message'])) {
    ?>
        const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
        })

        Toast.fire({
        icon: 'success',
        title: '<?php echo $_SESSION['verify_message'] ?>'
        })
    <?php
        unset($_SESSION['verify_message']);
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