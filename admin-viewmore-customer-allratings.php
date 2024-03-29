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
    elseif($userType == 1)
    { 
        header('location: index.php');
    }
}
else{
    header('location: index.php');
}

if(isset($_GET['userID'])){
    $customerID = $_GET['userID'];
}
else{
    $customerID = 0;
}

require_once("assets/classes/dbHandler.php");
$dbh = new Config();

$customer = $dbh->oneCustomer($customerID);
$allratings = $dbh->CustomerAllRatings($customerID);
$alltransac = $dbh->countPerCustomerTransac($customerID);
$count = $dbh->countCustomerRating($customerID);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | (Admin) Customer Ratings</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/material-icons.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-navigation.css">
    <link rel="stylesheet" href="assets/css/Admin%20css%20files/admin-viewmore-stores-allratings.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
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
                if(empty($customer)){
                    include 'no-data.php';
                }else{
            ?>
            <div class="container details-container">
                <h4>Normal Users Details</h4>
                <?php
                    foreach($customer as $user){
                ?>
                <div class="col-12 col-name">
                    <div class="dib">
                        <div class="image-div"><img src="assets/img/profiles/<?php echo $user['user_image']?>"></div>
                        <div class="details-dibb">
                            <div class="name-div">
                                <p class="name-p"><?php echo $user['firstname'].' '.$user['lastname']?></p>
                            </div>
                            <div class="details-div">
                                <div class="div-div">
                                    <p><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none">
                                            <path d="M3 3H5L5.4 5M7 13H17L21 5H5.4M7 13L5.4 5M7 13L4.70711 15.2929C4.07714 15.9229 4.52331 17 5.41421 17H17M17 17C15.8954 17 15 17.8954 15 19C15 20.1046 15.8954 21 17 21C18.1046 21 19 20.1046 19 19C19 17.8954 18.1046 17 17 17ZM9 19C9 20.1046 8.10457 21 7 21C5.89543 21 5 20.1046 5 19C5 17.8954 5.89543 17 7 17C8.10457 17 9 17.8954 9 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>Completed Transaction: <?php echo $alltransac?>
                                    </p>
                                    <p><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none">
                                            <path d="M11.0489 2.92707C11.3483 2.00576 12.6517 2.00576 12.9511 2.92707L14.4697 7.60083C14.6035 8.01285 14.9875 8.29181 15.4207 8.29181H20.335C21.3037 8.29181 21.7065 9.53143 20.9228 10.1008L16.947 12.9894C16.5965 13.244 16.4499 13.6954 16.5838 14.1074L18.1024 18.7812C18.4017 19.7025 17.3472 20.4686 16.5635 19.8992L12.5878 17.0107C12.2373 16.756 11.7627 16.756 11.4122 17.0107L7.43647 19.8992C6.65276 20.4686 5.59828 19.7025 5.89763 18.7812L7.41623 14.1074C7.5501 13.6954 7.40344 13.244 7.05296 12.9894L3.07722 10.1008C2.2935 9.53143 2.69628 8.29181 3.665 8.29181H8.57929C9.01251 8.29181 9.39647 8.01285 9.53034 7.60083L11.0489 2.92707Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>Rated Store: <?php echo $count?>
                                    </p>
                                </div>
                                <div class="div-div">
                                    <p><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none">
                                            <path d="M3 8L10.8906 13.2604C11.5624 13.7083 12.4376 13.7083 13.1094 13.2604L21 8M5 19H19C20.1046 19 21 18.1046 21 17V7C21 5.89543 20.1046 5 19 5H5C3.89543 5 3 5.89543 3 7V17C3 18.1046 3.89543 19 5 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg><?php echo $user['email']?>
                                    </p>
                                    <p><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none">
                                            <path d="M3 5C3 3.89543 3.89543 3 5 3H8.27924C8.70967 3 9.09181 3.27543 9.22792 3.68377L10.7257 8.17721C10.8831 8.64932 10.6694 9.16531 10.2243 9.38787L7.96701 10.5165C9.06925 12.9612 11.0388 14.9308 13.4835 16.033L14.6121 13.7757C14.8347 13.3306 15.3507 13.1169 15.8228 13.2743L20.3162 14.7721C20.7246 14.9082 21 15.2903 21 15.7208V19C21 20.1046 20.1046 21 19 21H18C9.71573 21 3 14.2843 3 6V5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg><?php echo $user['phone_num']?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="message-div">
                        <?php
                            if($user['verified'] == 0){
                        ?>
                        <a class="btn non-verified-user" role="button">
                        <?php
                            }else{
                        ?>    
                        <a class="btn" role="button" href="chat-box.php?userID=<?=$user['userID']?>&userType=<?=$user['user_type']?>">
                        <?php
                            }
                        ?>    
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none">
                                <path d="M8 10H8.01M12 10H12.01M16 10H16.01M9 16H5C3.89543 16 3 15.1046 3 14V6C3 4.89543 3.89543 4 5 4H19C20.1046 4 21 4.89543 21 6V14C21 15.1046 20.1046 16 19 16H14L9 21V16Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>Message
                        </a>
                    </div>
                </div>
                <div class="nav-div">
                    <ul class="nav">
                        <li class="nav-item"><a class="nav-link active" href="admin-viewmore-customer-allratings.php?userID=<?=$user['userID']?>">All Ratings</a></li>
                        <li class="nav-item"><a class="nav-link" href="admin-viewmore-customer-alltransac.php?userID=<?=$user['userID']?>">All Transactions</a></li>
                    </ul>
                </div>
                <div class="here">
                <?php
                    }
                    if(empty($allratings)){
                ?>
                <h5 class="no-rate">Nothing has been rated yet</h5>
                <?php
                    }else{
                ?>
                <div class="table-div">
                    <div class="table-responsive">
                        <table class="datatable table">
                            <thead>
                                <tr>
                                    <!-- <th></th> -->
                                    <th>Image</th>
                                    <th>Station name</th>
                                    <th>Feedback</th>
                                    <th>Rating Date</th>
                                    <th>Star Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($allratings as $ratings){

                                        $date = $ratings['rating_date'];
                                        $createdate = date_create($date);
                                        $new_date = date_format($createdate, "M d, Y h:i a");
                                ?>
                                <tr>
                                    <!-- <td></td> -->
                                    <td class="image-td">
                                        <div><img src="assets/img/profiles/<?php echo $ratings['user_image']?>"></div>
                                    </td>
                                    <td><?php echo $ratings['station_name'].' '.$ratings['branch_name']?></td>
                                    <td><?php echo $ratings['feedback']?></td>
                                    <td><?php echo $new_date?></td>
                                    <td class="star-rating-div">
                                    <?php
                                        $star = 0;
                                        while($star < $ratings['rating']){
                                    ?>
                                            <i class="fas fa-star"></i>
                                    <?php
                                        $star++;
                                        }
                                    ?>
                                    </td>
                                </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
                    }
                ?>
                </div>
            </div>
            <?php
                }
            ?>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <!-- <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script> -->
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="assets/js/admin-viewmore-customer.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <script src="assets/js/table.js"></script>
    <script>
        var nav = document.querySelectorAll(".nav-btn");
        nav.forEach(button => {
            button.addEventListener("click",()=> {
                resetActive();
                button.classList.add("active");
            })
        })

        function resetActive(){
            nav.forEach(button => {
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


        $('.non-verified-user').click(function () { 
        Swal.fire({
            title: "Oops...",
            text: "You can't message this user because this account is not verified yet.",
            icon: "info",
            confirmButtonColor: '#fea600',
            })
        });
    </script>
</body>

</html>