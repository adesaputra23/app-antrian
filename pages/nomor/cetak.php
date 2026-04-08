<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


function cetak($no_antrian, $code_antrian, $config)
{
    // echo 'masukkk';
    $setting_printer = (!empty($config['printer'])) ? $config['printer'] : [];
    $ip = !empty($setting_printer["ip_komputer_printer"]) ? $setting_printer["ip_komputer_printer"] : "127.0.0.1";
    $port = !empty($setting_printer['port_komputer_printer']) ? $setting_printer['port_komputer_printer'] : "3000";
    $url = $ip . ":" . $port . "/printantrian";  

    $data = [
        "no_antrian" => $no_antrian,
        "code_antrian" => $code_antrian,
        "config" => $config
    ];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($curl);
    $err      = curl_error($curl);

    curl_close($curl);
    if ($err) {
        echo $err;
    }
}
