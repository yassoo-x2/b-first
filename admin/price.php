<?php
ob_start();
SESSION_start(); 
   if(isset($_SESSION['adm'])){
    $pageTitle = 'price' ;
      include 'init.php';



        
                $url = 'https://5sim.net/v1/guest/countries';
                $options = [
                    'http' => [
                        'header' => "Accept: application/json\r\n",
                    ],
                ];
                
                $context = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                
                if ($result === false) {
                    echo 'Error: Failed to fetch data from the API.';
                } else {
                    $countrylist = json_decode($result, true); // Decode the JSON response
                                        
                        foreach ($countrylist as $country => $countryData ) {
                           $short = array_keys($countryData['iso'])[0]; // Get the ISO code
                           $code = array_keys($countryData['prefix'])[0];

                
                            $stmt = $con->prepare("SELECT * FROM country WHERE country = '$country' ");
                            $stmt->execute();
                            $rowCount = $stmt->rowCount();
                    
                            if ($rowCount > 0) {
        
                              
                            } else {
                                // Insert the data into the database
                    
                                $insertStmt = $con->prepare("INSERT INTO country (country, shortcountry, code) VALUES ('$country', '$short','$code')");
                                $insertStmt->execute();
                                
                            }
                        }
                }

                if (isset($_POST['priceform'])) {
                    if ($_POST['server']) {
                        $server = $_POST['server'];
                    }else{
                        $server='general';
                    }
                    if ($_POST['product']) {
                        $product = $_POST['product'];
                    }else{
                        $product='general';
                    }

                    $price = $_POST['price'];
                    $pricecheck = $con->prepare("SELECT * FROM price WHERE product = '$product' AND server = '$server' ");
                    $pricecheck->execute();
                    $priceCount = $pricecheck->rowCount();
            
                    if ($priceCount > 0) {
                        $updateprice = $con->prepare("UPDATE price SET price = '$price'  WHERE product = '$product' AND server = '$server'  ");
                        $updateprice->execute();
                      
                    } else {
                        // Insert the data into the database
            
                        $insertprice = $con->prepare("INSERT INTO price (server, product, price) VALUES ('$server', '$product','$price')");
                        $insertprice->execute();
                        
                    }
                }
                ?>
<div class="container">
    <form action="price.php" method="POST" id="price" >
        <div class="row">
            <div class="col">
                <p>add your addetional price</p>
                <div class=" input-box ">
                        <input class="form-control" name="server" step="0.01" placeholder=" " />
                        <span for="filterInput">server</span>
                    </div>
                    <div class=" input-box ">
                        <input class="form-control" name="product" step="0.01" placeholder=" " />
                        <span for="filterInput">product</span>
                    </div>
                <div class=" input-box ">
                        <input class="form-control" name="price" step="0.01" placeholder=" " required/>
                        <span for="filterInput">you</span>
                    </div>
                <button type="submit" class="btn btn-success" name="priceform">go</button>
            </div>
        </div>
    </form>
</div>









<?php
      include  $tpl . 'footer.php';  
    
    
    
   }
   else{
                 header('location: ../index.php');
                 exit();
                 }
                 ob_end_flush();