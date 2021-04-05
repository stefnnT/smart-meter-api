<?php

  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../../config/Database.php';
  include_once '../../../models/HardwareStatus.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate site details object
  $status = new HardwareStatus($db);
  echo "got heregi";
  // Query data
  $result = $status->get_all_status();
  // Get row count
  $num = $result->rowCount();

  // Check if any record exists
  if($num > 0) {
      // record array
      
      $subscriber_details = array();
      $subscriber_details['status'] = array();

      
      foreach ($result->fetchAll() as $each) {
          $post_item = array(
            "rawStatus" => $each->temp_everything,
            "time" => $each->time_stamp
            )
          );

          // push each record
          array_push($subscriber_details['status'], $post_item);
      }
      
      // parse data as JSON
      echo json_encode($subscriber_details);

  } else {
      // No record
      echo json_encode(
          array('message' => 'No Record Found')
      );
  }
  
 