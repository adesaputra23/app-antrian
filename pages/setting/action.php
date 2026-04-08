<?php
require_once "../../config/query.php";

if (isset($_POST['type'])) {
	$action = new config\query;

	if ($_POST['type'] == 'save') {
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {

			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$id = $_POST['id'];
				$nama_instansi = $_POST['nama_instansi'];
				$alamat = $_POST['alamat'];
				$telpon = $_POST['telpon'];
				$email = $_POST['email'];
				$running_text = $_POST['running_text'];
				$youtube_id = $_POST['youtube_id'];
				$no_loket = $_POST['no_loket'];
				$nama_loket = $_POST['nama_loket'];
				$handle_type_antrian = $_POST['handle_type_antrian'];
				$handle_jenis_poli = $_POST['handle_jenis_poli'];
				$type_antrian = $_POST['type_antrian'];
				$code_antrian = $_POST['code_antrian'];
				$warna_primary = $_POST['warna_primary'];
				$warna_secondary = $_POST['warna_secondary'];
				$warna_accent = $_POST['warna_accent'];
				$warna_background = $_POST['warna_background'];
				$warna_text = $_POST['warna_text'];
				$list_jenis_poli = $_POST['nama_poli'];
				$handle_type_antrian_printer = $_POST['handle_type_antrian_printer'];
				$no_monitor = $_POST['no_monitor'];
				$handle_category_monitor = $_POST['handle_category_monitor'];
				$ip_komputer_printer = $_POST['ip_komputer_printer'];
				$port_komputer_printer = $_POST['port_komputer_printer'];

				$printerArray = [];
				if (count($ip_komputer_printer) > 0) {
					foreach ($ip_komputer_printer as $key_pn => $printer) {
						$printerArray[] = [
							'ip_komputer_printer' => $printer,
							'port_komputer_printer' => $port_komputer_printer[$key_pn],
							'kode_antrian' => $handle_type_antrian_printer[$key_pn]
						];
					}
				}
				$printer = json_encode($printerArray);

				$config_monitor = [];

				if (count($no_monitor) > 0 && count($handle_category_monitor) > 0) {
					foreach ($no_monitor as $key_nk => $val_nk) {
						$config_monitor[] = [
							'no_monitor' => $val_nk,
							'handle_category_monitor' => str_replace('"', '\"', json_encode($handle_category_monitor[$key_nk]))
						];
					}
					$config_monitor = json_encode($config_monitor);
				}

				$nama_logo = $_POST['nama_logo'];
				$loket = array();
				if (count($no_loket) > 0) {
					foreach ($no_loket as $key_nk => $val_nk) {
						$check_handle_type_antrian = (!empty($handle_type_antrian[$key_nk])) ? $handle_type_antrian[$key_nk] : [];
						$loket[] = [
							'no_loket' => $val_nk,
							'nama_loket' => $nama_loket[$key_nk],
							'handle_type_antrian' => str_replace('"', '\"', json_encode($check_handle_type_antrian))
						];
					}
				}
				$list_loket = json_encode($loket);

				if ($_FILES['logo']['error'] == 4 || ($_FILES['logo']['size'] == 0 && $_FILES['logo']['error'] == 0)) {
					$logo = $nama_logo;
				} else {
					$targetDirectory = "../../assets/img/"; // Specify the directory where uploaded files will be stored
					$targetFile = $targetDirectory . basename($_FILES["logo"]["name"]);
					$uploadOk = 1;
					$fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

					// Check if the file already exists
					if (file_exists($targetDirectory . $nama_logo)) {
						unlink($targetDirectory . $nama_logo);
						$uploadOk = 1;
					}

					// Check file size (limit to 2MB)
					if ($_FILES["logo"]["size"] > 2000000) {
						echo "Sorry, your file is too large.";
						$uploadOk = 0;
					}

					// Allow certain file formats (you can add more as needed)
					if ($fileType != "jpg" && $fileType != "jpeg" && $fileType != "png" && $fileType != "gif") {
						echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
						$uploadOk = 0;
					}

					// Check if $uploadOk is set to 0 by an error
					if ($uploadOk == 0) {
						echo "Sorry, your file was not uploaded.";
					} else {
						if (move_uploaded_file($_FILES["logo"]["tmp_name"], $targetFile)) {
							$logo = $_FILES["logo"]["name"];
						} else {
							$logo = $nama_logo;
						}
					}
				}

				$type = array();
				if (count($type_antrian) > 0) {
					foreach ($type_antrian as $key_ta => $val_ta) {
						$type[] = [
							'type_antrian' => $val_ta,
							'code_antrian' => $code_antrian[$key_ta],
							'list_poli' => $handle_jenis_poli[$key_ta] ?? []
						];
					}
				}
				$list_type_antrian = json_encode($type);

				$array_poli = array();
				if (count($list_jenis_poli) > 0) {
					foreach ($list_jenis_poli as $key_pl => $val_pl) {
						$array_poli[] = $val_pl;
					}
				}
				$list_poli = $array_poli[0];
				$save = $action->saveSetting($id, $nama_instansi, $logo, $alamat, $telpon, $email, $running_text, $youtube_id, $list_loket, $list_type_antrian, $warna_primary, $warna_secondary, $warna_accent, $warna_background, $warna_text, $printer, $list_poli, $config_monitor);

				if ($save) {
					echo json_encode([
						'success' => true,
						'message' => 'Data berhasil disimpan'
					]);
				} else {
					echo json_encode([
						'success' => false,
						'message' => 'Data gagal disimpan'
					]);
				}
			}
		}
	}

	if ($_POST['type'] == 'reset_antrian') {
		$query = $action->resetAntrian();

		if ($query) {
			echo json_encode([
				'success' => true,
				'message' => 'Antrian berhasil direset'
			]);
		} else {
			echo json_encode([
				'success' => false,
				'message' => 'Oppps, tidak ada antrian yang direset!'
			]);
		}
	}

	if ($_POST['type'] == 'logout') {
		session_start();
		session_destroy();
		echo json_encode([
			'success' => true,
			'message' => 'Success'
		]);
	}
}
