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
    require_once("assets/includes/downloadFiles-inc.php");

    $dbh = new Config();

    $stations = $dbh->superAdminShopsPending();
    // echo $_SESSION['userID'];

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Fuel ON</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/fonts/line-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Admin%20css%20files/admin-navigation.css">
    <link rel="stylesheet" href="assets/css/Admin%20css%20files/admin-table.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand sticky-top" id="top">
        <div class="container"><a class="btn" role="button" id="menu-toggle" href="#menu-toggle"><i class="fa fa-bars"></i></a><a class="navbar-brand">&nbsp;<i class="fas fa-gas-pump"></i>&nbsp;FUEL ON</a>
            <ul class="navbar-nav">
                <li class="nav-item" id="mail">
                    <p class="badge message-counter"></p>
                    <a class="nav-link" href="chat-list.php"><i class="fas fa-envelope"></i></a>
                </li>
                <li class="nav-item dropdown" id="user"><a class="nav-link" data-bs-toggle="dropdown">
                        <div class="profile-div"><img src="assets/img/profiles/<?php echo $userpic ?>"></div>
                        <p><?php echo $username; ?></p>
                    </a>
                    <div class="dropdown-menu user"><a class="dropdown-item" href="assets/includes/logout-inc.php">Logout</a></div>
                </li>
            </ul>
        </div>
    </nav>
    <div id="wrapper">
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand"> <a href="admin-home-panel.php"><i class="fas fa-home"></i><span class="icon-name">Dashboard</span></a></li>
                <li class="sidebar-brand"> <a href="admin-normal-user-table.php"><i class="fas fa-users"></i><span class="icon-name">Normal Users</span></a></li>
                <li class="sidebar-brand"> <a href="admin-stores-table.php"><i class="fas fa-store"></i><span class="icon-name">Station Owners</span></a></li>
                <li class="sidebar-brand"> <a href="admin-table.php"><i class="fas fa-user-tie"></i><span class="icon-name">Admins</span></a></li>
                <li class="sidebar-brand"> <a href="admin-products-table.php"><i class="fas fa-shopping-basket"></i><span class="icon-name">Products</span></a></li>
                <li class="sidebar-brand"> <a href="admin-fuels-table.php"><i class="fas fa-gas-pump"></i><span class="icon-name">Fuels</span></a></li>
                <li class="sidebar-brand"> <a href="admin-store-locations.php"><i class="fas fa-map-marked-alt"></i><span class="icon-name">Station Locations</span></a></li>
                <li class="sidebar-brand"> 
                    <a class="actives" href="admin-store-approval.php"><i class="fas fa-user-check"></i><span class="icon-name">Pending Approval</span></a>
                    <?php 
                        $pending = $dbh->countPending();
                        if ($pending != 0) { ?>
                        <sup><?=$pending ?></sup>
                    <?php
                    } ?>
                </li>
                <li class="sidebar-brand"> <a href="admin-account-settings.php"><i class="fas fa-user-cog"></i><span class="icon-name">Settings</span></a></li>
            </ul>
        </div>
        <div class="page-content-wrapper">
            <div class="container">
                <h4>Pending for approval</h4>
                <?php 
                    if(empty($stations)){
                ?>
                <div class="nothing">
                    <h4>Nothing to approved</h4>
                </div>
                <?php
                    }else{
                ?>
                <div class="table-div">
                    <div>
                        <table class="datatable display nowrap">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>#</th>
                                    <th>First name</th>
                                    <th>Last name</th>
                                    <th>Station name</th>
                                    <th>Branch name</th>
                                    <th>Business Permit</th>
                                    <th>Map Location</th>
                                    <th>Action</th>
                                    <th>Address</th>
                                    <th>TIN</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Schedule</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($stations as $station) { 
                                ?>
                                <tr>
                                    <td></td>
                                    <td><?= $station['stationID'] ?></td>
                                    <td><?= $station['firstname'] ?></td>
                                    <td><?= $station['lastname'] ?></td>
                                    <td><?= $station['station_name'] ?></td>
                                    <td><?= $station['branch_name'] ?></td>
                                    <td class="permit-td">
                                        <a href="assets/includes/downloadFiles-inc.php?stationID=<?= $station['shopID'] ?>">Download</a>
                                    </td>
                                    <td class="view-loc-td">
                                        <a class="view-map" href="#locationModal" data-bs-toggle="modal">View Location</a>
                                    </td>
                                    <td class="button-td">
                                        <button data-type="1" href="assets/includes/approveAction-inc.php?storeId=<?= $station['userID'] ?>&type=1" class="btn btn-success accept-btn approval-btns" data-bs-toggle="tooltip" data-bss-tooltip="" data-bs-placement="bottom" title="Approve">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        <button data-type="2" href="assets/includes/approveAction-inc.php?storeId=<?= $station['userID'] ?>&type=2" class="btn btn-danger decline-btn approval-btns" data-bs-toggle="tooltip" data-bss-tooltip="" data-bs-placement="bottom" title="Decline">
                                            <i class="fa fa-close"></i>
                                        </button>
                                    </td>
                                    <td><?= $station['station_address'] ?></td>
                                    <td><?= $station['tin_number'] ?></td>
                                    <td><?= $station['phone_num'] ?></td>
                                    <td><?= $station['email'] ?></td>
                                    <td>
                                    <?php
                                        //open hour
                                        $openTime = $station['opening'];
                                        $createdate = date_create($openTime);
                                        $Timeopen = date_format($createdate, "h:i a");

                                        //close hour
                                        $closeTime = $station['closing'];
                                        $createdate = date_create($closeTime);
                                        $Timeclose = date_format($createdate, "h:i a");

                                        if ($station['opening'] && $station['closing'] == "00:00:00"){
                                            echo "24 Hours Open";
                                        }
                                        else{
                                            echo "Open: " . $Timeopen . " " . "Close: " . $Timeclose;
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
            <div>
                <div class="modal fade" role="dialog" tabindex="-1" id="locationModal">
                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div id="maps" class="map"></div>
                                <input type="hidden" id="mapLat" value="<?= $station['map_lat'] ?>">
                                <input type="hidden" id="mapLng" value="<?= $station['map_lang'] ?>">
                                <input type="hidden" id="name" value="<?= $station['station_name'] . ' ' . $station['branch_name']?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBznw3cpC9HWF3r7VOvfpTpFaC_3s2lPMY"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/map-admin.js"></script>
    <script type="text/javascript">

        let admintable = new DataTable('.datatable', {
            responsive: {
                details: {
                    type: 'column'
                }
            },
            columnDefs: [ {
                className: 'dtr-control',
                orderable: false,
                targets:   0
            } ],
            order: [ 1, 'asc' ]
        });


        $(function() {
            $('.approval-btns').click(function() {
                const url = $(this).attr('href');
                const type = $(this).data('type');

                Swal.fire({
                    title: 'Confirmation',
                    icon: 'question',
                    text: type == 1 ? 'Are you sure you want to approve this store?' : 'Are you sure you want to decline this store?',
                    showCancelButton: true,
                    confirmButtonColor:  type == 1 ? "#157347" : "#dc3545",
                    confirmButtonText: 'Yes',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "GET",
                            url,
                            success: function(data) {
                                Swal.fire({
                                    title: type == 1 ?  'Store approved' : 'Store declined',
                                    confirmButtonColor: "#157347",
                                    confirmButtonText: 'OK',
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            },
                        });
                    }
                })

            });
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