<?php 
  require 'php/db_config.php';
  
  if (mysqli_connect_errno()) {
     echo 'Error: Could not connect to database.  Please try again later.';
     exit;
  }

  // First select current temps
  $query = "SELECT  pollingTime 
				    ,insideTemp 
				    ,garageTemp 
				    ,outsideTemp 
			from v_combinedTemps
      WHERE pollingTime >= now() - INTERVAL 1 DAY;";
  $result = $mysqli->query($query);
  $num_results = $result->num_rows;
  $rows0 = array();
  $rows1 = array();
  $rows2 = array();
  $rows3 = array();
  $rows0['name'] = 'Date';
  $rows1['name'] = 'Inside';
  $rows2['name'] = 'Garage';
  $rows3['name'] = 'Outside';
  for ($i=0; $i <$num_results; $i++) {
     $row = $result->fetch_assoc();
     $rows0['data'][] = $row['pollingTime'];
     $rows1['data'][] = $row['insideTemp'];
     $rows2['data'][] = $row['garageTemp'];
     $rows3['data'][] = $row['outsideTemp'];
  }
  $result->free();

  $result = array();
  array_push($result,$rows0); // pollingDate
  array_push($result,$rows1); // insideTemp
  array_push($result,$rows2); // garageTemp
  array_push($result,$rows3); // outsideTemp
  print json_encode($result, JSON_NUMERIC_CHECK);
?>
