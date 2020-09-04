<?php
  // echo "routes index";

  include_once '../config/Database.php';
  include_once '../models/History.php';
  

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate status object
  $status = new History($db);

  $status->today_usage();

//   $query = "select * from device_status 
// where (time_stamp between  DATE_FORMAT(NOW() ,'%Y-%m-01') AND NOW() )";

// $query = "SELECT * FROM device_status WHERE DATE(`time_stamp`) = CURDATE()";

// SELECT * FROM device_status WHERE time_stamp > DATE_SUB(NOW(), INTERVAL 1 DAY) ;
// SELECT * FROM device_status WHERE time_stamp > DATE_SUB(NOW(), INTERVAL 1 WEEK) ;
// SELECT * FROM device_status WHERE time_stamp > DATE_SUB(NOW(), INTERVAL 1 MONTH) ;