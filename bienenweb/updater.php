<?php

//apply transformatins and forward data to dashboard server
$data = array("device" =>  "a",
              "station"=>  "stat",
              "temperature_dht_22" =>  20,
              "temperature_openscale" => 21,
              "temperature_bmp280" =>  22,
              "status2" =>  23,
              "humidity" =>  24,
              "weight" =>  25,
              "weight2" =>  26,
              "pressure" => 27);
$options = array(
    'http' => array(
    'method'  => 'POST',
    'content' => json_encode( $data ),
    'header'=>  "Content-Type: application/json\r\n" .
                "Accept: application/json\r\n" .
                "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJCaWVuZW5Ub2tlbjEiLCJ1c3IiOiJtaWNob3NsYXYifQ.IUWcyTMWEU_bmf_du3nUiV4oIs0bhXNpVlSMqfT6WEc\r\n"
));
$context  = stream_context_create( $options );
$result = file_get_contents( "https://api.thinger.io/v1/users/michoslav/buckets/Arduino1/data", false, $context );
$response = json_decode( $result );
//header("Location: updater.php");
//return;
 ?>

 <!DOCTYPE html>
 <html lang="en" dir="ltr">
     <head>
         <meta charset="utf-8">
         <title>Updater</title>
     </head>
     <body>
         <h1><?php echo $response; ?></h1>
     </body>
 </html>
