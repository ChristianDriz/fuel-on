<?php


require_once('Notifications.php');

class DBHandler extends Notifications
{

    protected function connect()
    {
        try {
            //CHANGE THIS BEFORE UPLOADING IN HOSTING

            $username = "u887826340_db_fuelon";
            $password = "Schuzoo.1227";
            $dbh = new PDO('mysql:host=localhost;dbname=u887826340_db_fuelon', $username, $password);
            return $dbh;

            // $username = "root";
            // $password = "";
            // $dbh = new PDO('mysql:host=localhost;dbname=db_fuelon', $username, $password);
            // return $dbh;
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
}

class Config extends DBHandler
{
    public function addToCart($userID, $shopID, $prodID, $prodName, $quantity)
    {
        try {
            $stmt = $this->connect()->prepare('INSERT INTO tbl_carts(customerID, shopID, productID, product_name, quantity) VALUES(?, ?, ?, ?, ?);');

            $stmt->execute(array($userID, $shopID, $prodID, $prodName, $quantity));

            //echo "<script>alert('Added to cart successfully!');document.location='../../customer-cart.php'</script>";

        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //PANG ALERT PAG SUCCESS
    public function success($url, $message)
    {
        $_SESSION['message'] = $message;
        header('Location: ' . $url);
        exit();
    }

    //PANG ALERT FOR INFO
    public function info($url, $message)
    {
        $_SESSION['info_message'] = $message;
        header('Location: ' . $url);
        exit();
    }

    //ALERT FOR ERROR
    public function error($url, $message)
    {
        $_SESSION['error_message'] = $message;
        header('Location: ' . $url);
        exit();
    }

    //ALERT FOR VERIFIED
    public function verified($url, $message)
    {
        $_SESSION['verify_message'] = $message;
        header('Location: ' . $url);
        exit();
    }    

    public function orderIDCount($orderID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT COUNT(orderID) FROM tbl_transactions WHERE orderID = ?;');

            $stmt->execute(array($orderID));

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //pang display ng location ng mga shops
    public function displayLocation()
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_locations;');

            $stmt->execute();

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // public function checkOut($shopID, $customerID, $prodID, $prodName, $prodPrice, $quantity, $total, $orderID, $payment)
    // {
    //     // var_dump($total);
    //     // DateTime(1);
    //     $conn = $this->connect();
    //     try {
    //         $stmt = $conn->prepare('INSERT INTO tbl_transactions(shopID, customerID, productID, product_name, price, quantity, total, orderID, payment_method, transac_date) 
    //         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, now());');
    //         $stmt->execute(array($shopID, $customerID, $prodID, $prodName, $prodPrice, $quantity, $total, $orderID, $payment));

    //         $lastInsertedId =  $conn->lastInsertId();
    //         $this->createNotification($lastInsertedId, Notifications::TYPE_NEW_ORDER);
    //     } catch (\PDOException $e) {
    //         print "Error!: " . $e->getMessage() . "<br/>";
    //         die();
    //     }
    // }

    public function checkOut($shopID, $customerID, $prodID, $prodName, $prodPrice, $quantity, $total, $orderID, $payment, $date)
    {
        try {
            $stmt = $this->connect()->prepare('INSERT INTO tbl_transactions(shopID, customerID, 
            productID, product_name, price, quantity, total, 
            orderID, payment_method, transac_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);');
            $stmt->execute(array($shopID, $customerID, $prodID, $prodName, $prodPrice, $quantity, $total, $orderID, $payment, $date));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //creating notif 
    public function createNotif($shopID, $customerID, $orderID, $type, $date)
    {
        try {
            $stmt = $this->connect()->prepare('INSERT INTO tbl_notif (shopID, customerID, orderID, notif_type, notif_date) 
            VALUES (?, ?, ?, ?, ?);');
            $stmt->execute(array($shopID, $customerID, $orderID, $type, $date));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //getting all of the notif both users include read and unread
    public function getUsersAllNotif($userID, $usertype)
    {
        switch($usertype) {
            case '1';
                try {
                    $stmt = $this->connect()->prepare('SELECT * 
                    FROM tbl_notif 
                    WHERE customerID = ?
                    AND notif_type IN ("To Pickup", "Declined", "Completed")
                    ORDER BY notif_date DESC;');

                    $stmt->execute(array($userID));
                    return $stmt->fetchAll();
                } catch (\PDOException $e) {
                    print "Error!: " . $e->getMessage() . "<br/>";
                    die();
                }
                break;

            case '2';
                try {
                    $stmt = $this->connect()->prepare('SELECT * 
                    FROM tbl_notif 
                    WHERE shopID = ?
                    AND notif_type IN ("Ordered", "Cancelled", "Completed")
                    ORDER BY notif_date DESC;');

                    $stmt->execute(array($userID));
                    return $stmt->fetchAll();
                } catch (\PDOException $e) {
                    print "Error!: " . $e->getMessage() . "<br/>";
                    die();
                }
                break;
        }
    }

    //getting the name and images for the notif
    public function getDetailsNotif($orderID, $usertype)
    {
        switch ($usertype) {

            //customer
            case '1':
                try {
                    $stmt = $this->connect()->prepare('SELECT CONCAT(tbl_station.station_name, " ", tbl_station.branch_name) AS name, 
                    tbl_users.user_image, tbl_products.prod_image, 
                    tbl_transactions.customerID, tbl_transactions.shopID, tbl_transactions.productID, 
                    tbl_transactions.orderID 
                    FROM tbl_station, tbl_users, tbl_products, tbl_transactions 
                    WHERE tbl_users.userID = tbl_station.shopID 
                    AND tbl_users.userID = tbl_transactions.shopID 
                    AND tbl_products.productID = tbl_transactions.productID 
                    AND tbl_transactions.orderID = ? 
                    GROUP BY tbl_transactions.orderID');

                    $stmt->execute(array($orderID));
                    return $stmt->fetchAll();
                } catch (\PDOException $e) {
                    print "Error!: " . $e->getMessage() . "<br/>";
                    die();
                }
                break;

            //shop
            case '2':
                try {
                    $stmt = $this->connect()->prepare('SELECT CONCAT(tbl_users.firstname, " ", tbl_users.lastname) AS name, 
                    tbl_users.user_image, tbl_products.prod_image, 
                    tbl_transactions.customerID, tbl_transactions.shopID, tbl_transactions.productID, 
                    tbl_transactions.orderID FROM tbl_users, tbl_products, tbl_transactions 
                    WHERE tbl_users.userID = tbl_transactions.customerID 
                    AND tbl_products.productID = tbl_transactions.productID 
                    AND tbl_transactions.orderID = ? 
                    GROUP BY tbl_transactions.orderID;');

                    $stmt->execute(array($orderID));
                    return $stmt->fetchAll();
                } catch (\PDOException $e) {
                    print "Error!: " . $e->getMessage() . "<br/>";
                    die();
                }
                break;
        }
    }

    //to get the unread notif details of the user
    public function getUserUnreadNotif($userID, $usertype)
    {
        switch($usertype){
            //customer
            case '1':
                try {
                    $stmt = $this->connect()->prepare('SELECT * 
                    FROM tbl_notif 
                    WHERE customerID = ?
                    AND read_status = 0
                    AND notif_type IN ("To Pickup", "Declined", "Completed")
                    ORDER BY notif_date DESC;');

                    $stmt->execute(array($userID));
                    return $stmt->fetchAll();
                } catch (\PDOException $e) {
                    print "Error!: " . $e->getMessage() . "<br/>";
                    die();
                }
                break;

            //shop
            case '2':
                try {
                    $stmt = $this->connect()->prepare('SELECT * 
                    FROM tbl_notif 
                    WHERE shopID  = ?
                    AND read_status = 0
                    AND notif_type IN ("Ordered", "Cancelled")
                    ORDER BY notif_date DESC;');

                    $stmt->execute(array($userID));
                    return $stmt->fetchAll();
                } catch (\PDOException $e) {
                    print "Error!: " . $e->getMessage() . "<br/>";
                    die();
                }
                break;
        }
    }

    //to count the unread notif of the user
    public function countUserUnreadNotif($userID, $usertype)
    {
        switch ($usertype) {

            //Customer
            case '1':
                try {
                    $stmt = $this->connect()->prepare('SELECT COUNT(*) 
                    FROM tbl_notif 
                    WHERE customerID = ? 
                    AND read_status = 0
                    AND notif_type IN ("To Pickup", "Declined", "Completed")');

                    $stmt->execute(array($userID));
                    return $stmt->fetchColumn();
                } catch (\PDOException $e) {
                    print "Error!: " . $e->getMessage() . "<br/>";
                    die();
                }
                break;

            //Shop
            case '2':
                try {
                    $stmt = $this->connect()->prepare('SELECT COUNT(*) 
                    FROM tbl_notif 
                    WHERE shopID = ?
                    AND read_status = 0 
                    AND notif_type IN ("Ordered", "Cancelled")');
                    $stmt->execute(array($userID));
                    return $stmt->fetchColumn();
                } catch (\PDOException $e) {
                    print "Error!: " . $e->getMessage() . "<br/>";
                    die();
                }
                break;
        }
    }

    //Updating the read status of the notification
    public function updateNotifReadStatus($orderID, $notifDate)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_notif 
            SET read_status = 1 
            WHERE orderID = ? 
            AND notif_date = ?');

            $stmt->execute(array($orderID, $notifDate));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //PANG UPDATE NG QUANTITY SA PRODUCTS TABLE
    public function updateProdStock($stock, $productID)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_products SET quantity = ? WHERE productID = ?');

            $stmt->execute(array($stock, $productID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //PANG KUHA NG QUANTITY SA PRODUCTS TABLE
    public function quantity($productID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT quantity AS stocks FROM tbl_products WHERE productID = ?');
            $stmt->execute(array($productID));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function allFuelPerStation($shopID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_users.user_image, tbl_station.station_name, tbl_station.branch_name, tbl_fuel.*
            FROM tbl_users, tbl_station, tbl_fuel
            WHERE tbl_users.userID = tbl_station.shopID
            AND tbl_station.shopID = tbl_fuel.shopID
            AND tbl_fuel.shopID = ?
            ORDER BY tbl_fuel.date_updated DESC');
            $stmt->execute(array($shopID));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //pang kuha ng details ng shop para sa fuels
    public function customerGetStationforFuel($shopID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_users.user_image, tbl_station.station_name, tbl_station.branch_name
            FROM tbl_users, tbl_station
            WHERE tbl_users.userID = tbl_station.shopID 
            AND tbl_users.userID = ?');
            $stmt->execute(array($shopID));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function allProductsStore($stationID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_products WHERE shopID = ?');
            $stmt->execute(array($stationID));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function allProductsCustomer()
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_products WHERE quantity != 0');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //ALL PRODUCTS NA MAY STOCKS
    public function allProductsWithStock($station)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_products WHERE quantity != 0 AND shopID = ?');
            $stmt->execute(array($station));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //ALL PRODUCTS NA WALANG STOCKS
    public function allProductsNoStock($station)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_products WHERE quantity = 0 AND shopID = ?');
            $stmt->execute(array($station));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function soldProducts($shopID, $prodID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_transactions WHERE shopID = ? AND productID = ? AND order_status = "Completed";');
            $stmt->execute(array($shopID, $prodID));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function productsFuelAll()
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_users.user_image, tbl_station.station_name, tbl_station.branch_name, tbl_fuel.*
            FROM tbl_users, tbl_station, tbl_fuel
            WHERE tbl_users.userID = tbl_station.shopID
            AND tbl_station.shopID = tbl_fuel.shopID
            ORDER BY tbl_fuel.date_updated DESC');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // public function productsFuelAll()
    // {
    //     try {
    //         $stmt = $this->connect()->prepare('SELECT * FROM tbl_fuel ORDER BY date_updated DESC');
    //         $stmt->execute();
    //         return $stmt->fetchAll();
    //     } catch (\PDOException $e) {
    //         print "Error!: " . $e->getMessage() . "<br/>";
    //         die();
    //     }
    // }

    //RESERVED CODE
    // public function productsFuelAll()
    // {
    //     try {
    //         $stmt = $this->connect()->prepare('SELECT shopID, user_image, firstname, lastname, fuel_type, fuel_category, fuel_image, new_price, old_price, fuel_status, date_updated
    //         FROM tbl_users, tbl_fuel
    //         WHERE tbl_users.userID = tbl_fuel.shopID
    //         ORDER BY date_updated DESC');
    //         $stmt->execute();
    //         return $stmt->fetchAll();
    //     } catch (\PDOException $e) {
    //         print "Error!: " . $e->getMessage() . "<br/>";
    //         die();
    //     }
    // }


    //PANG SORT NG FUEL LOWEST PRICE
    public function productsFuelLowestPrice()
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_fuel ORDER BY new_price ASC');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //PANG SORT NG FUEL HIGHEST PRICE
    public function productsFuelHighestPrice()
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_fuel ORDER BY new_price DESC');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function productsFuelDiesel()
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_users.user_image, tbl_station.shopID, tbl_station.station_name, tbl_station.branch_name, tbl_fuel.* 
            FROM tbl_users, tbl_station, tbl_fuel
            WHERE tbl_users.userID = tbl_station.shopID
            AND tbl_station.shopID = tbl_fuel.shopID
            AND tbl_fuel.fuel_category = "Diesel" 
            ORDER BY new_price ASC');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function productsFuelUnleaded()
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_users.user_image, tbl_station.shopID, tbl_station.station_name, tbl_station.branch_name, tbl_fuel.* 
            FROM tbl_users, tbl_station, tbl_fuel
            WHERE tbl_users.userID = tbl_station.shopID
            AND tbl_station.shopID = tbl_fuel.shopID
            AND tbl_fuel.fuel_category = "Unleaded" 
            ORDER BY new_price ASC');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function productsFuelPremium()
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_users.user_image, tbl_station.shopID, tbl_station.station_name, tbl_station.branch_name, tbl_fuel.* 
            FROM tbl_users, tbl_station, tbl_fuel
            WHERE tbl_users.userID = tbl_station.shopID
            AND tbl_station.shopID = tbl_fuel.shopID
            AND tbl_fuel.fuel_category = "Premium" 
            ORDER BY new_price ASC');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // //pang serts ng fuel name
    // public function searchFuel($key){
    //     try{
    //         $stmt = $this->connect()->prepare('SELECT * FROM tbl_fuel WHERE fuel_type LIKE ? ORDER BY new_price ASC');
    //         $stmt->execute(array($key));
    //         return $stmt->fetchAll();
    //     }
    //     catch(\PDOException $e){
    //         print "Error!: " . $e->getMessage() . "<br/>";
    //         die();
    //     }
    // }

    public function lowestProductPrice()
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_products WHERE quantity != 0 ORDER BY price ASC');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function highestProductPrice()
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_products WHERE quantity != 0 ORDER BY price DESC');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function lowestProductPriceperstore($shopID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_products WHERE quantity != 0 AND shopID = ? ORDER BY price ASC');
            $stmt->execute(array($shopID));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function highestProductPriceperstore($shopID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_products WHERE quantity != 0 AND shopID = ? ORDER BY price DESC');
            $stmt->execute(array($shopID));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function allShops()
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_users.*, tbl_station.*
            FROM tbl_users, tbl_station
            WHERE tbl_users.userID = tbl_station.shopID
            AND user_type = 2 
            AND verified = 1');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function allBranch($shopID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT firstname, lastname FROM tbl_users WHERE user_type = 2 AND userID = ?');
            $stmt->execute(array($shopID));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //for cart
    public function getCartOneDetails($customerID, $prodID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_carts 
            WHERE customerID = ? 
            AND productID = ?;');

            $stmt->execute(array($customerID, $prodID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //for cart
    public function getCartProdQuantity($cartID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_products.productID, tbl_carts.quantity FROM tbl_products, tbl_carts WHERE tbl_products.productID = tbl_carts.productID AND cartID = ?;');

            $stmt->execute(array($cartID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //for cart
    public function getProdQuantityforCart($cartID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_products.productID, tbl_products.quantity 
            FROM tbl_products, tbl_carts 
            WHERE tbl_products.productID = tbl_carts.productID AND cartID = ?;');

            $stmt->execute(array($cartID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //for transac
    public function getProdQuantity($orderID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_products.productID, tbl_products.quantity 
            FROM tbl_products, tbl_transactions 
            WHERE tbl_products.productID = tbl_transactions.productID 
            AND orderID = ?;');

            $stmt->execute(array($orderID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //for transac
    public function getTransQuantity($orderID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_products.productID, tbl_transactions.quantity 
            FROM tbl_products, tbl_transactions 
            WHERE tbl_products.productID = tbl_transactions.productID 
            AND orderID = ?;');

            $stmt->execute(array($orderID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function getOrdProducts($orderID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_products, tbl_transactions WHERE tbl_products.productID = tbl_transactions.productID AND orderID = ?;');

            $stmt->execute(array($orderID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function viewFile($userID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM files WHERE store_id = ?;');

            $stmt->execute(array($userID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function getVerified($email)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_users WHERE email = ?;');

            $stmt->execute(array($email));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // public function getVerifiedUserType1($email)
    // {
    //     try {
    //         $stmt = $this->connect()->prepare('SELECT * FROM tbl_users WHERE email = ? AND user_type = 1;');

    //         $stmt->execute(array($email));

    //         return $stmt->fetchAll();
    //     } catch (\PDOException $e) {
    //         print "Error!: " . $e->getMessage() . "<br/>";
    //         die();
    //     }
    // }

    // public function getVerifiedUserType2($email)
    // {
    //     try {
    //         $stmt = $this->connect()->prepare('SELECT * FROM tbl_users WHERE email = ? AND user_type = 2;');

    //         $stmt->execute(array($email));

    //         return $stmt->fetchAll();
    //     } catch (\PDOException $e) {
    //         print "Error!: " . $e->getMessage() . "<br/>";
    //         die();
    //     }
    // }

    public function updateVerified($userID)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_users SET verified = 1 WHERE userID = ?;');

            $stmt->execute(array($userID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function updateOTP($otp, $userID)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_users SET otp_code = ? WHERE userID = ?;');

            $stmt->execute(array($otp, $userID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function deleteProducts($prodID)
    {
        try {
            $stmt = $this->connect()->prepare('DELETE FROM tbl_products WHERE productID = ?;');

            $stmt->execute(array($prodID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //pangkuha ng lahat ng naging order ni customer
    public function CustomerAllOrder($customerID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * 
            FROM tbl_transactions
            WHERE customerID = ? 
            GROUP BY orderID
            ORDER BY transac_date DESC;');

            $stmt->execute(array($customerID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // //extra code for testing
    // public function CustomerAllOrderss($customerID)
    // {
    //     try {
    //         $stmt = $this->connect()->prepare('SELECT * 
    //         FROM tbl_transactions
    //         WHERE customerID = ? 
    //         ORDER BY transac_date DESC;');

    //         $stmt->execute(array($customerID));

    //         return $stmt->fetchAll();
    //     } catch (\PDOException $e) {
    //         print "Error!: " . $e->getMessage() . "<br/>";
    //         die();
    //     }
    // }

    public function CustomerAllRatings($customerID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT rating, feedback, rating_date, station_name, branch_name, user_image 
            FROM tbl_users, tbl_shop_ratings, tbl_station
            WHERE tbl_users.userID = tbl_shop_ratings.shopID 
            AND tbl_station.shopID = tbl_shop_ratings.shopID
            AND tbl_shop_ratings.customerID = ?;');

            $stmt->execute(array($customerID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //pang get ng order ni customer based on order status
    public function customerOrderCount($customerID, $statusOne, $statusTwo)
    {
        try {
            $stmt = $this->connect()->prepare("SELECT * FROM tbl_transactions 
            WHERE customerID = ? 
            AND order_status = ? 
            OR customerID = ? 
            AND order_status = ? 
            GROUP BY orderID
            ORDER BY transacID DESC;");

            $stmt->execute(array($customerID, $statusOne, $customerID, $statusTwo));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //pang count ilan order ni customer based on order status
    public function OrderCountCustomer($status, $customerID)
    {
        try {
            $stmt = $this->connect()->prepare("SELECT COUNT(*)
            FROM ( 
                SELECT COUNT(*)
                FROM tbl_transactions 
                WHERE order_status = ? 
                AND customerID = ? 
                GROUP BY orderID 
            ) tbl_transactions;");

            $stmt->execute(array($status, $customerID));

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }


    //pang count ilan orders kay shop based on order status
    public function OrderCountShop($status, $shopID)
    {
        try {
            $stmt = $this->connect()->prepare("SELECT COUNT(*)
            FROM ( 
                SELECT COUNT(*)
                FROM tbl_transactions 
                WHERE order_status = ? 
                AND shopID = ? 
                GROUP BY orderID 
            ) tbl_transactions;");

            $stmt->execute(array($status, $shopID));

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //pangkuha ng station details sa transac
    public function customerGetShop($orderID)
    {
        try {
            $stmt = $this->connect()->prepare("SELECT tbl_station.shopID, tbl_station.station_name, 
            tbl_station.branch_name
            FROM tbl_station, tbl_transactions 
            WHERE tbl_station.shopID = tbl_transactions.shopID 
            AND tbl_transactions.orderID = ?;");

            $stmt->execute(array($orderID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //pangkuha ng customer details sa transac
    public function shopGetCustomer($orderID)
    {
        try {
            $stmt = $this->connect()->prepare("SELECT tbl_users.userID, tbl_users.firstname, 
            tbl_users.lastname, tbl_users.user_image, tbl_users.user_type
            FROM tbl_users, tbl_transactions 
            WHERE tbl_users.userID = tbl_transactions.customerID 
            AND tbl_transactions.orderID = ?;");

            $stmt->execute(array($orderID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // to show shop details in cart
    public function cartGetShop($shopID, $userID)
    {
        try {
            $stmt = $this->connect()->prepare("SELECT tbl_station.shopID, tbl_station.station_name, 
            tbl_station.branch_name
            FROM tbl_station, tbl_carts 
            WHERE tbl_station.shopID = tbl_carts.shopID 
            AND tbl_carts.shopID = ?
            AND tbl_carts.customerID = ?;");

            $stmt->execute(array($shopID, $userID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // // to show shop details in cart
    // public function cartGetShop($shopID, $userID)
    // {
    //     try {
    //         $stmt = $this->connect()->prepare("SELECT tbl_users.userID, tbl_users.firstname, 
    //         tbl_users.lastname, tbl_users.user_image 
    //         FROM tbl_users, tbl_carts 
    //         WHERE tbl_users.userID = tbl_carts.shopID 
    //         AND tbl_carts.shopID = ? 
    //         AND tbl_carts.customerID = ?;");

    //         $stmt->execute(array($shopID, $userID));

    //         return $stmt->fetchAll();
    //     } catch (\PDOException $e) {
    //         print "Error!: " . $e->getMessage() . "<br/>";
    //         die();
    //     }
    // }

    //to show per order ni customer based on order ID 
    public function customerOrders($orderID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_transactions.shopID, tbl_transactions.customerID, tbl_transactions.productID, 
            tbl_transactions.product_name, tbl_transactions.price, tbl_products.prod_image, 
            tbl_transactions.quantity, tbl_transactions.total, tbl_transactions.payment_method, 
            tbl_transactions.order_status, tbl_transactions.cancel_reason 
            FROM tbl_transactions, tbl_products 
            WHERE tbl_products.productID = tbl_transactions.productID 
            AND tbl_transactions.orderID = ?');

            $stmt->execute(array($orderID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function getOrders($orderID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM `tbl_transactions` 
            WHERE orderID = ?');

            $stmt->execute(array($orderID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // public function getInvoiceDate($orderID)
    // {
    //     try {
    //         $stmt = $this->connect()->prepare('SELECT * 
    //         FROM tbl_transactions 
    //         WHERE orderID = ?;');
    //         $stmt->execute(array($orderID));

    //         return $stmt->fetchAll();
    //     } catch (\PDOException $e) {
    //         print "Error!: " . $e->getMessage() . "<br/>";
    //         die();
    //     }
    // }
    
    // public function insertInvoiceDate($orderID)
    // {
    //     try {
    //         $stmt = $this->connect()->prepare('UPDATE tbl_transactions SET invoice_date = now() WHERE orderID = ?');
    //         $stmt->execute(array($orderID));

    //     } catch (\PDOException $e) {
    //         print "Error!: " . $e->getMessage() . "<br/>";
    //         die();
    //     }
    // }


    // //pang kuha ng all orders ni customer
    // public function customerAllOrders($customerID)
    // {
    //     try {
    //         $stmt = $this->connect()->prepare('SELECT tbl_transactions.shopID, tbl_transactions.customerID, tbl_transactions.productID, tbl_products.product_name, tbl_products.price, tbl_products.prod_image, tbl_transactions.quantity, tbl_transactions.total, tbl_transactions.payment_method, tbl_transactions.order_status FROM tbl_transactions, tbl_products WHERE tbl_products.productID = tbl_transactions.productID AND tbl_transactions.customerID = ? ORDER BY transac_date ASC');

    //         $stmt->execute(array($customerID));

    //         return $stmt->fetchAll();
    //     } catch (\PDOException $e) {
    //         print "Error!: " . $e->getMessage() . "<br/>";
    //         die();
    //     }
    // }

    //pang display ng lahat ng umorder sa shop
    public function shopAllOrders($shopID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * 
            FROM tbl_transactions 
            WHERE shopID = ? 
            GROUP BY orderID
            ORDER BY transac_date DESC;');

            $stmt->execute(array($shopID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function countCartProductsCheckout($shopID, $userID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT COUNT(cartID) FROM tbl_carts WHERE shopID = ? AND customerID = ? AND quantity > 0 AND checked = 1;');

            $stmt->execute(array($shopID, $userID));

            return $stmt->fetchColumn();

            //return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function countShopProducts($shopID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT COUNT(productID) FROM tbl_products WHERE shopID = ?;');

            $stmt->execute(array($shopID));

            return $stmt->fetchColumn();

            //return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function countShopProfit($shopID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_transactions WHERE order_status = "Completed" AND shopID = ?');

            $stmt->execute(array($shopID));

            //return $stmt->fetchColumn();

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function countShopSold($prodID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT COUNT(*) FROM tbl_transactions WHERE order_status = "Completed" AND productID = ?');

            $stmt->execute(array($prodID));

            return $stmt->fetchColumn();

            //return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function countFeedback($shopID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT COUNT(*) FROM tbl_shop_ratings WHERE shopID = ?');

            $stmt->execute(array($shopID));

            return $stmt->fetchColumn();

            //return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function countCritical($shopID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT COUNT(*) FROM tbl_products WHERE quantity < 10 AND shopID = ?');

            $stmt->execute(array($shopID));

            return $stmt->fetchColumn();

            //return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //All shops with most transac
    // public function AllshopsMostTransac()
    // {
    //     try {
    //         $stmt = $this->connect()->prepare("SELECT tbl_transactions.shopID, tbl_users.user_image, tbl_users.firstname, tbl_users.lastname, COUNT(*) AS Transaction 
    //         FROM tbl_transactions, tbl_users 
    //         WHERE tbl_transactions.shopID = tbl_users.userID 
    //         AND order_status = 'Completed' 
    //         GROUP BY shopID 
    //         ORDER BY Transaction DESC;");

    //         $stmt->execute();

    //         return $stmt->fetchColumn();
    //     } catch (\PDOException $e) {
    //         print "Error!: " . $e->getMessage() . "<br/>";
    //         die();
    //     }
    // }

    // public function countShopTransac()
    // {
    //     try {
    //         $stmt = $this->connect()->prepare("SELECT shopID, COUNT(*) AS Transaction 
    //         FROM tbl_transactions x 
    //         WHERE order_status = 'Completed' 
    //         GROUP BY shopID 
    //         ORDER BY Transaction DESC;");

    //         $stmt->execute();

    //         return $stmt->fetchColumn();
    //     } catch (\PDOException $e) {
    //         print "Error!: " . $e->getMessage() . "<br/>";
    //         die();
    //     }
    // }

    //pang check if meron na transac si customer and station before customer can rate the station
    public function checkTransacUser($shopID, $customerID)
    {
        try {
            $stmt = $this->connect()->prepare("SELECT COUNT(*) FROM tbl_transactions 
            WHERE shopID = ?
            AND customerID = ? 
            AND order_status = 'completed';");

            $stmt->execute(array($shopID, $customerID));

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //for admin side para mabilang ilan completed transac ni station
    public function countPerShopTransac($shopID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT COUNT(*)
            FROM ( 
                SELECT COUNT(*)
                FROM tbl_transactions 
                WHERE order_status = "Completed" 
                AND shopID = ? 
                GROUP BY orderID 
            ) tbl_transactions;');

            $stmt->execute(array($shopID));

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //for admin side para mabilang ilan completed transac ni customer
    public function countPerCustomerTransac($customerID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT COUNT(*)
            FROM ( 
                SELECT COUNT(*)
                FROM tbl_transactions 
                WHERE order_status = "Completed" 
                AND customerID = ? 
                GROUP BY orderID 
            ) tbl_transactions;');

            $stmt->execute(array($customerID));

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //pangbilang ng station sa order
    public function shopOrderCount($shopID, $statusOne, $statusTwo)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT *
            FROM tbl_transactions 
            WHERE shopID = ? 
            AND order_status = ? 
            OR shopID = ? 
            AND order_status = ? 
            GROUP BY orderID
            ORDER BY transac_date DESC;');

            $stmt->execute(array($shopID, $statusOne, $shopID, $statusTwo));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function shopOrdersCompleted($shopID, $status)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_transactions 
            WHERE shopID = ? 
            AND order_status = ?
            ORDER BY transac_date DESC');
            $stmt->execute(array($shopID, $status));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function salesReportSorted($from_date, $to_date, $userID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_transactions 
            WHERE transac_date BETWEEN ?
            AND ?
            AND shopID = ?
            AND order_status = "completed"
            ORDER BY transac_date DESC');
            $stmt->execute(array($from_date, $to_date, $userID));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //to divide shops in checkout
    public function divideShopsCheckout($userID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_carts 
            WHERE customerID = ? 
            AND checked = ? 
            AND quantity != 0 
            GROUP BY shopID;');

            $stmt->execute(array($userID, true));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //pang kuha ilan na laman ng cart
    public function cartTotalItems($customerID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT COUNT(*) FROM tbl_carts WHERE customerID = ?;');

            $stmt->execute(array($customerID));

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //pang kuha ilan natirang item in cart
    public function cartRemainingItems($customerID, $userID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT COUNT(*) FROM tbl_carts 
            WHERE customerID = ? 
            AND checked = 1 
            AND quantity = 0 
            OR customerID = ? 
            AND checked = 0;');
            $stmt->execute(array($customerID, $userID));

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //pang count ilan checked item sa cart kasama no quantity
    public function cartAllChecked($customerID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT COUNT(checked) 
            FROM tbl_carts 
            WHERE checked = 1 
            AND customerID = ?;');

            $stmt->execute(array($customerID));

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //to count all items in cart with quantity
    public function cartAllCheckedwithQuant($customerID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT COUNT(checked) 
            FROM tbl_carts 
            WHERE checked = 1 
            AND quantity != 0 
            AND customerID = ?;');

            $stmt->execute(array($customerID));

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function checkCart($prodID, $userID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_carts WHERE productID = ? AND customerID = ?');

            $stmt->execute(array($prodID, $userID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function checkRatings($userID, $station)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_shop_ratings WHERE customerID = ? AND shopID = ?');

            $stmt->execute(array($userID, $station));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function countRatings($station)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT COUNT(*) FROM tbl_shop_ratings WHERE shopID = ?');

            $stmt->execute(array($station));

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }


    public function countOneStar($station)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT COUNT(*) FROM tbl_shop_ratings WHERE shopID = ? AND rating = 1');

            $stmt->execute(array($station));

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function countTwoStar($station)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT COUNT(*) FROM tbl_shop_ratings WHERE shopID = ? AND rating = 2');

            $stmt->execute(array($station));

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function countThreeStar($station)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT COUNT(*) FROM tbl_shop_ratings WHERE shopID = ? AND rating = 3');

            $stmt->execute(array($station));

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function countFourStar($station)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT COUNT(*) FROM tbl_shop_ratings WHERE shopID = ? AND rating = 4');

            $stmt->execute(array($station));

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function countFiveStar($station)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT COUNT(*) FROM tbl_shop_ratings WHERE shopID = ? AND rating = 5');

            $stmt->execute(array($station));

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function viewRatings($userID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_users.user_image, tbl_users.firstname, tbl_users.lastname, tbl_shop_ratings.rating, tbl_shop_ratings.feedback, tbl_shop_ratings.rating_date FROM tbl_users, tbl_shop_ratings WHERE tbl_users.userID = tbl_shop_ratings.customerID AND shopID = ? ORDER BY rating_date DESC');

            $stmt->execute(array($userID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function viewRatingsFiveStar($userID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_users.user_image, tbl_users.firstname, tbl_users.lastname, tbl_shop_ratings.rating, tbl_shop_ratings.feedback, tbl_shop_ratings.rating_date FROM tbl_users, tbl_shop_ratings WHERE tbl_users.userID = tbl_shop_ratings.customerID AND shopID = ? AND rating = 5 ORDER BY rating_date DESC;');

            $stmt->execute(array($userID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function viewRatingsFourStar($userID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_users.user_image, tbl_users.firstname, tbl_users.lastname, tbl_shop_ratings.rating, tbl_shop_ratings.feedback, tbl_shop_ratings.rating_date FROM tbl_users, tbl_shop_ratings WHERE tbl_users.userID = tbl_shop_ratings.customerID AND shopID = ? AND rating = 4 ORDER BY rating_date DESC;');

            $stmt->execute(array($userID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function viewRatingsThreeStar($userID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_users.user_image, tbl_users.firstname, tbl_users.lastname, tbl_shop_ratings.rating, tbl_shop_ratings.feedback, tbl_shop_ratings.rating_date FROM tbl_users, tbl_shop_ratings WHERE tbl_users.userID = tbl_shop_ratings.customerID AND shopID = ? AND rating = 3 ORDER BY rating_date DESC;');

            $stmt->execute(array($userID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function viewRatingsTwoStar($userID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_users.user_image, tbl_users.firstname, tbl_users.lastname, tbl_shop_ratings.rating, tbl_shop_ratings.feedback, tbl_shop_ratings.rating_date FROM tbl_users, tbl_shop_ratings WHERE tbl_users.userID = tbl_shop_ratings.customerID AND shopID = ? AND rating = 2 ORDER BY rating_date DESC;');

            $stmt->execute(array($userID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function viewRatingsOneStar($userID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_users.user_image, tbl_users.firstname, tbl_users.lastname, tbl_shop_ratings.rating, tbl_shop_ratings.feedback, tbl_shop_ratings.rating_date FROM tbl_users, tbl_shop_ratings WHERE tbl_users.userID = tbl_shop_ratings.customerID AND shopID = ? AND rating = 1 ORDER BY rating_date DESC;');

            $stmt->execute(array($userID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function insertRating($userID, $shopID, $rating, $feedback, $ratingDate)
    {
        try {
            $stmt = $this->connect()->prepare('INSERT INTO tbl_shop_ratings(customerID, shopID, rating, feedback, rating_date) 
            VALUES (?, ?, ?, ?, ?)');
            $stmt->execute(array($userID, $shopID, $rating, $feedback, $ratingDate));
            // return $stmt->fetchAll();            
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function updateRating($rating, $feedback, $ratingDate, $userID, $shopID)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_shop_ratings 
            SET rating = ?, feedback = ?, rating_date = ?
            WHERE customerID = ? 
            AND shopID = ?;');

            $stmt->execute(array($rating, $feedback, $ratingDate, $userID, $shopID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function updateOrder($status, $orderID)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_transactions SET order_status = ? WHERE orderID = ?;');
            $stmt->execute(array($status, $orderID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function updateCancelledOrder($status, $reason, $orderID)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_transactions SET order_status = ?, cancel_reason = ? WHERE orderID = ?;');
            $stmt->execute(array($status, $reason, $orderID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function setCartQuantityZero($prodID)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_carts SET quantity = 0 
            WHERE productID = ?;');

            $stmt->execute(array($prodID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function setCartQuantityOne($prodID)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_carts 
            SET quantity = 1 
            WHERE productID = ?;');

            $stmt->execute(array($prodID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function setCartQuantityEqualtoStock($quantity, $prodID, $userID)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_carts SET quantity = ? WHERE productID = ? AND customerID = ?;');

            $stmt->execute(array($quantity, $prodID, $userID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function updateCart($quantity, $prodID, $userID, $checked = 0)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_carts SET quantity = ?, checked = ? WHERE productID = ? AND customerID = ?;');

            $stmt->execute(array($quantity, $checked, $prodID, $userID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        // var_dump($checked);
        // DateTime(1);
    }

    //to get the sales report date
    public function salesreportdate($shopID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT MIN(transac_date) AS From_date, MAX(transac_date) AS To_date 
            FROM tbl_transactions 
            WHERE shopID = ?');

            $stmt->execute(array($shopID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function getShopBillingReceipt($orderID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT trans.*, CONCAT(users.firstname, " " ,users.lastname) AS customerName 
                FROM tbl_transactions AS trans 
                LEFT JOIN tbl_users AS users ON users.userID = trans.customerID 
                WHERE orderID = ?');
            $stmt->execute(array($orderID));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function updateQuantity($quantity, $prodID)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_products SET quantity = ? WHERE productID = ?;');

            $stmt->execute(array($quantity, $prodID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function deleteFuel($fuelID)
    {
        try {
            $stmt = $this->connect()->prepare('DELETE FROM tbl_fuel WHERE fuelID = ?;');

            $stmt->execute(array($fuelID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //to display the added products in cart
    public function displayCart($userID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_carts.cartID, tbl_products.shopID, 
            tbl_products.productID, tbl_carts.product_name, tbl_products.description, 
            tbl_products.price, tbl_products.prod_image, tbl_carts.quantity 
            FROM tbl_products,tbl_carts,tbl_users 
            WHERE tbl_products.productID = tbl_carts.productID 
            AND tbl_users.userID = tbl_carts.customerID 
            AND tbl_carts.customerID = ?
            ORDER BY cartID DESC;');
            $stmt->execute(array($userID));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // public function CartwithStocks($prodID){
    //     try{
    //         $stmt = $this->connect()->prepare('SELECT tbl_carts.cartID, tbl_products.shopID, tbl_products.productID, tbl_carts.product_name, tbl_products.price, tbl_products.quantity FROM tbl_products,tbl_carts,tbl_users WHERE tbl_products.productID = tbl_carts.productID AND tbl_users.userID = tbl_carts.customerID AND tbl_carts.productID = ?;');
    //         $stmt->execute(array($prodID));
    //         return $stmt->fetchAll();
    //     }
    //     catch(\PDOException $e){
    //         print "Error!: " . $e->getMessage() . "<br/>";
    //         die();
    //     }
    // }

    //divide shop in cart
    public function divideShops($userID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_carts 
            WHERE customerID = ? 
            GROUP BY shopID
            ORDER BY cartID DESC;');

            $stmt->execute(array($userID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //sort products in cart depending on shop id
    public function sortCart($userID, $shopID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_carts.cartID, tbl_products.shopID, 
            tbl_products.productID, tbl_carts.product_name, tbl_products.description, 
            tbl_products.quantity AS stocks, tbl_products.price, 
            tbl_products.prod_image, tbl_carts.quantity, tbl_carts.checked 
            FROM tbl_products,tbl_carts,tbl_users 
            WHERE tbl_users.userID = tbl_carts.customerID 
            AND tbl_products.productID = tbl_carts.productID 
            AND  tbl_carts.customerID = ?
            AND tbl_carts.shopID = ?
            ORDER BY cartID DESC;');
            $stmt->execute(array($userID, $shopID));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //sort products in checkout depending on shop id
    public function sortCartCheckOut($userID, $shopID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_carts.cartID, tbl_products.shopID, tbl_products.productID, 
            tbl_products.quantity, tbl_carts.product_name, tbl_products.description, tbl_products.price, 
            tbl_products.prod_image, tbl_carts.quantity, tbl_carts.checked 
            FROM tbl_products,tbl_carts,tbl_users 
            WHERE tbl_users.userID = tbl_carts.customerID 
            AND tbl_products.productID = tbl_carts.productID 
            AND  tbl_carts.customerID = ? 
            AND tbl_carts.shopID = ? 
            AND tbl_carts.checked = ?
            AND tbl_carts.quantity > 0;');
            $stmt->execute(array($userID, $shopID, true));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //for checkbox in cart 
    public function selectAllFromCart($shopId, $userId, $checked, $type = '')
    {
        switch ($type) {
            case 'store':
                //for selecting all checkbox under store
                try {
                    $stmt = $this->connect()->prepare('UPDATE tbl_carts 
                    SET checked = ? 
                    WHERE shopID = ? 
                    AND customerID = ?;');
                    $stmt->execute(array($checked, $shopId, $userId));
                } catch (\PDOException $e) {
                    print "Error!: " . $e->getMessage() . "<br/>";
                    die();
                }
                break;
            case 'all':
                //selecting all products in cart
                try {
                    $stmt = $this->connect()->prepare('UPDATE tbl_carts 
                    SET checked = ? 
                    WHERE customerID = ?;');
                    return $stmt->execute(array($checked, $userId));
                } catch (\PDOException $e) {
                    print "Error!: " . $e->getMessage() . "<br/>";
                    die();
                }
                break;
        }
    }

    //to delete all items in cart
    public function deleteAllinCart($customerID)
    {
        try {
            $stmt = $this->connect()->prepare('DELETE FROM tbl_carts WHERE customerID = ? AND quantity >= 0 AND checked = 1;');

            $stmt->execute(array($customerID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //to delete one item in cart
    public function deleteOneinCart($cartID)
    {
        try {
            $stmt = $this->connect()->prepare('DELETE FROM tbl_carts WHERE cartID = ?;');
            $stmt->execute(array($cartID));


            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //to remove the product in cart after checking out
    public function removeProdAftrChkout($customerID)
    {
        try {
            $stmt = $this->connect()->prepare('DELETE FROM tbl_carts WHERE customerID = ? AND quantity != 0 AND checked = 1;');

            $stmt->execute(array($customerID));

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // public function CountCartChecked($userID)
    // {
    //     try {
    //         $stmt = $this->connect()->prepare('SELECT COUNT(checked) FROM tbl_carts WHERE checked = 1 AND customerID = ?;');
    //         $stmt->execute(array($userID));
    //         return $stmt->fetchAll();
    //     } catch (\PDOException $e) {
    //         print "Error!: " . $e->getMessage() . "<br/>";
    //         die();
    //     }
    // }

    public function insertFuel($fuel_type, $fuel_category, $image, $price, $fuel_status, $date, $userID)
    {
        try {
            $stmt = $this->connect()->prepare("INSERT INTO tbl_fuel(fuel_type, fuel_category, fuel_image, new_price, fuel_status, date_updated, shopID) 
            VALUES(?, ?, ?, ?, ?, ?, ?);");

            $stmt->execute(array($fuel_type, $fuel_category, $image, $price, $fuel_status, $date, $userID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function insertProducts($prodName, $desc, $image, $quantity, $price, $userID)
    {
        try {
            $stmt = $this->connect()->prepare('INSERT INTO tbl_products(product_name, description, prod_image, quantity, price, shopID) VALUES(?, ?, ?, ?, ?, ?);');

            $stmt->execute(array($prodName, $desc, $image, $quantity, $price, $userID));

            // echo "<script>alert('Product is added successfully!');document.location='../../view-store.php'</script>";

        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function updateFuel($fuelID, $fuelType)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_fuel SET fuel_type = ? WHERE fuelID = ?');
            $stmt->execute(array($fuelType, $fuelID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function updateFuelStatus($fuelID, $fuelStatus)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_fuel SET fuel_status = ? WHERE fuelID = ?');
            $stmt->execute(array($fuelStatus, $fuelID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function updateFuelPic($fuelID, $fuelType, $image)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_fuel SET fuel_type = ?, fuel_image = ? WHERE fuelID = ?');
            $stmt->execute(array($fuelType, $image, $fuelID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function updateFuelPrice($newPrice, $oldPrice, $date, $fuelID)
    {
        try {
            $stmt = $this->connect()->prepare("UPDATE tbl_fuel SET new_price = ?, old_price = ?, date_updated = ? 
            WHERE fuelID = ?");
            $stmt->execute(array($newPrice, $oldPrice, $date, $fuelID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function updateProdImage($prodName, $desc, $image, $quantity, $price, $prodID)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_products SET product_name = ?, description = ?, prod_image = ?, quantity = ?, price = ? WHERE productID = ?');
            $stmt->execute(array($prodName, $desc, $image, $quantity, $price, $prodID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function updateProducts($prodName, $desc, $quantity, $price, $prodID)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_products SET product_name = ?, description = ?, quantity = ?, price = ? WHERE productID = ?');
            $stmt->execute(array($prodName, $desc, $quantity, $price, $prodID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function oneProduct($prodID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_products WHERE productID = ?');
            $stmt->execute(array($prodID));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function oneCart($prodID, $userID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_carts WHERE productID = ? AND customerID = ?;');
            $stmt->execute(array($prodID, $userID));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function oneCustomer($userID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_users WHERE user_type = 1 AND userID = ?');
            $stmt->execute(array($userID));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function oneShop($userID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_users WHERE user_type = 2 AND userID = ?');
            $stmt->execute(array($userID));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function oneFuel($fuelID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_fuel WHERE fuelID = ?');
            $stmt->execute(array($fuelID));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }


    public function allUsers()
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_users WHERE user_type = 1');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function oneUser($id)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_users WHERE userID = ?');
            $stmt->execute(array($id));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function updateAccountsImage($userID, $firstname, $lastname, $phone_num, $image)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_users SET firstname = ?, lastname = ?, phone_num = ?, user_image = ? WHERE userID = ?');
            $stmt->execute(array($firstname, $lastname, $phone_num, $image, $userID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function updateAccounts($userID, $firstname, $lastname, $phone_num)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_users SET firstname = ?, lastname = ?, phone_num = ? WHERE userID = ?');
            $stmt->execute(array($firstname, $lastname, $phone_num, $userID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function updateStoreAccountsImage($userID, $firstname, $lastname, $phone_num, $image)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_users SET firstname = ?, lastname = ?, phone_num = ?, user_image = ? WHERE userID = ?');
            $stmt->execute(array($firstname, $lastname, $phone_num, $image, $userID));
            // $this->updateBusinessPermit($files, $size, $tmp, $userID);
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function updateStoreAccount($userID, $firstname, $lastname, $phone_num)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_users SET firstname = ?, lastname = ?, phone_num = ? WHERE userID = ?');
            $stmt->execute(array($firstname, $lastname, $phone_num, $userID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function updateShopDetailsWithPermit($filename, $station, $branch, $address, $tin, $opening, $closing, $shopID)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_station SET permit_name = ?, station_name = ?, branch_name = ?,
            station_address = ?, tin_number = ?, opening = ?, closing = ?
            WHERE shopID = ?');
            $stmt->execute(array($filename, $station, $branch, $address, $tin, $opening, $closing, $shopID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function updateShopDetails($station, $branch, $address, $tin, $opening, $closing, $shopID)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_station SET station_name = ?, branch_name = ?,
            station_address = ?, tin_number = ?, opening = ?, closing = ?
            WHERE shopID = ?');
            $stmt->execute(array($station, $branch, $address, $tin, $opening, $closing, $shopID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function getFeedback($shopID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_shop_ratings WHERE shopID = ?');
            $stmt->execute(array($shopID));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function editFeedback($shopID, $userID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_shop_ratings WHERE shopID = ? AND customerID = ?');
            $stmt->execute(array($shopID, $userID));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function getRatings($shopID)
    {
        try {
            $stmt = $this->connect()->prepare("SELECT COUNT(*) FROM tbl_shop_ratings WHERE shopID = ?;");

            $stmt->execute(array($shopID));

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function countCustomerRating($customerID)
    {
        try {
            $stmt = $this->connect()->prepare("SELECT COUNT(*) FROM tbl_shop_ratings WHERE customerID = ?;");

            $stmt->execute(array($customerID));

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // all users
    public function AdminAllUser()
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_users');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // all verified users
    public function superAdminUsers()
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_users 
            WHERE user_type = 1
             ORDER BY firstname ASC');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //all non verified users
    public function superAdminUsersnonVerified()
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_users WHERE user_type = 1 AND verified = 0');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //count of verified users
    public function countUsers()
    {
        try {
            $stmt = $this->connect()->prepare("SELECT COUNT(*) FROM tbl_users WHERE user_type = 1 AND verified = 1;");

            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //all stations that is verified
    public function superAdminShops()
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_users.*, tbl_station.* 
            FROM tbl_users, tbl_station 
            WHERE tbl_users.userID = tbl_station.shopID 
            AND user_type = 2 
            AND verified = 1 
            ORDER BY tbl_station.station_name ASC');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //station details
    public function shopDetails($shopID)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_users.*, tbl_station.* 
            FROM tbl_users, tbl_station 
            WHERE tbl_users.userID = tbl_station.shopID 
            AND tbl_users.userID = ?');
            $stmt->execute(array($shopID));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //to show data of stores that is pending for approval
    public function superAdminShopsPending()
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_users.*, tbl_station.*
            FROM tbl_users, tbl_station
            WHERE user_type = 2 
            AND verified = 0 
            AND tbl_users.userID = tbl_station.shopID
            ORDER BY tbl_station.stationID ASC');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //To count pending for approval stores
    public function countPending()
    {
        try {
            $stmt = $this->connect()->prepare("SELECT COUNT(verified) FROM `tbl_users` WHERE verified = 0 AND user_type = 2;");

            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //count for all shops thats is verified
    public function countShops()
    {
        try {
            $stmt = $this->connect()->prepare("SELECT COUNT(*) FROM tbl_users WHERE user_type = 2 AND verified = 1;");

            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function superAdminProducts()
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_products');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function countProds()
    {
        try {
            $stmt = $this->connect()->prepare("SELECT COUNT(*) FROM tbl_products;");

            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function countProdsinShop($shopID)
    {
        try {
            $stmt = $this->connect()->prepare("SELECT COUNT(*) FROM tbl_products WHERE shopID = ?;");

            $stmt->execute(array($shopID));

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function getchartData($shopID, $status, $frequency = 'Monthly')
    {
        try {
            $stmt = $this->connect()->prepare("SELECT MONTH(transac_date) as frequency, SUM(total) as averageSale 
            FROM tbl_transactions 
            WHERE shopID = ? 
            AND order_status = ?
            GROUP BY MONTH(transac_date)
            ORDER BY MONTH(transac_date) ASC");
            $stmt->execute(array($shopID, $status));
            $results = $stmt->fetchAll();
            $data = [];

            $rData = [];
            foreach ($results as $result) {
                $data['categories'][] = $result['frequency'];
                $rData[] = [$result['frequency'], (int)$result['averageSale']];
            }
            $data['series'][] = ['name' => 'Month', 'data' => $rData];

            return json_encode($data);
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //BACKUP CODE
    // public function registerStore($email, $fname, $lname, $phone, $password, $tin_num, $type, $mapLat, $mapLng, $files)
    // {
    //     $conn = $this->connect();
    //     try {
    //         $stmt = $conn->prepare('INSERT INTO tbl_users (email, firstname, lastname, phone_num, password, tin_num, user_type, map_lat, map_lng) 
    //         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);');
    //         $hashedPass = password_hash($password, PASSWORD_DEFAULT);
    //         $stmt->execute(array($email, $fname, $lname, $phone, $hashedPass, $tin_num, $type, $mapLat, $mapLng));
    //         $this->uploadBusinessPermit($files, $conn->lastInsertId());
    //         return  $conn->lastInsertId();
    //     } catch (\PDOException $e) {
    //         print "Error!: " . $e->getMessage() . "<br/>";
    //         die();
    //     }
    // }

    // public function uploadBusinessPermit($files, $userId)
    // {
    //     // connect to the database
    //     $conn = $this->connect();

    //     // Uploads files
    //     $filename = $files['myfile']['name'];

    //     // destination of the file on the server
    //     $destination = 'uploads/' . $filename;

    //     // get the file extension
    //     $extension = pathinfo($filename, PATHINFO_EXTENSION);

    //     // the physical file on a temporary uploads directory on the server
    //     $file = $files['myfile']['tmp_name'];
    //     $size = $files['myfile']['size'];

    //     if (!in_array($extension, ['pdf', 'docx', 'png', 'jpeg', 'jpg', 'doc'])) {
    //         // print "You file extension must be .zip, .pdf or .docx";
    //         $this->info("../../register-store.php", "You file extension must be .zip, .pdf or .docx");
    //         die();
    //     } elseif ($files['myfile']['size'] > 1000000) { // file shouldn't be larger than 1Megabyte
    //         // print "File too large";
    //         $this->info("../../register-store.php", "File too large");
    //         die();
    //     } else {
 
    //         if (file_exists($filename)) {
    //             chmod($filename, 0755); //Change the file permissions if allowed
    //             unlink($filename); //remove the file
    //         }

    //         // move the uploaded (temporary) file to the specified destination
    //         if (move_uploaded_file($file, $destination)) {
    //             try {
    //                 $stmt = $conn->prepare('INSERT INTO files (name, size, store_id) VALUES (?, ?, ?);');
    //                 $stmt->execute(array($filename, $size, $userId));
    //                 return  $conn->lastInsertId();
    //             } catch (\PDOException $e) {
    //                 print "Error!: " . $e->getMessage() . "<br/>";
    //                 die();
    //             }
    //         } else {
    //             echo "Failed to upload file.";
    //         }
    //     }
    // }

    public function superAdminFuels()
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_fuel');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function countFuels()
    {
        try {
            $stmt = $this->connect()->prepare("SELECT COUNT(*) FROM tbl_fuel");
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function downloadFile($shopID)
    {
        try {
            $stmt = $this->connect()->prepare("SELECT * FROM tbl_station WHERE shopID = $shopID");
            $stmt->execute();
            return $stmt->fetch();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    
    // public function downloadPermit($shopID)
    // {
    //     try {
    //         $stmt = $this->connect()->prepare("SELECT * FROM files WHERE id = ?");
    //         $stmt->execute(array($shopID));
    //         return $stmt->fetchAll();
    //     } catch (\PDOException $e) {
    //         print "Error!: " . $e->getMessage() . "<br/>";
    //         die();
    //     }
    // }

    public function updateStoreStatus($storeId, $type)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_users SET verified = ? WHERE userID = ?');
            return $stmt->execute(array($type, $storeId));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // public function myStoreLocation($userID)
    // {
    //     try {
    //         $stmt = $this->connect()->prepare('SELECT * FROM tbl_station WHERE shopID = ?');
    //         $stmt->execute(array($userID));
    //         return $stmt->fetchAll();
    //     } catch (\PDOException $e) {
    //         print "Error!: " . $e->getMessage() . "<br/>";
    //         die();
    //     }
    // }

    public function updateStoreLocation($mapLat, $mapLng, $shopID)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_users SET map_lat = ?, map_lang = ? WHERE userID = ?');
            return $stmt->execute(array($mapLat, $mapLng, $shopID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function getStoreLocations()
    {
        try {
            $stmt = $this->connect()->prepare('SELECT tbl_users.*, tbl_station.*
            FROM tbl_users, tbl_station
            WHERE tbl_users.userID = tbl_station.shopID
            AND tbl_users.user_type = 2
            AND tbl_users.verified = 1');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function getUserLocation($userID)
    {
        try {
            $stmt = $this->connect()->prepare("SELECT map_lat, map_lang FROM tbl_users WHERE userID = ?");
            $stmt->execute(array($userID));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function setUserLocation($mapLat, $mapLng, $userID)
    {
        try {
            $stmt = $this->connect()->prepare("UPDATE tbl_users SET map_lat = ?, map_lang = ? WHERE userID = ?");
            return $stmt->execute(array($mapLat, $mapLng, $userID));
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //the distance is only set to 5 kilometers
    public function getNearestStation($mapLat, $mapLng, $mapLat2)
    {
        try {
            $stmt = $this->connect()->prepare("SELECT *, ( 6371 * acos( cos( radians(?)) * cos(radians (map_lat)) * cos( radians(map_lang) - radians(?)) + sin(radians(?)) * sin( radians(map_lat)))) 
            AS distance 
            FROM tbl_users
            WHERE user_type = 2
            AND verified = 1
            HAVING distance < 5
            ORDER BY distance");
            $stmt->execute(array($mapLat, $mapLng, $mapLat));
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function getStoreData($storeId)
    {
        try {
            $stmt = $this->connect()->prepare("SELECT * FROM tbl_users WHERE userID = $storeId");
            $stmt->execute();
            return $stmt->fetch();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    //get chat list
    public function getChatList($userID)
    {
        // switch($usertype) {
        //     case '0';
                try {
                    $stmt = $this->connect()->prepare("SELECT user.userID, user.user_type, user.firstname, user.lastname, user.user_image, user.active_status, chats.* 
                    FROM tbl_users AS user
                    INNER JOIN tbl_chat_contents AS chats ON chats.receiverID = user.userID
                    WHERE chats.senderID = ?
                    GROUP BY user.userID
                    ORDER BY chats.created_at DESC");
                    $stmt->execute(array($userID));
                    return $stmt->fetchAll();
                } catch (\PDOException $e) {
                    print "Error!: " . $e->getMessage() . "<br/>";
                    die();
                }
        //         break;

        //     case '1';
        //         try {
        //             $stmt = $this->connect()->prepare("SELECT user.userID, user.firstname, user.lastname, user.user_image, user.active_status, chats.* 
        //             FROM tbl_users AS user
        //             INNER JOIN tbl_chat_contents AS chats ON chats.receiverID = user.userID
        //             WHERE chats.senderID = ?
        //             GROUP BY user.userID
        //             ORDER BY chats.created_at DESC");
        //             $stmt->execute(array($userID));
        //             return $stmt->fetchAll();
        //         } catch (\PDOException $e) {
        //             print "Error!: " . $e->getMessage() . "<br/>";
        //             die();
        //         }
        //         break;

        //     case '2':
        //         try {
        //             $stmt = $this->connect()->prepare("SELECT user.userID, user.firstname, user.lastname, user.user_image, user.active_status, chats.* 
        //             FROM tbl_users AS user
        //             INNER JOIN tbl_chat_contents AS chats ON chats.receiverID = user.userID
        //             WHERE chats.senderID = ?
        //             GROUP BY user.userID
        //             ORDER BY chats.created_at DESC");
        //             $stmt->execute(array($userID));
        //             return $stmt->fetchAll();
        //         } catch (\PDOException $e) {
        //             print "Error!: " . $e->getMessage() . "<br/>";
        //             die();
        //         }
        //         break;
        // }    
    }

    // public function createNotification($transactionId, $type)
    // {
    //     $conn = $this->connect();
    //     try {
    //         $stmt = $conn->prepare('INSERT INTO tbl_notifications (transaction_id, type) VALUES (?, ?);');
    //         $stmt->execute(array($transactionId, $type));
    //         return  $conn->lastInsertId();
    //     } catch (\PDOException $e) {
    //         print "Error!: " . $e->getMessage() . "<br/>";
    //         die();
    //     }
    // }

    public function getUserNotifications($userID, $isStore = false)
    {
        $trasUser = '';
        if ($isStore) {
            $trasUser = 'shopID';
            $notifTypes = [0, 1];
        } else {
            $trasUser = 'customerID';
            $notifTypes = [3, 4, 5];
        }

        $types =  implode(', ', $notifTypes);
        try {
            $stmt = $this->connect()->prepare("SELECT notif.*, trans.*, user.*, product.prod_image, 
                DATE_FORMAT(transac_date,'%m/%d/%Y %r') as transac_date, CONCAT(shop.firstname, ' ', shop.lastname) AS shopname 
                FROM tbl_transactions AS trans 
                LEFT JOIN tbl_users AS user ON user.userID = trans.customerID 
                LEFT JOIN tbl_users AS shop ON shop.userID = trans.shopID 
                LEFT JOIN tbl_products AS product ON product.productID = trans.productID 
                INNER JOIN tbl_notifications AS notif ON notif.transaction_id = trans.transacID 
                WHERE trans.$trasUser = $userID 
                AND notif.type IN ($types)
                ORDER BY trans.transacID DESC");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }


    public function checkUser($email, $password)
    {
        $stmt = $this->connect()->prepare('SELECT password FROM tbl_users WHERE email = ?;');
        $stmt->execute(array($email));

        if ($stmt->rowCount() > 0) {
            $hashedPass = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $checkPass = password_verify($password, $hashedPass[0]["password"]);

            if ($checkPass == false) {
                http_response_code(404);
                throw new Exception('incorrect password');
                die();
            } else if ($checkPass == true) {
                return json_encode(['success' => true]);
            }
        } else {
            http_response_code(404);
            throw new Exception('user not found');
            die();
        }
    }

    public function checkPassword($userID, $email)
    {
        try {
            $stmt = $this->connect()->prepare('SELECT password FROM tbl_users WHERE userID = ? AND email = ?');
            $stmt->execute(array($userID, $email));
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function updatePass($password, $userID, $email)
    {
        try {
            $stmt = $this->connect()->prepare('UPDATE tbl_users SET password = ? WHERE userID = ? AND email = ?');
            return $stmt->execute(array($password, $userID, $email));
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }  
    

    public function superAdmin()
    {
        try {
            $stmt = $this->connect()->prepare('SELECT * FROM tbl_users WHERE user_type = 0');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function addAdmin($email, $fname, $lname, $phone, $password, $type, $verified){

        try {
            $stmt = $this->connect()->prepare('INSERT INTO tbl_users(email, firstname, lastname, phone_num, password, user_type, verified) 
            VALUES(?, ?, ?, ?, ?, ?, ?);');
            $stmt->execute(array($email, $fname, $lname, $phone, $password, $type, $verified));

        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function checkAdminAccount()
    {
        try {
            $stmt = $this->connect()->prepare('SELECT email, phone_num FROM tbl_users WHERE user_type = 0');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // public function allShopDetails()
    // {
    //     try {
    //         $stmt = $this->connect()->prepare('SELECT * FROM tbl_station');
    //         $stmt->execute();
    //         return $stmt->fetchAll();
    //     } catch (\PDOException $e) {
    //         print "Error!: " . $e->getMessage() . "<br/>";
    //         die();
    //     }
    // }
}