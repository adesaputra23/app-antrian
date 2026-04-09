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

    // Kirim permintaan POST ke server printer menggunakan @fsockopen
    $parsed_url = parse_url("http://$url"); // $url sudah berisi ip:port/path
    $host = $parsed_url['host'] ?? '127.0.0.1';
    $port = $parsed_url['port'] ?? 80;
    $path = $parsed_url['path'] ?? '/printantrian';

    // Siapkan data json dan headers
    $payload = json_encode($data);
    $headers = "POST $path HTTP/1.1\r\n";
    $headers .= "Host: $host\r\n";
    $headers .= "Content-Type: application/json\r\n";
    $headers .= "Content-Length: " . strlen($payload) . "\r\n";
    $headers .= "Connection: Close\r\n\r\n";

    $timeout = 5;
    $fp = @fsockopen($host, $port, $errno, $errstr, $timeout);
    $response = '';
    if ($fp) {
        fwrite($fp, $headers . $payload);
        stream_set_timeout($fp, $timeout);
        while (!feof($fp)) {
            $response .= fgets($fp, 1024);
        }
        fclose($fp);
    } else {
        // Jika gagal konek, echo error
        echo "Printer connection error: $errstr ($errno)";
    }
}
