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

    require_once("assets/classes/dbHandler.php");
    $dbh = new Config();

    $stations = $dbh->superAdminShops();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON | (Admin) Station Owners</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Customer%20css%20files/customer-navigation.css">
    <link rel="stylesheet" href="assets/css/Admin%20css%20files/admin-table.css">
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css"> -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css"> -->
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
            <div class="container table-container" style="max-width: 1500px;">
                <div class="view-div">
                    <h4>Station Owners</h4>
                    <a class="btn" role="button" href="admin-declined-store.php">View Declined Station</a>
                </div>
                <div class="table-div">
                    <div class="table-responsive">
                        <table class="datatable table">
                            <thead>
                                <tr>
                                    <!-- <th></th> -->
                                    <th>Image</th>
                                    <th>Station</th>
                                    <th>Branch</th>
                                    <th>Station Address</th>
                                    <th>Owner</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>TIN</th>
                                    <!-- <th>Schedule</th> -->
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    foreach($stations as $shops){ 
                                ?>
                                <tr>
                                    <!-- <td></td> -->
                                    <td class="image-td">
                                        <div><img src="assets/img/profiles/<?php echo $shops['user_image']?>"></div>
                                    </td>
                                    <td><?=$shops['station_name']?></td>
                                    <td><?=$shops['branch_name']?></td>
                                    <td><?=$shops['station_address']?></td>
                                    <td><?=$shops['firstname']. ' ' . $shops['lastname']?></td>
                                    <td><?=$shops['email']?></td>
                                    <td><?=$shops['phone_num']?></td>
                                    <td><?=$shops['tin_number']?></td>
                                    <!-- <td>
                                    <?php
                                        //open hour
                                        $openTime = $shops['opening'];
                                        $createdate = date_create($openTime);
                                        $Timeopen = date_format($createdate, "h:i a");

                                        //close hour
                                        $closeTime = $shops['closing'];
                                        $createdate = date_create($closeTime);
                                        $Timeclose = date_format($createdate, "h:i a");

                                        if ($shops['opening'] && $shops['closing'] == "00:00:00"){
                                            echo "24 Hours Open";
                                        }
                                        else{
                                            echo "Open: " . $Timeopen . " " . "Close: " . $Timeclose;
                                        }
                                    ?>
                                    </td> -->
                                    <td class="action-td">
                                        <a class="btn btn-light" href="admin-viewmore-stores-allratings.php?shopID=<?=$shops['userID']?>">View</a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <!-- <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script> -->
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script>

        let admintable = new DataTable('.datatable', {
            responsive: {
                details: {
                    type: 'column',
                    target: 'tr'
                }
            },
            columnDefs: [ {
                className: 'dtr-control',
                orderable: false,
                targets:   0
            } ],
            order: [ 1, 'asc' ]
        });


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