<?php 
  require 'php/db_config.php';
  
  if (mysqli_connect_errno()) {
     echo 'Error: Could not connect to database.  Please try again later.';
     exit;
  }

  // First select current temps
  $query = "SELECT  pollingDate 
				    ,insideTemp_max 
				    ,insideTemp_avg 
				    ,insideTemp_min 
			from t_dailyReadings";
  $result = $mysqli->query($query);
  $num_results = $result->num_rows;
  $rows0 = array();
  $rows1 = array();
  $rows2 = array();
  $rows3 = array();
  $rows0['name'] = 'Date';
  $rows1['name'] = 'Maximum';
  $rows2['name'] = 'Average';
  $rows3['name'] = 'Minimum';
  for ($i=0; $i <$num_results; $i++) {
     $row = $result->fetch_assoc();
     $t_date = strtotime($row['pollingDate']);
     $myDateFormat = date("m/d/Y", $t_date);
     $rows0['data'][] = $myDateFormat;
     $rows1['data'][] = $row['insideTemp_max'];
     $rows2['data'][] = $row['insideTemp_avg'];
     $rows3['data'][] = $row['insideTemp_min'];
  }
  $result->free();

  $result = array();
  array_push($result,$rows0); // pollingDate
  array_push($result,$rows1); // insideTemp_max
  array_push($result,$rows2); // insideTemp_avg
  array_push($result,$rows3); // insideTemp_min
  print json_encode($result, JSON_NUMERIC_CHECK);
?>
