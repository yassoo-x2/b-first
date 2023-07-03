<?php
   session_start();
   $pageTitle = 'refresh' ;
   include 'admin/setting.php';
   $js = 'admin/design/js/';
   $jsme = 'design/js/';



   if(isset($_SESSION['customer'])){
    $user = $_SESSION['customer'];

    $page = isset($_GET['page']) ? $_GET['page'] : header('location:home.php');

    if($page ==='country'){
        if (@ $_POST['product'] ){
            $productName = $_POST['product'];
            $maxcost = $_POST['cost'];
    
        $selectcon = $con->prepare("SELECT  country,shortcountry,code FROM product WHERE product = '$productName'");
        $selectcon->execute();
        $country = $selectcon->fetchAll();
    ?>

<div class="list-group countrylist animate__animated animate__zoomIn" id="countrylist">
    <?php foreach ($country as $count){  ?>
    <form action="active.php" method="POST" class="buy-form">
        <div id="<?php echo $count['country'];?>" class="country-feild btn btn-secondary country_btn">
            <span class="fi fi-<?php echo $count['shortcountry'];?>"></span>
            <span name=<?php echo $count['code'];?> id="" class="country_sel"><?php echo $count['country'];?></span>
            <span style="display:none" class="prod_country"><?php echo $productName;?></span>
        </div>
    </form>

    <?php }?>
    <button id="showMoreBtn">Show More</button>
</div>

<?php
        }
    }
    


    elseif($page ==='refresh'){
//-----------------------------refresh sms
if (@$_POST['orderid'] || @$_GET['orderid'] ){
    $orderid = $_POST['orderid'] ? $_POST['orderid'] : $_GET['orderid'];
    $token = 'eyJhbGciOiJSUzUxMiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE3MTcxNzA3MzUsImlhdCI6MTY4NTYzNDczNSwicmF5IjoiNTM5NTg0OTFiYmRhNTY2YWIzMDRkOTNiZTY5NmYyYWQiLCJzdWIiOjI1NjQ3NX0.gI9uuv1UO-S7iRXIPCtco51QaRjw1WMRjUWeAl5jYWuJAKxMO-X0gG4aGIRgXxxur1eOgBo--hUxR92quvhgJF85tK91f88Rq1OcMCkMLl2hc2nZBMqqVMVAF2AnVyyKdK3aJYe0TC0Qg1oJJ0lpQEe1vApTaDbLv1ewII4o5YIDmy07-3jPRBK2kRvCAIbf6z5nSB3GnMrSghImso0lDr3biYZQnuw1EzoeZIB75GlnlB-7-N9WwDLEI5KssvK5rQiUhE2aIw63L7qmKPJtBlAEq2IZqVYHGt53EuXZA-Qu5-SovmAZmEOn3L1yB_lQEn3EoExv15zz5c3WB1vDaQ';
 
    $url = 'https://5sim.net/v1/user/check/'.$orderid;
 
    
    $options = [
        'http' => [
            'header' => "Authorization: Bearer $token\r\n" .
                        "Accept: application/json\r\n"
        ]
    ];
    
    $context = stream_context_create($options);
    @$result = file_get_contents($url, false, $context);
    
    if ($result != false) {
     // Process the API response
     $getsms = json_decode($result, true);
     if (is_array($getsms)) {
         $sms = $getsms['sms'];
         if (!empty($sms)) {
            $lastIndex = count($sms) - 1;
            $code = $sms[$lastIndex]['code'];
            echo $code;
 
             $updatecode = $con->prepare("UPDATE sms SET sms = '$code', status='completed'  WHERE orderid = '$orderid'  ");
             $updatecode->execute();
         }
         else{
             echo 'not get yet';
         }
     }
 }
    
 }
    }
    elseif($page === 'cancel'){
        if (@$_POST['orderid'] ){
            $orderid = $_POST['orderid'];
            $price = $_POST['price'];

            $token = 'eyJhbGciOiJSUzUxMiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE3MTcxNzA3MzUsImlhdCI6MTY4NTYzNDczNSwicmF5IjoiNTM5NTg0OTFiYmRhNTY2YWIzMDRkOTNiZTY5NmYyYWQiLCJzdWIiOjI1NjQ3NX0.gI9uuv1UO-S7iRXIPCtco51QaRjw1WMRjUWeAl5jYWuJAKxMO-X0gG4aGIRgXxxur1eOgBo--hUxR92quvhgJF85tK91f88Rq1OcMCkMLl2hc2nZBMqqVMVAF2AnVyyKdK3aJYe0TC0Qg1oJJ0lpQEe1vApTaDbLv1ewII4o5YIDmy07-3jPRBK2kRvCAIbf6z5nSB3GnMrSghImso0lDr3biYZQnuw1EzoeZIB75GlnlB-7-N9WwDLEI5KssvK5rQiUhE2aIw63L7qmKPJtBlAEq2IZqVYHGt53EuXZA-Qu5-SovmAZmEOn3L1yB_lQEn3EoExv15zz5c3WB1vDaQ';
         
            $url = 'https://5sim.net/v1/user/cancel/'.$orderid;
         
            
            $options = [
                'http' => [
                    'header' => "Authorization: Bearer $token\r\n" .
                                "Accept: application/json\r\n"
                ]
            ];
            
            $context = stream_context_create($options);
            echo $result = file_get_contents($url, false, $context);
            
            if ($result != false) {
             // Process the API response
             $cancel = json_decode($result, true);
             if (is_array($cancel)) {
                $status = $cancel['sms'];
                if (!empty($status)) {
                    $code = $status[0]['code'];
                    echo $code;
        
                    $updatecode = $con->prepare("UPDATE sms SET sms = '$code', status='completed'  WHERE orderid = '$orderid'  ");
                    $updatecode->execute();
                }
                else{
                    $updatecode = $con->prepare("UPDATE sms SET  status='canceled'  WHERE orderid = '$orderid'  ");
                    $updatecode->execute();
                    $getbalance = $con->prepare("SELECT balance FROM login WHERE username ='$user'");
                    $getbalance->execute();
                    $balances = $getbalance->fetchall();
                    foreach($balances as $balance){
                        $balance  = $balance['balance'];
                    }
                    $newbalance = $balance + $price;
                    $setbalance = $con->prepare("UPDATE login SET balance = '$newbalance' WHERE username = '$user' ");
                    $setbalance->execute();

                }
            }else{
                header('location:home.php?tryagain=true');
            }
            }
        }
    }

    ?>
<script src="<?php echo $js ?>jquery.min.js"></script>
<script src="<?php echo $jsme ?>main.js"></script>


<?php
      
} else{
         header('location: index.php');
}
ob_end_flush();