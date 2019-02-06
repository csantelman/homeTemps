<?php
  require 'php/db_config.php';
  
  if (mysqli_connect_errno()) {
     echo 'Error: Could not connect to database.  Please try again later.';
     exit;
  }

  // First select current temps
  $query = "SELECT  pollingTime, 
                    insideTemp, 
                    outsideTemp, 
                    garageTemp 
            from v_combinedTemps 
            order by pollingTime desc limit 1";
  $result = $mysqli->query($query);
  $num_results = $result->num_rows;
  for ($i=0; $i <$num_results; $i++) {
     $row = $result->fetch_assoc();
     $phpInsideTemp  = $row['insideTemp'];
     $phpOutsideTemp = $row['outsideTemp'];
     $phpGarageTemp  = $row['garageTemp'];
     $phpPollingTime = date_create($row['pollingTime']);
  }
  $result->free();
  
  // Next select 24 hour historical data
  $query2 = "SELECT insideTemp_min,
                    insideTemp_avg,
                    insideTemp_max,
                    garageTemp_min,
                    garageTemp_avg,
                    garageTemp_max,
                    outsideTemp_min,
                    outsideTemp_avg,
                    outsideTemp_max
             FROM t_dailyReadings
             WHERE pollingDate >= now() - INTERVAL 1 DAY";
  $result2 = $mysqli->query($query2);
  $num_results2 = $result2->num_rows;
  for ($i=0; $i <$num_results2; $i++) {
    $row2 = $result2->fetch_assoc();
    $phpInsideTemp_min  = $row2['insideTemp_min'];
    $phpInsideTemp_avg  = $row2['insideTemp_avg'];
    $phpInsideTemp_max  = $row2['insideTemp_max'];
    $phpGarageTemp_min  = $row2['garageTemp_min'];
    $phpGarageTemp_avg  = $row2['garageTemp_avg'];
    $phpGarageTemp_max  = $row2['garageTemp_max'];
    $phpOutsideTemp_min = $row2['outsideTemp_min'];
    $phpOutsideTemp_avg = $row2['outsideTemp_avg'];
    $phpOutsideTemp_max = $row2['outsideTemp_max'];
  }
  $result2->free();
  $mysqli->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Fallbrooke Temps</title>
  <link rel="stylesheet" type="text/css" href="styles/reset.css">
  <link rel="stylesheet" type="text/css" href="styles/style.css">
  <link href="https://fonts.googleapis.com/css?family=Major+Mono+Display" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Roboto+Mono" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Comfortaa" rel="stylesheet">
</head>

<body onload="timedRefreshDateTime()">
 
  <!-- Header -->
  <nav class="header">
    <ul>
      <li><a href="#">HOME</div></a></li>
      <li><a href="inside_history.php"><div class="tooltip">
        <span class="tooltiptext">Inside<br>History
        </span>INSIDE</div></a></li>
      <li><a href="garage_history.php"><div class="tooltip">
        <span class="tooltiptext">Garage<br>History
        </span>GARAGE</div></a></li>
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
          <td align="right"><h2>inside</h2></td>
          <td><h2> : </h2></td>
          <td align="left"><h2><div class="temptooltip">
            <span class="temptooltiptext">Min : <?php echo number_format($phpInsideTemp_min,2)?>&deg;<br>
                                          Avg : <?php echo number_format($phpInsideTemp_avg,2)?>&deg;<br>
                                          Max : <?php echo number_format($phpInsideTemp_max,2)?>&deg;</span>
            <?php echo number_format($phpInsideTemp,2)?>&deg;
          </div></h2></td>
        </tr>
        <tr>
          <td align="right"><h2>garage</h2></td>
          <td><h2> : </h2></td>
          <td align="left"><h2><div class="temptooltip">
            <span class="temptooltiptext">Min : <?php echo number_format($phpGarageTemp_min,2)?>&deg;<br>
                                          Avg : <?php echo number_format($phpGarageTemp_avg,2)?>&deg;<br>
                                          Max : <?php echo number_format($phpGarageTemp_max,2)?>&deg;</span>
            <?php echo number_format($phpGarageTemp,2)?>&deg;
          </div></h2></td>
        </tr>
        <tr>
          <td align="right"><h2>outside</h2></td>
          <td><h2> : </h2></td>
          <td align="left"><h2><div class="temptooltip">
            <span class="temptooltiptext">Min : <?php echo number_format($phpOutsideTemp_min,2)?>&deg;<br>
                                          Avg : <?php echo number_format($phpOutsideTemp_avg,2)?>&deg;<br>
                                          Max : <?php echo number_format($phpOutsideTemp_max,2)?>&deg;</span>
            <?php echo number_format($phpOutsideTemp,2)?>&deg;
          </div></h2></td>
        </tr>
      </tbody>
    </table>
    <h3 id="pollingTime">current as of <?php echo date_format($phpPollingTime,"h:i a m/d/Y")?></h3>
    <button class="refreshButton default" onclick="window.location.reload()">refresh temps</button>
  </div>
  
  
  
  
  
  
  
   <!-- Button Row -->
  <div class="buttonRow">
    <button class="btn default" onclick="fetchInsideTemp()">close garage</button>
    <button class="btn default" onclick="refreshDateTime()">open garage</button>
    <button class="btn default" onclick="refreshDateTime()">crack garage</button>
    <br>
  </div>
  

  <!-- Journal -->
<!--  <div class="journal"> -->
<!--    <p> -->
<!--    </p> -->
<!--  </div> -->

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
