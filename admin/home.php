<?php
ob_start();
SESSION_start();
if (isset($_SESSION['adm'])) {
   $pageTitle = 'Home';
   include 'init.php';


   $token5sim = 'eyJhbGciOiJSUzUxMiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE3MTcxNzA3MzUsImlhdCI6MTY4NTYzNDczNSwicmF5IjoiNTM5NTg0OTFiYmRhNTY2YWIzMDRkOTNiZTY5NmYyYWQiLCJzdWIiOjI1NjQ3NX0.gI9uuv1UO-S7iRXIPCtco51QaRjw1WMRjUWeAl5jYWuJAKxMO-X0gG4aGIRgXxxur1eOgBo--hUxR92quvhgJF85tK91f88Rq1OcMCkMLl2hc2nZBMqqVMVAF2AnVyyKdK3aJYe0TC0Qg1oJJ0lpQEe1vApTaDbLv1ewII4o5YIDmy07-3jPRBK2kRvCAIbf6z5nSB3GnMrSghImso0lDr3biYZQnuw1EzoeZIB75GlnlB-7-N9WwDLEI5KssvK5rQiUhE2aIw63L7qmKPJtBlAEq2IZqVYHGt53EuXZA-Qu5-SovmAZmEOn3L1yB_lQEn3EoExv15zz5c3WB1vDaQ';

        $url5sim = 'https://5sim.net/v1/user/profile';
     
        
        $options = [
            'http' => [
                'header' => "Authorization: Bearer $token5sim\r\n" .
                            "Accept: application/json\r\n"
            ]
        ];
        
        $context = stream_context_create($options);
        @$result = file_get_contents($url5sim, false, $context);
        
        if ($result == false) {
            echo 'Error: Failed to fetch number .';

        }elseif (empty($result) ) {
            echo 'no resault';
        }
         else {
            // Process the API response
             $response = json_decode($result, true);
            if (is_array($response)) {
                $balance5sim = $response['balance'];
            }
         }

         //--------sms hub//
         $tokensmshub = '96970U547e03747b2afdbc45a5e75600876459';
         $urlsmshub = 'https://smshub.org/stubs/handler_api.php?api_key=' . $tokensmshub . '&action=getBalance';
         $curlsmshub = curl_init($urlsmshub);
         curl_setopt($curlsmshub, CURLOPT_RETURNTRANSFER, true);
         $resultsmshub = curl_exec($curlsmshub);
         
         if ($resultsmshub !== false) {
             $smshubbalance = substr($resultsmshub, strpos($resultsmshub, ":") + 1);
              $balance;
         } else {
             echo "Failed to retrieve balance";
         }
         
         curl_close($curlsmshub);
         

?> <br>
<div class="container">
    <div class="row">
        <div class="col-sm card">
            <h4>5sim Balance</h4>
            <p><?php echo $balance5sim ?></p>
        </div>
        <div class="col-sm card">
            <h4>SMS Hub Balance</h4>
            <p><?php echo $smshubbalance ?></p>
        </div>
    </div>
</div>
<?php




   include  $tpl . 'footer.php';
} else {
   header('location: ../index.php');
}
ob_end_flush();
?>