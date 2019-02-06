<?php
  require 'php/db_config.php';

  if (mysqli_connect_errno()) {
     echo 'Error: Could not connect to database.  Please try again later.';
     exit;
  }

  // Fetch the temps
  $query2 = "SELECT min(A.tempF) as 'MIN', 
                    avg(A.tempF) as 'AVG', 
                    max(A.tempF) as 'MAX' 
             from t_readings A, t_zones B
             where A.zoneIndex = B.zoneIndex and B.zoneName = 'GARAGE' and A.pollingTime >= now() - INTERVAL 1 DAY";
  $result2 = $mysqli->query($query2);
  $num_results2 = $result2->num_rows;
  for ($i=0; $i <$num_results2; $i++) {
    $row2 = $result2->fetch_assoc();
    $phpGarageTemp_min = $row2['MIN'];
    $phpGarageTemp_avg = $row2['AVG'];
    $phpGarageTemp_max = $row2['MAX'];
  }
  $result2->free();
  $mysqli->close();
?>

<head>
  <title>Fallbrooke Temps - Garage</title>
  <link rel="stylesheet" type="text/css" href="styles/reset.css">
  <link rel="stylesheet" type="text/css" href="styles/style.css">
  <link href="https://fonts.googleapis.com/css?family=Major+Mono+Display" rel="stylesheet">
  
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>
<!-- The two scripts below allow for export info from the chart -->
<!--  <script src="https://code.highcharts.com/stock/modules/exporting.js"></script> -->
<!--  <script src="https://code.highcharts.com/stock/modules/export-data.js"></script> -->

</head>

<body onload="timedRefreshDateTime()">

  <!-- Header -->
  <nav class="header">
    <ul>
      <li><a href="index.php"><div class="tooltip"><span class="tooltiptext">Home</span>HOME</div></a></li>
      <li><a href="inside_history.php"><div class="tooltip">
        <span class="tooltiptext">Inside<br>History
        </span>INSIDE</div></a></li>
      <li><a href="#">GARAGE</div></a></li>
      <li><a href="outside_history.php"><div class="tooltip">
        <span class="tooltiptext">Outside<br>History
        </span>OUTSIDE</div></a></li>
      <li><a href="current_history.php"><div class="tooltip">
        <span class="tooltiptext">24-Hour<br>History
        </span>24 HOUR</div></a></li>
    </ul>
  </nav>

 
   <!-- Banner -->
  <div class="banner">
    <h1 id="dateTime"></h1>
    <table>
      <tbody>
        <tr>
          <td align="right"><h2>max</h2></td>
          <td><h2> : </h2></td>
          <td align="left"><h2><?php echo number_format($phpGarageTemp_max,2)?>&deg</h2></td>
        </tr>
        <tr>
          <td align="right"><h2>avg</h2></td>
          <td><h2> : </h2></td>
          <td align="left"><h2><?php echo number_format($phpGarageTemp_avg,2)?>&deg</h2></td>
        </tr>
        <tr>
          <td align="right"><h2>min</h2></td>
          <td><h2> : </h2></td>
          <td align="left"><h2><?php echo number_format($phpGarageTemp_min,2)?>&deg;</h2></td>
        </tr>
      </tbody>
    </table>
  </div>
  
   <!-- Button Row -->
<!--   <div class="buttonRow"> -->
<!--     <button class="btn default" onclick="fetchInsideTemp()">close garage</button> -->
<!--     <button class="btn default" onclick="refreshDateTime()">open garage</button> -->
<!--     <button class="btn default" onclick="refreshDateTime()">crack garage</button> -->
<!--   </div> -->
  

  <!-- Journal -->
<!--  <div class="journal"> --> 
<!--    <p> -->
<!--    </p> -->
<!--  </div> -->


    <div id = "container" style = "width: 90%; height: 800px; margin: 0 auto"></div>
      <script language = "JavaScript">
        $(document).ready(function() {
        var options = {
          chart: {
            zoomType : 'x',
          renderTo: 'container',
          type: 'line',
          marginTop: 75,
          marginRight: 0,
          marginBottom: 75
          },
          title: {
            text: 'Garage Temps',
            x: -20 //center
          },
          subtitle: {
                text: document.ontouchstart === undefined ?
                        'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
          },
          xAxis: {
            categories: []
          },
          
          yAxis: {
            title: {
              text: 'Temp \xB0F'
            },
            labels: {
              formatter: function () {
                return (this.value > 0 ? ' + ' : '') + this.value + '\xB0F';
              }
            },
            plotLines: [{
              value: 0,
              width: 1,
              color: '#808080'
            }]
          },
          
          tooltip: {
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}\xB0F</b>',
            valueDecimals: 2,
            split: true
          },
          legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -10,
            y: 100,
            borderWidth: 0
          },
          series: []
        }
         
        $.getJSON("garage_history_data.php", function(json) {
            options.xAxis.categories = json[0]['data'];
            options.series[0] = json[1];
            options.series[1] = json[2];
            options.series[2] = json[3];
            chart = new Highcharts.Chart(options);
          });
        });

      </script>
    </div>

  <!-- Footer -->
  <footer>
    <div class="content">
      <p>
        <span class="author">Craig Santelman</span> has <em>killed</em> a <em>bear</em> with his <em>bare</em> hands.
      </p>
      <p>
        He is trying his best to figure this shit out...
      </p>
    </div>
  </footer>
</body>

<script src="js/app.js"></script>

</html>
