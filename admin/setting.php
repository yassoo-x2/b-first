<?php
    $dsn = 'mysql:host=localhost;dbname=b-first';
    $user = 'root';
    $pass = '';
    $option = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    );
    try{
        $con = new PDO($dsn, $user, $pass, $option);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
        echo 'F connect' .$e->getMessage();
    }

  // Check if the table exists
$tableName = 'login';
$stmt = $con->query("SHOW TABLES LIKE '$tableName'");
$tableExists = ($stmt->rowCount() > 0);

if (!$tableExists) {
    // Create the table
    $createTableQuery = "
        CREATE TABLE $tableName (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            usdt_address VARCHAR(255) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            balance FLOAT,
            groupID INT,
            Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";

    $con->exec($createTableQuery);

}
  // Check if the table exists
  $product = 'product';
  $stmtproduct = $con->query("SHOW TABLES LIKE '$product'");
  $productExists = ($stmtproduct->rowCount() > 0);
  
  if (!$productExists) {
      // Create the table
      $createproduct = "
          CREATE TABLE $product (
              id INT AUTO_INCREMENT PRIMARY KEY,
              server  VARCHAR(255) NOT NULL,
              product VARCHAR(255) NOT NULL,
              country VARCHAR(255) NOT NULL,
              shortcountry VARCHAR(255) NOT NULL,
              code VARCHAR(255) NOT NULL,
              cost FLOAT,


              Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
          )
      ";
  
      $con->exec($createproduct);
  
  }
    // Check if the table exists
    $country = 'country';
    $stmtcountry = $con->query("SHOW TABLES LIKE '$country'");
    $countryExists = ($stmtcountry->rowCount() > 0);
    
    if (!$countryExists) {
        // Create the table
        $createcountry = "
            CREATE TABLE $country (
                id INT AUTO_INCREMENT PRIMARY KEY,
                country VARCHAR(255) NOT NULL,
                shortcountry VARCHAR(255) NOT NULL,
                code VARCHAR(255) NOT NULL,
  
                Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
    
        $con->exec($createcountry);
    
    }

        // Check if the table exists
        $price = 'price';
        $stmtprice = $con->query("SHOW TABLES LIKE '$price'");
        $priceExists = ($stmtprice->rowCount() > 0);
        
        if (!$priceExists) {
            // Create the table
            $createprice = "
                CREATE TABLE $price (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    server VARCHAR(255) ,
                    product VARCHAR(255) ,
                    price FLOAT NOT NULL,
      
                    Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ";
        
            $con->exec($createprice);
        
        }


                // Check if the table exists
        $sms = 'sms';
        $stmtsms = $con->query("SHOW TABLES LIKE '$sms'");
        $smsExists = ($stmtsms->rowCount() > 0);
        
        if (!$smsExists) {
            // Create the table
            $creatsms = "
                CREATE TABLE $sms (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    userid INT,
                    orderid VARCHAR(255) ,
                    server VARCHAR(255) ,
                    product VARCHAR(255) ,
                    country VARCHAR(255) ,
                    price FLOAT NOT NULL,
                    phone VARCHAR(255) ,
                    sms VARCHAR(255) ,
                    status VARCHAR(255),
                    Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ";
        
            $con->exec($creatsms);
        
        }


                        // Check if the table exists
        $recharge = 'recharge';
        $stmtrecharge = $con->query("SHOW TABLES LIKE '$recharge'");
        $rechargeExists = ($stmtrecharge->rowCount() > 0);
        
        if (!$rechargeExists) {
            // Create the table
            $creatrecharge = "
                CREATE TABLE $recharge (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    userid INT,
                    currency VARCHAR(255) ,
                    txid VARCHAR(255) ,
                    value FLOAT NOT NULL,
                    Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ";
        
            $con->exec($creatrecharge);
        
        }
?>