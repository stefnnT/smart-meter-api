<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: GET');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../../config/Database.php';
    include_once '../../../models/HardwareStatus.php';
    

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate status object
    $state = new HardwareStatus($db);
    $token = new HardwareStatus($db);
    
    // Get raw posted data
    // $data = json_decode(file_get_contents("php://input"));
    // if ($data->meterNumber) {
    if ($_GET['meter_number']) {

      // $state->meter_number = $data->meterNumber;
      // $token->meter_number = $data->meterNumber;
      
      $state->meter_number = $_GET['meter_number'];
      $token->meter_number = $_GET['meter_number'];

      // Query data
      $result = $state->get_hardware_state();
      // Get row count
      $num = $result->rowCount();

      if($num > 0) {
        $control = $result->fetch();

        $result2 = $token->get_unused_token();
        $num2 = $result2->rowCount();
        
        if($num2 > 0) {
          $control2 = $result2->fetch();
           
          $unit = $control2->loaded === 'no' ? $control2->units : "######";
          // echo $unit;
          if ($unit[0] !== '#') {
            $unit *= 100;
            $unit = strval($unit);
            $unitArr = array();
            for ($i = 0; $i < 6; $i++) {
              if (isset($unit[$i])) {
                array_push($unitArr, $unit[$i]);
              } else {
                array_unshift($unitArr, 0);
              }
            }
            // var_dump( $unitArr);
            $unit = join('', $unitArr);
          }

          echo $control->state.'|'.$unit; 
          // echo "afdds";

          // echo json_encode(
          //   array(
          //     "status" => "success",
          //     "control" => $control->state,
          //     "units" => $control2->loaded === 'no' ? $control2->units : "######"
          //   )
          // );

        } else {
          echo json_encode(
            array(
              "status" => "error"
            )
          );
        }
        

      } else {
        http_response_code(412);
      }
    
    }