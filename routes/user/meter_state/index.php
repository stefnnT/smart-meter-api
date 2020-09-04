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
    $state = new User($db);
    
    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));
    if ($data) {

      $state->meter_number = $data->meterNumber;
      $state->state = $data->state;
      
      try {
        $state->update_hardware_state();
        
        echo json_encode(
          array(
            'status' => 'success',
            'code' => 200,
            'message' => 'meter state updated'
          )
        );
        
      } catch(Exception $e) {
        // var_dump($e);
        echo $e;
      }
      
    } else {
      http_response_code(412);
    }
    