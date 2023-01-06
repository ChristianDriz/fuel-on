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
    <title>Fuel ON | (Admin) Station Approval</title>
    <link rel="icon" href="assets/img/fuelon_logo.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
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
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                            
                                    <th>Station</th>
                                    <th>Branch</th>
                                    <th>Address</th>
                                    <th>Owner</th>
                                    <th>Email</th>
                                    <th>Permit</th>
                                    <th>Location</th>
                                    <th>TIN</th>
                                    <th>Phone</th>     
                                    <th>Schedule</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($stations as $station) { 
                                ?>
                                <tr>
                                    
                                    <td><?= $station['station_name'] ?></td>
                                    <td><?= $station['branch_name'] ?></td>
                                    <td><?= $station['station_address'] ?></td>
                                    <td><?= $station['firstname'].' '.$station['lastname']?></td>
                                    <td><?= $station['email'] ?></td>
                                    <td class="permit-td">
                                        <?php
                                            $filetype = pathinfo($station['permit_name'], PATHINFO_EXTENSION);
                                            if($filetype == "pdf"){
                                        ?>                                        
                                        <a class="btn btn-light" role="button" target="_blank" href="uploads/<?php echo $station['permit_name']?>">View</a>
                                        <?php
                                            }else{
                                        ?>
                                        <button class="btn btn-light view-permit" value="<?php echo $station['permit_name']?>">View</button>
                                        <?php
                                            }
                                        ?>
                                    </td>
                                    <td class="permit-td">
                                        <a class="btn btn-light view-map" 
                                        data-lat = "<?= $station['map_lat'] ?>" 
                                        data-lng = "<?= $station['map_lang'] ?>" 
                                        data-name = "<?= $station['station_name'] . ' ' . $station['branch_name']?>"
                                        href="#locationModal" data-bs-toggle="modal">View</a>
                                    </td>                               
                                    <td><?= $station['tin_number'] ?></td>
                                    <td><?= $station['phone_num'] ?></td>
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
                                    <td class="button-td">
                                        <button data-type="1" 
                                        href="assets/includes/approveAction-inc.php?storeId=<?= $station['userID'] ?>&type=1" 
                                        class="btn btn-success accept-btn approval-btns" 
                                        data-bs-toggle="tooltip" 
                                        data-bss-tooltip="" 
                                        data-bs-placement="bottom" 
                                        title="Approve">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        <button data-type="2" 
                                        data-owner="<?= $station['firstname'].' '.$station['lastname']?>"
                                        href="assets/includes/approveAction-inc.php?storeId=<?= $station['userID'] ?>&type=2" 
                                        class="btn btn-danger decline-btn approval-btns" 
                                        data-bs-toggle="tooltip" 
                                        data-bss-tooltip="" 
                                        data-bs-placement="bottom" 
                                        title="Decline">
                                            <i class="fa fa-close"></i>
                                        </button>
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
                            <div class="modal-body">
                                <div id="maps" class="map"></div>
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
    <!--galing kay rose-->
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBznw3cpC9HWF3r7VOvfpTpFaC_3s2lPMY"></script> -->
    
    <!--galing kay michelle-->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_OEm-GWs2MhtvKaabGYVDO1wOE6LI9i0"></script>

    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <!-- <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script> -->
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
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
                targets: 0
            } ],
            order: [1, 'asc']
        });


        $(function() {
            $('.approval-btns').click(function() {
                const url = $(this).attr('href');
                const type = $(this).data('type');
                const owner = $(this).data('owner');

                Swal.fire({
                    title: 'Confirmation',
                    icon: 'question',
                    text: type == 1 ? 'Are you sure you want to approve this station?' : 'Are you sure you want to decline this station?',
                    showCancelButton: true,
                    confirmButtonColor:  type == 1 ? "#157347" : "#dc3545",
                    confirmButtonText: 'Yes',
                }).then((result) => {
                    if (result.isConfirmed) {
                        //if declined
                        if (type == 2){
                            const { value: reason } = Swal.fire({
                                text: 'Message to ' + owner,
                                input: 'textarea',
                                inputPlaceholder: 'Type the reason why you cancel this approval',
                                showCancelButton: true,
                                inputValidator: (value) => {
                                    return new Promise((resolve) => {
                                        if (value) {
                                            resolve()
                                            $.ajax({
                                                type: "GET",
                                                url,
                                                data: "reason=" +value,
                                                success: function(data) {
                                                    Swal.fire({
                                                        title: 'Station declined',
                                                        confirmButtonColor: "#157347",
                                                        confirmButtonText: 'OK',
                                                        icon: 'success'
                                                    }).then(() => {
                                                        location.reload();
                                                    });
                                                },
                                            });
                                        }else{
                                            resolve('You need to type a reason')
                                        }
                                    })
                                }
                            })
                        //if confirmed
                        }else{
                            $.ajax({
                                type: "GET",
                                url,
                                success: function(data) {
                                    Swal.fire({
                                        title: 'Station approved',
                                        confirmButtonColor: "#157347",
                                        confirmButtonText: 'OK',
                                        icon: 'success'
                                    }).then(() => {
                                        location.reload();
                                    });
                                },
                            });
                        }
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


        //modal view permit
        $('.view-permit').click(function () { 
            var img = $(this).val();

            Swal.fire({
                heightAuto: true,
                imageUrl: 'uploads/' + img,
                imageWidth: '100%',
                imageAlt: 'Custom image',
                showConfirmButton: false,
                padding: '0 10px',
                width: '35%',
            })
        });

    </script>
 
</body>

</html>