<?php
   session_start();
   $pageTitle = 'Home' ;
   include 'init.php';
    
   


   if(isset($_SESSION['customer'])){

    if (@$_POST['country'] ){
        $country = $_POST['country'];
        $product = $_POST['product'];

        $stmt = $con->prepare("SELECT * FROM login WHERE username = '$user'");
        $stmt -> execute();
        $rows =$stmt->fetchAll();
        foreach ($rows as $row) {
        $balance = $row['balance'];
        }

            if (@$_POST['server']) {
                $server = $_POST['server'];
            }else{
                $server = 'general';
            }

            $priceproduct = $_POST['product'];
        $pricecheck = $con->prepare("SELECT * FROM price WHERE (product = '$priceproduct' OR product = 'general') AND (server = '$server' OR server = 'general')");
        $pricecheck->execute();

        $pricecount = $pricecheck->rowCount();
        if ($pricecount > 0) {
            $prices = $pricecheck->fetchAll();
            foreach ($prices as $price){ 
                $percent =$price['price'];
            }
        }


        $urlprice = 'https://5sim.net/v1/guest/prices?product=' . $product;
        $optionsprice = [
            'http' => [
                'header' => "Accept: application/json\r\n",
            ],
        ];
        
        $contextprice = stream_context_create($optionsprice);
        $resultprice = file_get_contents($urlprice, false, $contextprice);
        
        if ($resultprice === false) {
            echo 'Error: Failed to fetch data from the API.';
        } else {
            $responseprice = json_decode($resultprice, true); // Decode the JSON response
        
            if (isset($responseprice[$product])) {
                $productData = $responseprice[$product];
                
                foreach ($productData as $countrylist => $countryData) {
                    $maxcost = null;


                    foreach ($countryData as $virtualNumber => $values) {
                        if (isset($values['cost'])) {
                            $cost = $values['cost'];
        
                            if ($maxcost === null || $cost > $maxcost) {
                                $maxcost = $cost / $rubPrice;
                                $mycost =$maxcost +($maxcost * $percent);
                            }
                        }
                    }
                }
            }
        }

    if($balance < $mycost ){
        header('location:home.php?nobalance=ok');
        exit;
    }else{

    
        $token = 'eyJhbGciOiJSUzUxMiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE3MTcxNzA3MzUsImlhdCI6MTY4NTYzNDczNSwicmF5IjoiNTM5NTg0OTFiYmRhNTY2YWIzMDRkOTNiZTY5NmYyYWQiLCJzdWIiOjI1NjQ3NX0.gI9uuv1UO-S7iRXIPCtco51QaRjw1WMRjUWeAl5jYWuJAKxMO-X0gG4aGIRgXxxur1eOgBo--hUxR92quvhgJF85tK91f88Rq1OcMCkMLl2hc2nZBMqqVMVAF2AnVyyKdK3aJYe0TC0Qg1oJJ0lpQEe1vApTaDbLv1ewII4o5YIDmy07-3jPRBK2kRvCAIbf6z5nSB3GnMrSghImso0lDr3biYZQnuw1EzoeZIB75GlnlB-7-N9WwDLEI5KssvK5rQiUhE2aIw63L7qmKPJtBlAEq2IZqVYHGt53EuXZA-Qu5-SovmAZmEOn3L1yB_lQEn3EoExv15zz5c3WB1vDaQ';

        $url = 'https://5sim.net/v1/user/buy/activation/'.$country.'/any/'.$product;
     
        
        $options = [
            'http' => [
                'header' => "Authorization: Bearer $token\r\n" .
                            "Accept: application/json\r\n"
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        if ($result == false) {
            header('location:home.php?nonumber');

        }elseif($result == 'no free phones'){
            header('location:home.php?nonumber');

        }
        elseif (empty($result) ) {
            echo 'no resault';
        }
         else {
            // Process the API response
             $response = json_decode($result, true);
            if (is_array($response)) {
                $response['price'];
                $price = $response['price']/$rubPrice;
                $price = $price + ($price * $percent);
                $myprice = ceil($price * 100)/100;

                $string = $response['expires'];
                $dateTime = strtotime( $string. ' ' . 'UTC');
                $expires = date('Y-m-d H:i:s',strtotime('+1 hour', $dateTime));

                $id = $response['id'];
                $product = $response['product'];
                $country = $response['country'];
                $phone = $response['phone'];
                $sms = $response['sms'];
                $newbalance =$balance - $myprice;
                $updatebalance = $con->prepare("UPDATE login SET balance = '$newbalance'  WHERE username = '$user'  ");
                $updatebalance->execute();

                $insertnumber = $con->prepare("INSERT INTO sms (userid,orderid,server,product,country,price,phone,sms,status)
                                VALUES ('$userid','$id','5sim','$product','$country','$myprice','$phone','$sms','pendding')");
                $insertnumber->execute();
              

?>
<div class="container">
    <div class="row">
        <div class="col label">
            <p>order ID</p>
            <p>product</p>
            <p>country</p>
            <p>price</p>
            <p>number</p>
            <p>sms</p>
            <p>expire time</p>
        </div>
        <div class="col">
            <p class="idorder"><?php echo $id ; ?></p>
            <p><?php echo $product; ?></p>
            <p><?php echo $country; ?></p>
            <p class='price'><?php echo $myprice; ?></p>
            <p><?php echo $phone; ?></p>
            <p class="sms"><?php
                        if ($sms !='') {
                            echo $sms;
                        }else{echo 'null'; }  ?></p>
            <p class="expires" style="display:none"><?php echo $expires ; ?></p>
            <div class="progress">
                <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
        </div>
    </div>
    <div class="row d-flex justify-content-around">
        <button class="refresh btn btn-success">refresh</button>
        <button class="completed btn btn-primary">completed</button>
        <button class="cancel btn btn-danger">cancel</button>

    </div>
</div>

<?php

    }else{
        echo 'No Number now,pleas try again later';
    }
        }
    }
}



    include  $tpl . 'footer.php';      
} else{
         header('location: index.php');
}
ob_end_flush();