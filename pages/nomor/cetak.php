<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


function cetak($no_antrian, $code_antrian, $config)
{

    $setting_printer = (!empty($config['printer'])) ? $config['printer'] : [];
    $ip = !empty($setting_printer["ip_komputer_printer"]) ? $setting_printer["ip_komputer_printer"] : "127.0.0.1";
    $port = !empty($setting_printer['port_komputer_printer']) ? $setting_printer['port_komputer_printer'] : "3000";
    $url = $ip . ":" . $port . "/printantrian";
    $data = [
        "no_antrian" => $no_antrian,
        "code_antrian" => $code_antrian,
        "config" => $config
    ];

    $antrian =  $code_antrian.$no_antrian;

    try {
        $fp = pfsockopen($ip, $port, $errno, $errstr, 10);
        if (!$fp) {
            throw new Exception("Gagal konek: $errstr ($errno)");
        }

        // Karakter Kontrol ESC/POS
        $esc        = chr(27);
        $gs         = chr(29);
        $newline    = "\n";

        // 1. Inisialisasi Printer
        $msg = $esc . "@"; 

        // 2. Header: Nama RSUD (Center, Bold, Double Height)
        $msg .= $esc . "a" . chr(1); // Align Center
        $msg .= $esc . "E" . chr(1); // Bold ON
        $msg .= $gs . "!" . chr(17); // Double High & Wide
        $msg .= "RSUD SUMBAWA" . $newline;

        // 3. Sub-Header: Alamat atau Unit (Normal)
        $msg .= $gs . "!" . chr(0);  // Normal Size
        $msg .= $esc . "E" . chr(0); // Bold OFF
        $msg .= "Loket Antrian Kategori C" . $newline;
        $msg .= "--------------------------------" . $newline . $newline;

        // 4. Label Nomor Antrean (Normal)
        $msg .= "NOMOR ANTREAN" . $newline;

        // 5. Angka Antrean (Sangat Besar & Bold)
        $msg .= $esc . "E" . chr(1); // Bold ON
        $msg .= $gs . "!" . chr(119); // Quadruple Size (Besar sekali)
        $msg .= $antrian . $newline;
        $msg .= $gs . "!" . chr(0);  // Reset ke Normal
        $msg .= $esc . "E" . chr(0); // Bold OFF

        // 6. Footer: Tanggal & Jam
        $msg .= $newline;
        $msg .= "--------------------------------" . $newline;
        $msg .= "Waktu: " . date('d-m-Y H:i:s') . $newline;
        $msg .= "Simpan struk ini untuk dipanggil" . $newline;
        $msg .= "Semoga Lekas Sembuh" . $newline;

        // 7. Spasi Tambahan & Potong Kertas
        $msg .= $newline . $newline . $newline . $newline;
        $msg .= $gs . "V" . chr(66) . chr(0); // Full Cut

        fwrite($fp, $msg);
        fclose($fp);
        echo "Struk berhasil dikirim ke printer.";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
    
    // $curl = curl_init();
    // curl_setopt($curl, CURLOPT_URL, $url);
    // curl_setopt($curl, CURLOPT_POST, true);
    // curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
    // curl_setopt($curl, CURLOPT_TIMEOUT, 5);
    // $response = curl_exec($curl);
    // $err      = curl_error($curl);
    // curl_close($curl);
    // if ($err) {
    //     echo $err;
    // }
}
