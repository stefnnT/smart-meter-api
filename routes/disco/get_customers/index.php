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

  // Query data
  $result = $customers->get_all_subscribers();
  // Get row count
  $num = $result->rowCount();

  // Check if any record exists
  if($num > 0) {
      // record array
      
      $subscriber_details = array();
      $subscriber_details['customers'] = array();

      
      foreach ($result->fetchAll() as $subscriber) {
          $post_item = array(
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
          );

          // push each record
          array_push($subscriber_details['customers'], $post_item);
      }
      
      // parse data as JSON
      echo json_encode($subscriber_details);

  } else {
      // No record
      echo json_encode(
          array('message' => 'No Record Found')
      );
  }
  
  // get_tarriff_price('r2s', '23');
  // function get_tarriff_price($tarriff_code, $disco_id) {
  //   $database = new Database();
  //   $db = $database->connect();
  //   $tarriff = new Disco($db);
  //   $amount = $tarriff->get_tarriff_price($tarriff_code, $disco_id);
  //   return $amount->fetch()->{$tarriff_code};
  // }







// SELECT ALL QUERY
// SELECT 
//   d.device_id AS site_id, d.op_mode, d.battery, d.op_volt, d.op_current, d.op_power, d.ch_current,
//   s.site_name, s.street, s.city, s.state, s.latitude, s.longitude, s.phone, s.energy_needs, s.battery_amp, s.solar_current, 
//   w.state AS t1_state, w.start_time AS t1_start_time, w.stop_time AS t1_stop_time, w.priority AS t1_priority, w.load_current AS t1_load_current,
//   x.state AS t2_state, x.start_time AS t2_start_time, x.stop_time AS t2_stop_time, x.priority AS t2_priority, x.load_current AS t2_load_current,
//   y.state AS t3_state, y.start_time AS t3_start_time, y.stop_time AS t3_stop_time, y.priority AS t3_priority, y.load_current AS t3_load_current,
//   z.state AS t4_state, z.start_time AS t4_start_time, z.stop_time AS t4_stop_time, z.priority AS t4_priority, z.load_current AS t4_load_current
//   FROM device_status d INNER JOIN (
//   site_details s INNER JOIN (
//     terminal_one w INNER JOIN (
//       terminal_two x INNER JOIN (
//         terminal_three y INNER JOIN terminal_four z USING(site_id)) 
//         USING (site_id)) 
//       USING (site_id)) 
//     USING (site_id)) 
//   ON s.site_id = d.device_id
