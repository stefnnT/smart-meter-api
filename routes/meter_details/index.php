<?php

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/MeterDetails.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate site details object
    $sites = new GetSiteDetails($db);

    // Query data
    $result = $sites->read_all();
    // Get row count
    $num = $result->rowCount();

    // Check if any record exists
    if($num > 0) {
        // record array
        $site_details = array();
        $site_details['sites'] = array();

        
        foreach ($result->fetchAll() as $site) {
            $post_item = array(
                "id" => $site->site_id,
                "siteName" => $site->site_name,
                "location" => array(
                    "street" => $site->street,
                    "city" => $site->city,
                    "state" => $site->state,
                    "latitude" => $site->latitude,
                    "longitude" => $site->longitude,
                ),
                "siteDetails" => array(
                    "phone" => $site->phone,
                    "energyNeed" => $site->energy_needs,
                    "status" => array(
                        "mode" => $site->op_mode == 1 ? "ON" : "OFF",
                        "voltage" => $site->op_volt,
                        "current" => $site->op_current,
                        "power" => $site->op_power,
                        "chargingCurrent" => $site->ch_current,
                        "batteryPercentage" => $site->battery,
                    ),
                    "controls" => [
                        array(
                            "terminal" => array(
                                "state" => $site->t1_state,
                                "start" => $site->t1_start_time,
                                "stop" => $site->t1_stop_time,
                                "priority" => $site->t1_priority,
                            )
                        ),
                        array(
                            "terminal" => array(
                                "state" => $site->t2_state,
                                "start" => $site->t2_start_time,
                                "stop" => $site->t2_stop_time,
                                "priority" => $site->t2_priority,
                            )
                        ),
                        array(
                            "terminal" => array(
                                "state" => $site->t3_state,
                                "start" => $site->t3_start_time,
                                "stop" => $site->t3_stop_time,
                                "priority" => $site->t3_priority,
                            )
                        ),
                        array(
                            "terminal" => array(
                                "state" => $site->t4_state,
                                "start" => $site->t4_start_time,
                                "stop" => $site->t4_stop_time,
                                "priority" => $site->t4_priority,
                            )
                        )
                    ]
                )   
            );

            // push each record
            array_push($site_details['sites'], $post_item);
        }
       
        // parse data as JSON
        echo json_encode($site_details);

    } else {
        // No record
        echo json_encode(
            array('message' => 'No Record Found')
        );
    }








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
