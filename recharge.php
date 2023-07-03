<?php
   session_start();
   $pageTitle = 'Home' ;
   include 'init.php';
   if(isset($_SESSION['customer'])){
    if (isset($_POST['rech_address'])) {
    $address = $_POST['rech_address'];
    $myaddress = 'TPP5P5nGXQUMNMyERAkJC6caaSxti7iWFF';
    $apiUrl = 'https://api.trongrid.io/v1/accounts/'.$address.'/transactions/trc20?&contract_address=TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t&limit=20&only_from=true';

    $response = file_get_contents($apiUrl);
    
    
    $data = json_decode($response, true);
    
    if (isset($data['data']) && is_array($data['data'])) {
        $detail = $data['data'];
        foreach( $detail as $to){
            if($to['to'] === $myaddress){
                $value = $to['value']/1000000;
                $hash = $to['transaction_id'];
    
                $checkhash = $con->prepare("SELECT * FROM recharge WHERE txid ='$hash'");
                $checkhash->execute();
                $numhash = $checkhash->rowCount();
                if ($numhash == 0) {
                   
                $addbalance = $con->prepare("UPDATE login SET balance = balance + '$value' WHERE id = $userid");
                $addbalance->execute();
    
                $rechargeDetail = $con->prepare("INSERT INTO recharge (userid,currency,txid,value) VALUE ('$userid','USDT','$hash','$value')");
                $rechargeDetail->execute(); 
                }
            }
        }

    }
}
$myusdt = $con->prepare("SELECT usdt_address FROM login WHERE id=1 ");
$myusdt->execute();
$usdts = $myusdt->fetchAll();
?>
<div class="container recharge">
    <form action="" method="POST">

        <div class="row">
            <div class="col-md">
                <p>1. copy address below or scan QR code</p>
                <img src="admin/images/site/qrcode.jpg" alt="QR">
                <h5 class="address"><?php foreach($usdts as $usdt){ echo $usdt['usdt_address'];} ?></h5>
                <i class="fa-solid fa-clipboard" style="color: #00ffca;"></i>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <p>2. Send your mouny from your Wallet to address you copeid it</p>
            </div>

        </div>
        <div class="row">
            <div class="col-md">
                <p>3. Copy and past your recharge address in failed below</p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class=" input-box ">
                    <input class="form-control" id="" name="rech_address" type="text" placeholder=" ">
                    <span for="filterInput">recharge address</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <input type="submit" class="btn btn-primary" value="Done">
            </div>
        </div>

    </form>
</div>

<?php

    









    


      include  $tpl . 'footer.php';  
      
   } else{
            header('location: index.php');
   }
   ob_end_flush();