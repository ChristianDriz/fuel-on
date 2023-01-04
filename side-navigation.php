    <?php
        if($userType == 1){
    ?>
    <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <li class="sidebar-brand"> <a href="customer-home.php"><i class="fas fa-home"></i><span class="icon-name">Home</span></a></li>
            <li class="sidebar-brand"> <a href="customer-map.php"><i class="fas fa-map-pin"></i><span class="icon-name">Map</span></a></li>
            <li class="sidebar-brand"> <a href="customer-view-allstation.php"><i class="fas fa-gas-pump"></i><span class="icon-name">Stations</span></a></li>
            <li class="sidebar-brand"> <a href="customer-products.php"><i class="fas fa-tags"></i><span class="icon-name">Products</span></a></li>
            <li class="sidebar-brand">
                <a href="customer-cart.php">
                    <i class="fas fa-shopping-cart"></i><span class="icon-name">Cart</span>
                </a>
                <?php 
                    $cartItemCount = $dbh->cartTotalItems($userID);
                    if ($cartItemCount != 0) { 
                ?>
                    <sup><?php echo $cartItemCount ?></sup>
                <?php
                    } 
                ?>
            </li>
            <li class="sidebar-brand"> 
                <a href="customer-my-order.php">
                    <i class="fas fa-shopping-bag"></i><span class="icon-name">My Orders</span>
                </a>
                <?php
                    $orderCounter = $dbh->AllOrdersCountCustomer($userID);
                    if($orderCounter != 0){
                ?>
                    <sup style="margin-left: 52px;"><?php echo $orderCounter ?></sup>
                <?php
                    }
                ?>
            </li>
            <li class="sidebar-brand"> <a href="customer-account-settings.php"><i class="fas fa-user-cog"></i><span class="icon-name">My Account</span></a></li>
        </ul>
    </div>
    <?php
        }elseif($userType == 2){
    ?>
    <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <li class="sidebar-brand"> <a href="store-home.php"><i class="fas fa-home"></i><span class="icon-name">Dashboard</span></a></li>
            <li class="sidebar-brand"> <a href="store-location.php"><i class="fas fa-map-marked-alt"></i><span class="icon-name">Location</span></a></li>
            <li class="sidebar-brand"> 
                <a href="store-orders-all.php">
                    <i class="fas fa-shopping-basket"></i><span class="icon-name">Orders</span>
                </a>
                <?php
                    $orderCounter = $dbh->AllOrdersCountShop($userID);
                    if($orderCounter != 0){
                ?>
                    <sup><?php echo $orderCounter ?></sup>
                <?php
                    }
                ?>
            </li>
            <li class="sidebar-brand"> <a href="store-myfuels.php"><i class="fas fa-gas-pump"></i><span class="icon-name">Fuels</span></a></li>
            <li class="sidebar-brand"> <a href="store-myproducts.php"><i class="fas fa-shopping-bag"></i><span class="icon-name">Products</span></a></li>
            <li class="sidebar-brand"> <a href="store-view-sales.php"><i class="fas fa-chart-bar"></i><span class="icon-name">View Sales</span></a></li>
            <li class="sidebar-brand"> <a href="store-view-feedback.php"><i class="fas fa-star-half-alt"></i><span class="icon-name">Reviews</span></a></li>
            <li class="sidebar-brand"> <a href="store-account-settings.php"><i class="fas fa-user-cog"></i><span class="icon-name">My Account</span></a></li>
        </ul>
    </div>
    <?php
        }elseif($userType == 0){
    ?>
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
                <a href="admin-store-approval.php">
                    <i class="fas fa-user-check"></i><span class="icon-name">Pending Approval</span>
                </a>
                <?php
                    $pending = $dbh->countPending(); 
                    if ($pending != 0) { 
                ?>
                    <sup><?=$pending ?></sup>
                <?php
                    } 
                ?>
            </li>
            <li class="sidebar-brand"> <a href="admin-account-settings.php"><i class="fas fa-user-cog"></i><span class="icon-name">My Account</span></a></li>
        </ul>
    </div>
    <?php
        }
    ?>