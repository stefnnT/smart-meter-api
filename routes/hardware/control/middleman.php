<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: GET');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    // foreign code
    $curl = curl_init();

    $headers = array(
        'Content-Type: application/x-www-form-urlencoded',
        'Accept: application/json'
    );

    $fields = http_build_query(array(
      'status' => '228|0_0000|1_0120|0_0000|1_0069|0_0000|0_0000|0_0000|0_0000|G'
    ));

    $url = 'https://powercase.natterbase.com/powercheck/user/command/00017';

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_URL, $url);
    // curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_HTTPGET, 1);
    // curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);

    $resp = curl_exec($curl);
    
    echo $resp;

    // foreign code ends here
