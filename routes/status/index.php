<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/HardwareStatus.php';
    

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate status object
    $status = new HardwareStatus($db);

    // Get raw posted data
    // $data = json_decode(file_get_contents("php://input"));
    if (isset($_POST['status'])) {
      $data = $_POST['status'];
      // echo $data;

      http_response_code(202);

      $data_arr = explode("|", $data);

      $status->mains_state = $data_arr[0][0] ? "on" : "off";
      $status->device_state = $data_arr[0][1] ? "on" : "off";
      $status->potential_loss = $data_arr[0][2] ? "collapsed" : "restored";
      $status->bypass_state = $data_arr[0][3] ? "bypassed" : "normal";
      $status->voltage = $data_arr[1];
      $status->frequency = $data_arr[2];
      $status->current_consumption = $data_arr[3];
      $status->kwh = $data_arr[4];
      $status->temperature = $data_arr[5];

      try {
        $status->update_status();
      } catch(Exception $e) {
        echo $e;
      }
      
    } 
    
    // 111|230|49.9|12345|12345.7|100

    
    // $status->device_id = $data->device_id;
    // $status->op_mode = $data->stat->opm;
    // $status->battery = $data->stat->bat;
    // $status->op_volt = $data->stat->opv;
    // $status->op_power = $data->stat->opp;
    // $status->op_current = $data->stat->opc;
    // $status->ch_current = $data->stat->chc;


    // // Update status
    // if($status->update_status()) {
        
    //     // CODE BELOW SENDS EMAIL NOTIFICATION
    //     $notificationSent = False;
    //     if ($status->battery < 50) {

    //         // First check if last email notification was sent not later than 30mins before current time;
    //         $result = $status->last_sent_notification();
    //         $result = $result->fetch();
    //         $notificationLastSent = $result->last_sent;
    //         $currentTime = time();
            
    //         // Send email in 30mins interval;
    //         if(time() > $notificationLastSent+(30*60)) {
    //             sendBatteryNotificationMail('Test Message');
    //             $notificationSent = True;

    //             // Update notification last update time
    //             $status->update_last_notification_time();
    //         }

    //     }
    //     echo json_encode(
    //         array(
    //             'error' => false,
    //             'code' => 200,
    //             'message' => 'Status Updated',
    //             'low battery notification sent' => $notificationSent
    //         )
    //     );
        

    // } else {
    //     echo json_encode(
    //     array(
    //         'error' => true,
    //         'code' => 200,
    //         'message' => 'Status NOT Updated'
    //         )
    //     );
    // }
    