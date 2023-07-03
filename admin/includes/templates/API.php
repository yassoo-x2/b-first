<?php
//get RUB / usd price
$apiUrl = 'https://api.freecurrencyapi.com/v1/latest?apikey=wRW0RvcCi08hP12G8WFynYPu8nvcG92cOEN7kXio';
if ($apiUrl == true) {
  
$response = file_get_contents($apiUrl);

if ($response === false) {
  echo 'Error: Failed to fetch data from the API.';
} else {
    $data = json_decode($response, true); // Decode the JSON response

    if (isset($data['data']['RUB'])) {
        $rubPrice = $data['data']['RUB'];

    } else {
     echo 'Error: RUB price data not found in the API response.';
    }
}
}
?>