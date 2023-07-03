<?php
ob_start();
SESSION_start(); 
   if(isset($_SESSION['adm'])){
    $pageTitle = 'products' ;
      include 'init.php';
if ($_SERVER['REQUEST_METHOD']=='POST') {
    if (isset($_POST['add_prod'])) {
        $product = $_POST['prod'];

            $imgname = $_FILES['img']['name'];
            $tempFilePath = $_FILES['img']['tmp_name'];
            echo $newname = $product.'.jpg';
            $destinationPath = 'images/'.$newname; // Specify the destination path where you want to save the photo
            move_uploaded_file($tempFilePath, $destinationPath);
        
            if (@$_POST['server']) {
                $server = $_POST['server'];
            }else{
                $server = 'general';
            }
        $pricecheck = $con->prepare("SELECT * FROM price WHERE (product = '$product' OR product = 'general') AND (server = '$server' OR server = 'general')");
        $pricecheck->execute();

        $pricecount = $pricecheck->rowCount();
        if ($pricecount > 0) {
            $prices = $pricecheck->fetchAll();
            foreach ($prices as $price){ 
                $percent =$price['price'];
            }
        }



        $url = 'https://5sim.net/v1/guest/prices?product=' . $product;
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
            $response = json_decode($result, true); // Decode the JSON response
        
            if (isset($response[$product])) {
                $productData = $response[$product];
                
                foreach ($productData as $country => $countryData) {
                    $mincost = null;


                    foreach ($countryData as $virtualNumber => $values) {
                        if (isset($values['cost'])) {
                            $cost = $values['cost'];
        
                            if ($mincost === null || $cost < $mincost) {
                                $mincost = $cost / $rubPrice ;
                                $mycost =$mincost +($mincost * $percent);
                                $myprice = ceil($mycost * 100)/100;


                            }
                        }
                    }
        
                    $stmt = $con->prepare("SELECT * FROM product WHERE country = '$country' AND product='$product'");
                    $stmt->execute();
                    $rowCount = $stmt->rowCount();


            
                    if ($rowCount > 0) {

                      
                    } else {
                        // Insert the data into the database
            
                        $insertStmt = $con->prepare("INSERT INTO product (server, product, country, cost) VALUES ('5sim','$product','$country', '$myprice')");
                        $insertStmt->execute();

                        $stmt0 = $con->prepare("UPDATE product
                        JOIN country ON product.country = country.country
                        SET product.shortcountry = country.shortcountry , product.code = country.code");
                        $stmt0->execute();
                        
                    }
                }
            } else {
                echo 'Error: Product data not found in the API response.';
            }
        }
        
    }
}

?>
<div class="container-fluid">


    <form action="" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col">
                <h1>
                    add product
                </h1>
                <input type="text" name="prod" />
                <input type="file" class="FilePond" name="img">
                <button type="submit" name="add_prod" class="btn btn-success">add</button>
            </div>
        </div>

    </form>

</div>
<?php




      
        
            include  $tpl . 'footer.php';  
       } else{
                    header('location: ../index.php');
                    exit();
                    }
                    ob_end_flush();
         ?>