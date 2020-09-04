<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../../config/Database.php';
    include_once '../../../models/Admin.php';
    

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate status object
    $create_account = new Admin($db);
    
    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));
    if ($data) {

      $create_account->first_name = $data->firstName;
      $create_account->last_name = $data->lastName;
      $create_account->company_name = $data->companyName;
      $create_account->email = $data->email;
      $create_account->address = $data->address;
      $create_account->phone = $data->phone;

      switch($data->role) {
        case "super_admin":
          $create_account->role_id = 1;
          break;

        case "admin":
          $create_account->role_id = 2;
          break;
        
        case "disco":
          $create_account->role_id = 3;
          break;

        default:
          break;
      }

      // $create_account->password = $data->password;
      $create_account->password = "eko_electricity";
      
      
      if( $create_account->create_account()) {
        try {
          $create_account->add_authentication();
          
          if ($create_account->role_id == 3) {
            $create_account->create_tarriff();

            echo json_encode(
              array(
                'error' => false,
                'code' => 200,
                'message' => 'account created'
              )
            );
          } else {
            echo "na wa";
          }
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
    