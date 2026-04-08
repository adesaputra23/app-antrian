<?php
// Mengatasi CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header('Access-Control-Allow-Methods: GET, POST');
header("Allow: GET, POST");
// pengecekan ajax request untuk mencegah direct access file, agar file tidak bisa diakses secara langsung dari browser
// panggil file "database.php" untuk koneksi ke database
require_once "../../config/query.php";
require 'cetak.php';
// jika ada ajax request
if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET')) {
	if (isset($_POST['type'])) {
		$action = new config\query;

		if ($_POST['type'] == 'get_antrian') {
			// sql statement untuk menampilkan jumlah data dari tabel "queue_antrian_admisi" berdasarkan "tanggal"
			$query = $action->getAntrian();
			// ambil data hasil query
			// Inisialisasi array untuk menyimpan data
			$dataAntrian = array();

			// Ambil hasil query dan masukkan ke dalam array
			while ($row = mysqli_fetch_assoc($query)) {
				$dataAntrian[] = array(
					'code_antrian' => $row['code_antrian'],
					'no_antrian' => sprintf("%03s", (int) $row['no_antrian'])
				);
			}

			// tampilkan data
			echo json_encode([
				'success' => true,
				'message' => 'Success',
				'data' => $dataAntrian
			]);
		}

		if ($_POST['type'] == 'create_antrian') {


			// // if (isset($_POST['cek_koneksi_printer']) && $_POST['cek_koneksi_printer'] == true) {

			// 	// Ambil setting printer dari database
			// 	$querySetting = $action->getSetting();
			// 	$rows = mysqli_num_rows($querySetting);

			// 	if ($rows > 0) {
			// 		$data = mysqli_fetch_assoc($querySetting);
			// 		$config = $data;
			// 	} else {
			// 		$config = [];
			// 	}

			// 	// Cek koneksi ke printer
			// 	$printer_setting = !empty($config['printer']) ? $config['printer'] : [];
			// 	$ip = !empty($printer_setting["ip_komputer_printer"]) ? $printer_setting["ip_komputer_printer"] : "127.0.0.1";
			// 	$port = !empty($printer_setting['port_komputer_printer']) ? $printer_setting['port_komputer_printer'] : "3000";
			// 	$url = $ip . ":" . $port . "/testkoneksi";

			// 	$curl = curl_init();
			// 	curl_setopt($curl, CURLOPT_URL, $url);
			// 	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			// 	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
			// 	curl_setopt($curl, CURLOPT_TIMEOUT, 5);

			// 	$response = curl_exec($curl);
			// 	$error = curl_error($curl);
			// 	curl_close($curl);

			// 	if ($error) {
			// 		echo json_encode([
			// 			'success' => false,
			// 			'message' => 'Tidak bisa terkoneksi ke printer: ' . $error
			// 		]);
			// 	} else {
			// 		echo json_encode([
			// 			'success' => true,
			// 			'message' => 'Terhubung ke printer.',
			// 			'response' => $response
			// 		]);
			// 	}
			// 	// }
				
			// die;

			$code_antrian = $_POST['code_antrian'];

			$query = $action->getLastAntrianByType($code_antrian);

			// Ambil hasil query
			$result = mysqli_fetch_assoc($query);

			// Jika ada data, tampilkan no_antrian
			if ($result['no_antrian'] !== null) {
				$no_antrian = sprintf("%03s", (int) $result['no_antrian'] + 1);
			} else {
				$no_antrian = sprintf("%03s", 1);
			}

			$insert = $action->createAntrian($no_antrian, $code_antrian);

			if ($insert) {
				echo json_encode([
					'success' => true,
					'message' => 'Success',
					'data' => [
						'code_antrian' => $code_antrian,
						'no_antrian' => $no_antrian
					]
				]);

				$querySetting = $action->getSetting();
				// ambil jumlah baris data hasil querySetting
				$rows = mysqli_num_rows($querySetting);

				if ($rows <> 0) {
					$data = mysqli_fetch_assoc($querySetting);
				} else {
					$data = [];
				}

				$isPrinter = json_decode($data['printer'], true);
				$kodeYangDicari = $code_antrian;

				$sendDataPrinter = array_filter($isPrinter, function ($item) use ($kodeYangDicari) {
					return in_array($kodeYangDicari, $item['kode_antrian']);
				});

				foreach ($sendDataPrinter as $key => $value) {
					$data['printer'] = $value;
				}

				// Cetak hanya jika sukses
				cetak($no_antrian, $code_antrian, $data);
			} else {
				echo json_encode([
					'success' => true,
					'message' => "Gagal menyimpan data ke database: " . mysqli_error($mysqli),
					'data' => []
				]);
			}
		}

		if ($_POST['type'] == 'get_list_type_antrian') {
			$querySetting = $action->getSetting();
			// ambil jumlah baris data hasil querySetting
			$rows = mysqli_num_rows($querySetting);

			if ($rows <> 0) {
				$data = mysqli_fetch_assoc($querySetting);
			} else {
				$data = [];
			}

			// tampilkan data
			echo json_encode([
				'success' => true,
				'message' => 'Success',
				'data' => (isset($data['list_type_antrian'])) ? json_decode($data['list_type_antrian']) : []
			]);
		}
	}
}
