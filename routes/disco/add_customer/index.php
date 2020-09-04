<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../../config/Database.php';
    include_once '../../../models/Disco.php';
    

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate status object
    $customer = new Disco($db);
    
    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));
    if ($data) {

      $customer->first_name = $data->firstName;
      $customer->last_name = $data->lastName;
      $customer->address = $data->address;
      $customer->phone = $data->phone;
      $customer->meter_number = $data->meterNumber;
      $customer->tarriff_code = $data->tarriffCode;

      // $customer->password = $data->password;
      $customer->password = "eko_electricity";
      
      
      if( $customer->create_account()) {
        try {
          $customer->add_authentication();
          $customer->add_harware_state_control();
          $customer->add_meter_recharge();
          
          echo json_encode(
            array(
              'error' => false,
              'code' => 200,
              'message' => 'account created'
            )
          );
          
        } catch(Exception $e) {
          // var_dump($e);
          echo $e;
        }
      }  else {
        echo "error";
      }
      
      // catch(Exception $e) {
      //   echo $e;
      // }
      
    } else {
      http_response_code(412);
    }
    