<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST, PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../../config/Database.php';
    include_once '../../../models/Tarriff.php';
    

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate status object
    $tarriff = new Tarriff($db);
    
    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));


    $tarriff->r1 = $data->r1 ? $data->r1 : null;
    $tarriff->r2s = $data->r2s ? $data->r2s : null;
    $tarriff->r2t = $data->r2t ? $data->r2t : null;
    $tarriff->r3 = $data->r3 ? $data->r3 : null;
    $tarriff->r4 = $data->r4 ? $data->r4 : null;
    $tarriff->c1s = $data->c1s ? $data->c1s : null;
    $tarriff->c1t = $data->c1t ? $data->c1t: null;
    $tarriff->c2 = $data->c2 ? $data->c2 : null;
    $tarriff->c3 = $data->c3 ? $data->c3 : null;
    $tarriff->d1 = $data->d1 ? $data->d1 : null;
    $tarriff->d2 = $data->d2 ? $data->d2 : null;
    $tarriff->d3 = $data->d3 ? $data->d3 : null;
    $tarriff->a1 = $data->a1 ? $data->a1 : null;
    $tarriff->a2 = $data->a2 ? $data->a2 : null;
    $tarriff->a3 = $data->a3 ? $data->a3 : null;
    $tarriff->s1 = $data->s1 ? $data->s1 : null;


    if ($_SERVER['REQUEST_METHOD'] === "POST" || $_SERVER['REQUEST_METHOD'] === "PUT") {

      try {
        $tarriff->add_tarriff();
        echo json_encode(
          array(
            "status" => "success",
            "message" => "new tarriff set"
          )
        );
      } catch (Exception $e) {
        echo $e;
      }

    } 
  