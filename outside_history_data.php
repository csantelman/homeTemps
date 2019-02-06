<?php 
  require 'php/db_config.php';
  
  if (mysqli_connect_errno()) {
     echo 'Error: Could not connect to database.  Please try again later.';
     exit;
  }

  // First select current temps
  $query = "SELECT  pollingDate 
				    ,outsideTemp_max 
				    ,outsideTemp_avg 
				    ,outsideTemp_min 
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
     $rows1['data'][] = $row['outsideTemp_max'];
     $rows2['data'][] = $row['outsideTemp_avg'];
     $rows3['data'][] = $row['outsideTemp_min'];
  }
  $result->free();

  $result = array();
  array_push($result,$rows0); // pollingDate
  array_push($result,$rows1); // outsideTemp_max
  array_push($result,$rows2); // outsideTemp_avg
  array_push($result,$rows3); // outsideTemp_min
  print json_encode($result, JSON_NUMERIC_CHECK);
?>
