<?php

require_once('assets/db.conn.php');
require_once('assets/helpers/user.php');
require_once("assets/classes/dbHandler.php");
require_once("assets/classes/Notifications.php");

$data = new Config();

$user = getUser($_SESSION['userID'], $conn);
$userID = $user['userID'];
$notificationClass = new Notifications();

// Customer
$userType = $user['user_type'];
if ($userType == 1)
    $notifications = $data->getUserNotifications($userID);
// Store
else
    $notifications = $data->getUserNotifications($userID, true);
?>

<li class="nav-item dropdown notification-ui show">
    <a class="nav-link dropdown-toggle notification-ui_icon notif-dropdown" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell"></i>
        <span class="<?= !empty($notifications) ? 'unread-notification' : '' ?>"></span>
    </a>
    <div class="dropdown-menu notification-ui_dd" aria-labelledby="navbarDropdown">
        <div class="notification-ui_dd-header">
            <b class="text-center">Notifications</b>
        </div>
        <div class="notification-ui_dd-content">
            <?php if (!empty($notifications)) { ?>
                <?php
                $maxNotif = 0;
                foreach ($notifications as $notification) {
                    if($maxNotif == 4)
                        continue;

                    $maxNotif++;
                    $msg = $notificationClass->getTypeDesc($notification);
                ?>
                    <a href="<?= $userType == 1 ? 'customer-my-order.php' : 'store-orders-all.php' ?>">
                        <div class="notification-list">
                            <div class="notification-list_img">
                                <img src="assets/img/products/<?php echo $notification['prod_image']; ?>" alt="user">
                            </div>
                            <div class="notification-list_detail">
                                <p><?= $notificationClass->getTypeDesc($notification) ?></p>
                                <p><?= $notification['orderID'] ?></p>
                                <p><small><?= $notification['transac_date'] ?></small></p>
                            </div>
                        </div>
                    </a>
                <?php } ?>
            <?php } else { ?>
                <div class="no-message-yet">
                    <p>No notifications yet</p>
                </div>
            <?php } ?>
        </div>
        <div class="notification-ui_dd-footer">
            <a href="notifications.php" class="view-all">View All</a>
        </div>
    </div>
</li>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    $('.notif-dropdown').click(function() {
        $('.notification-ui_dd').toggleClass('show', 'hide');
    });
</script>

<style>
    @import url(https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900);

    body {
        font-family: 'Source Sans Pro';
        background: #E6E9ED;
        min-height: 100vh;
        position: relative;
    }

    .notification-ui a:after {
        display: none;
    }

    .notification-ui_icon {
        position: relative;
    }

    .notification-ui_icon .unread-notification {
        display: inline-block;
        height: 7px;
        width: 7px;
        border-radius: 7px;
        background-color: #fea600;
        position: absolute;
        top: 7px;
        left: 12px;
    }

    @media (min-width: 900px) {
        .notification-ui_icon .unread-notification {
            left: 20px;
        }
    }

    .notification-ui_dd {
        padding: 0;
        border-radius: 10px;
        -webkit-box-shadow: 0 5px 20px -3px rgba(0, 0, 0, 0.16);
        box-shadow: 0 5px 20px -3px rgba(0, 0, 0, 0.16);
        border: 0;
        max-width: 400px;
    }

    @media (min-width: 900px) {
        .notification-ui_dd {
            min-width: 350px;
            position: absolute;
            left: -165px;
            top: 70px;
        }
    }

    .notification-ui_dd:after {
        content: "";
        position: absolute;
        top: -30px;
        left: calc(50% - 7px);
        border-top: 15px solid transparent;
        border-right: 15px solid transparent;
        border-bottom: 15px solid #fff;
        border-left: 15px solid transparent;
    }

    .notification-ui_dd .notification-ui_dd-header {
        border-bottom: 1px solid #ddd;
        padding: 15px;
    }

    .notification-ui_dd .notification-ui_dd-header h3 {
        margin-bottom: 0;
    }

    .notification-ui_dd .notification-ui_dd-content {
        max-height: 516px;
        overflow: auto;
    }

    .notification-list {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: center;
        width: 100%;
        padding: 15px 0;
        margin: 0 20px;
        border-bottom: 1px solid #ddd;
    }

    .notification-list--unread {
        position: relative;
    }

    .notification-list--unread:before {
        content: "";
        position: absolute;
        top: 0;
        left: -25px;
        height: calc(100% + 1px);
        border-left: 2px solid #29B6F6;
    }

    .notification-list .notification-list_img img {
        height: 48px;
        width: 48px;
        object-fit: cover;
        border-radius: 50px;
        margin-right: 20px;
    }

    .notification-list .notification-list_detail p {
        width: 100%;
        margin-bottom: 5px;
        line-height: 1.2;
    }

    .notification-list .notification-list_feature-img img {
        height: 48px;
        width: 48px;
        border-radius: 5px;
        margin-left: 20px;
    }

    .notification-ui_dd-content a {
        color: #3e3d3d;
        text-decoration: none;
    }

    .notification-ui_dd-content a:hover {
        background-color: #f5f0f0;
    }

    .notification-ui_dd-footer .view-all{
        padding: 10px 15px;
        color: #fea600;
        text-decoration: none;
    }

    .notification-ui_dd-footer .view-all:hover{
        color: #d28e0f;
    }
</style>