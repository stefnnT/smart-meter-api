<?php

  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../../config/Database.php';
  include_once '../../../models/Disco.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate site details object
  $customers = new Disco($db);
  $checks = new Disco($db);

  $data = json_decode(file_get_contents("php://input"));

  
  if ($data->meterNumber) {
    $customers->meter_number = $data->meterNumber;

    // Query data
    $result = $customers->get_one_subscriber();
    // Get row count
    $num = $result->rowCount();

    if($num > 0) {
      $subscriber = $result->fetch();

      echo json_encode(
        array(
          "subscriberBiodata" => array(
            "firstName" => $subscriber->first_name,
            "lastName" => $subscriber->last_name, 
            "address" => $subscriber->address,
            "phone" => $subscriber->phone,
          ),
          "meterDetails" => array(
            "meterNumber" => $subscriber->meter_number,
            "tarriffCode" => $subscriber->tarriff_code,
            "tarriffUnitPrice" => $checks->get_tarriff_price($subscriber->tarriff_code, '23'), //23 should be tied to session stakeholder_id
            "lastDeviceStatus" => $checks->get_last_status($subscriber->meter_number)
          ),
          "meterUsageHistory" => array(
  
          )
        )
      );
      
    } else {
      // No record
      echo json_encode(
          array(
            "status" => "error",
            'message' => 'no record found'
          )
      );
    }


  } else {
    echo json_encode(
      array(
        "status" => "error",
        "message" => "no meter number"
      )
    );
  }
