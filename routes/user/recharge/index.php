<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../../config/Database.php';
    include_once '../../../models/User.php';
    

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate status object
    $recharge = new User($db);
    
    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));
    if ($data->meterNumber) {

      $recharge->meter_number = $data->meterNumber;
      $recharge->amount = $data->amount;
      $recharge->tarriff = $data->tarriff;
      $recharge->stakeholder_id = $data->stakeholderId;

      try {

        $recharge->recharge_status();
        echo json_encode(
          array(
            "status" => "success",
            "message" => "recharge received"
          )
        );

      } catch(Exception $e) {
        echo $e;
        // echo json_encode(
        //   array(
        //     "status" => "error",
        //     "message" => "recharge error"
        //   )
        // );
      }

    
    }