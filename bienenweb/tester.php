<?php
session_start();
require_once("pdo.php");

if (isset($_POST['device'])){
    $device=$_POST['device'];
    $time=$_POST['time'];

    $stmt = $pdo->prepare('INSERT INTO message
          (time,device)
          VALUES (:time, :device)');

    $stmt->execute(array(
      'time' => $_POST['time'],
      'device' => $_POST['device']
    ));
    $message_id=$pdo->LastInsertId();

    $sql = 'SELECT record_type_id,record_label FROM record_type';
    $type_array = array();
    foreach($pdo->query($sql) as $row){
        $type_array[$row['record_label']] = $row['record_type_id'];
    }

    foreach(array(
        "temperature_dht_22",
        "temperature_openscale",
        "temperature_bmp280",
        "status2",
        "humidity",
        "weight" ,
        "weight2",
        "pressure") as $record_type){
            $record_type_id=$type_array[$record_type];
            $stmt = $pdo->prepare('INSERT INTO measurement
                  (message_id,record_type_id,value)
                  VALUES (:message_id, :record_type_id, :value)');
            $stmt->execute(array(
              'message_id' => $message_id,
              'record_type_id' => $record_type_id,
              'value' => $_POST[$record_type]
            ));
        }

    $_SESSION['success'] = "Message ".$message_id." fetched";


    //apply transformatins and forward data to dashboard server
    $tempcoef=80/255;
    $tempoffset=-20;
    $pressurecoef=(1090-880)/65535;
    $pressureoffset=880;
    $weightcoef=250/65535;
    $weightoffset=-50;
    $humcoef=1/2.55;
    $data = array(
            "device" =>  $device,
            "station"=>  "null",
            "temperature_dht_22" =>  $_POST["temperature_dht_22"]*$tempcoef+$tempoffset,
            "temperature_openscale" =>  $_POST["temperature_openscale"]*$tempcoef+$tempoffset,
            "temperature_bmp280" =>  $_POST["temperature_bmp280"]*$tempcoef+$tempoffset,
            "status2" =>  $_POST["status2"],
            "humidity" =>  $_POST["humidity"]*$humcoef,
            "weight" =>  $_POST["weight"]*$weightcoef+$weightoffset,
            "weight2" =>  $_POST["weight2"]*$weightcoef+$weightoffset,
            "pressure" =>  $_POST["pressure"]*$pressurecoef+$pressureoffset
         );
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

    //header("Location: tester.php");
    return;
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Tester</title>
    </head>
    <body>
        <main>
            <h1>Tester</h1>
            <?php
                if (isset($_SESSION['success'])){
                    echo('<p>'.$_SESSION['success'].'</p>');
                }
             ?>

            <form method="post">
                <label for="device">Device</label>
                <input type="text" name="device" value=""><br>

                <label for="time">time</label>
                <input type="text" name="time" value=""><br>

                <label for="temperature_dht_22">temperature_dht_22</label>
                <input type="text" name="temperature_dht_22" value=""><br>

                <label for="temperature_openscale">temperature_openscale</label>
                <input type="text" name="temperature_openscale" value=""><br>

                <label for="temperature_bmp280">temperature_bmp280</label>
                <input type="text" name="temperature_bmp280" value=""><br>

                <label for="status2">status2</label>
                <input type="text" name="status2" value=""><br>

                <label for="humidity">humidity</label>
                <input type="text" name="humidity" value=""><br>

                <label for="weight">weight</label>
                <input type="text" name="weight" value=""><br>

                <label for="weight2">weight2</label>
                <input type="text" name="weight2" value=""><br>

                <label for="pressure">Pressure</label>
                <input type="text" name="pressure" value=""><br>
                <input type="submit" value="Add">
            </form>
            <a href="index.php">Back to index</a>

        </main>
    </body>
</html>
