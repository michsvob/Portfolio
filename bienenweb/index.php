<?php
    require_once("pdo.php");
    require_once("constants.php");
    $sql = "SELECT (FROM_UNIXTIME(time)+ INTERVAL 2*3600 SECOND) as time,value,measurement.record_type_id FROM\n"
        . "            measurement\n"
        . "            LEFT JOIN message ON measurement.message_id=message.message_id\n"
        . "            WHERE time>(unix_timestamp(NOW())-60*60*24*7)\n"
        . "            ORDER BY time DESC\n"
        . "LIMIT 10000";

    $weight=[];
    $time_w=[];
    $time_t=[];
    $time_p=[];
    $time_h=[];
    $temper_1=[];
    $temper_2=[];
    $temper_3=[];
    $press=[];
    $hum=[];
    foreach($pdo->query($sql) as $row){
        if ($row["record_type_id"]==6){
            array_push($weight,floatval($row["value"])*$WEIGHTCOEF+$WEIGHTOFFSET);
            array_push($time_w,$row["time"]);
        }
        if ($row["record_type_id"]==1){
            array_push($temper_1,floatval($row["value"])*$TEMPCOEF+$TEMPOFFSET);
            array_push($time_t,$row["time"]);
        }
        if ($row["record_type_id"]==2){
            array_push($temper_2,floatval($row["value"])*$TEMPCOEF+$TEMPOFFSET);
        }
        if ($row["record_type_id"]==3){
            array_push($temper_3,floatval($row["value"])*$TEMPCOEF+$TEMPOFFSET);
        }
        if ($row["record_type_id"]==8){
            array_push($press,floatval($row["value"])*$PRESSURECOEF+$PRESSUREOFFSET);
            array_push($time_p,$row["time"]);
        }
        if ($row["record_type_id"]==5){
            array_push($hum,floatval($row["value"])*$HUMCOEF);
            array_push($time_h,$row["time"]);
        }
    }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
        <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../css/style.css" type="text/css" media="all" />
        <title>Bienenweb</title>
    </head>
    <body>
        <header class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1> Bienenweb</h1>
            </div>
        </header>

        <nav class="navbar navbar-expand-sm bg-dark navbar-dark sticky-top">
            <a class="navbar-brand" href="../index.html">MS</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul data-toggle="collapse" data-target=".navbar-collapse.show" class="navbar-nav" >
                <li><a class="nav-link active" href="#weight_s">Gewicht</a></li>
                <li><a class="nav-link" href="#temp_s">Temperatur</a></li>
                <li><a class="nav-link" href="#hum_s">Feuchtigkeit</a></li>
                <li><a class="nav-link" href="#press_s">Luftdruck</a></li>
            </ul>
            </div>
        </nav>

        <main class="container">
            <div class="row">
                <section id="weight_s" class= "col-md-12">
                    <h2>Gewicht</h2>
                    <div id="weight_plot" style="width:80%;height:400px;"></div>
                </section>
                <section id="temp_s" class= "col-md-12">
                    <h2>Temperatur</h2>
                    <div id="temp_plot" style="width:80%;height:400px;"></div>
                </section>
                <section id="hum_s" class= "col-md-12">
                    <h2>Relative Feuchtigkeit</h2>
                    <div id="hum_plot" style="width:80%;height:400px;"></div>
                </section>
                <section id="press_s" class= "col-md-12">
                    <h2>Luftdruck</h2>
                    <div id="press_plot" style="width:80%;height:400px;"></div>
                </section>
            </div>
        </main>

        <script type="text/javascript">
            var weight = {
              x: <?php echo json_encode($time_w); ?>,
              y: <?php echo json_encode($weight); ?>,
              mode: 'lines+markers',
              type: 'scatter'
            };
            var temperature_1 = {
              x: <?php echo json_encode($time_t); ?>,
              y: <?php echo json_encode($temper_1); ?>,
              name: 'DHT_22',
              mode: 'lines+markers',
              type: 'scatter'
            };
            var temperature_2 = {
              x: <?php echo json_encode($time_t); ?>,
              y: <?php echo json_encode($temper_2); ?>,
              name: 'openscale',
              mode: 'lines+markers',
              type: 'scatter'
            };
            var temperature_3 = {
              x: <?php echo json_encode($time_t); ?>,
              y: <?php echo json_encode($temper_3); ?>,
              name: 'BMP280',
              mode: 'lines+markers',
              type: 'scatter'
          };
            var pressure = {
              x: <?php echo json_encode($time_p); ?>,
              y: <?php echo json_encode($press); ?>,
              mode: 'lines+markers',
              type: 'scatter'
            };
            var humidity = {
              x: <?php echo json_encode($time_h); ?>,
              y: <?php echo json_encode($hum); ?>,
              mode: 'lines+markers',
              type: 'scatter'
            };

            TESTER = document.getElementById('weight_plot');
            Plotly.newPlot( TESTER, [weight], {
            margin: { t: 0 } } );

            TEMPLOT = document.getElementById('temp_plot');
            Plotly.newPlot( TEMPLOT, [temperature_1,temperature_2,temperature_3], {
            margin: { t: 0 } } );

            PRESSPLOT = document.getElementById('press_plot');
            Plotly.newPlot( PRESSPLOT, [pressure], {
            margin: { t: 0 } } );

            HUMPLOT = document.getElementById('hum_plot');
            Plotly.newPlot( HUMPLOT, [humidity], {
            margin: { t: 0 } } );
        </script>

    </body>
</html>
