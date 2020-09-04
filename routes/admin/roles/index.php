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
    $add_role = new Admin($db);
    
    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));
    if ($data->role) {

      $add_role->role = $data->role;
      
      try {
        $add_role->add_role();
        http_response_code(202);
        echo json_encode(
          array(
            'status' => 'success',
            'message' => 'new role added'
          )
        );
        
      }  catch(Exception $e) {
        var_dump($e);
      }
      
    } else {
      http_response_code(412);
    }
    