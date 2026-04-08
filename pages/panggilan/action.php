<?php
// panggil file "database.php" untuk koneksi ke database
require_once "../../config/query.php";
// pengecekan ajax request untuk mencegah direct access file, agar file tidak bisa diakses secara langsung dari browser
// jika ada ajax request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    if (isset($_GET['type'])) {
        $action = new config\query;

        if ($_GET['type'] == 'get_jumlah_antrian') {
            // sql statement untuk menampilkan data "no_antrian" dari tabel "queue_antrian_admisi" berdasarkan "tanggal" dan "status = 1"
            $query = $action->getJumlahAntrian();
            // ambil jumlah baris data hasil query
            $rows = mysqli_num_rows($query);
            $dataJmlAntrian = array();

            if ($rows <> 0) {
                // ambil data hasil query
                while ($row = mysqli_fetch_assoc($query)) {
                    $data['code_antrian'] = $row["code_antrian"];
                    $data['jumlah']     = $row["jumlah"];

                    array_push($dataJmlAntrian, $data);
                }

                echo json_encode([
                    'success' => true,
                    'message' => 'Success',
                    'data' => $dataJmlAntrian
                ]);
            }else {
                echo json_encode([
                    'success' => true,
                    'message' => 'Success',
                    'data' => []
                ]);
            }
        }

        if ($_GET['type'] == 'get_antrian_sekarang') {
            // sql statement untuk menampilkan data "no_antrian" dari tabel "queue_antrian_admisi" berdasarkan "tanggal" dan "status = 1"
            $query = $action->getAntrianSekarang();
            // ambil jumlah baris data hasil query
            $rows = mysqli_num_rows($query);
            $dataJmlAntrian = array();

            if ($rows <> 0) {
                // ambil data hasil query
                while ($row = mysqli_fetch_assoc($query)) {
                    $data['id']         = $row["id"];
                    $data['code_antrian'] = $row["code_antrian"];
                    $data['no_antrian']     = $row["no_antrian"];
                    $data['status']     = $row["status"];

                    array_push($dataJmlAntrian, $data);
                }

                echo json_encode([
                    'success' => true,
                    'message' => 'Success',
                    'data' => $dataJmlAntrian
                ]);
            }else {
                echo json_encode([
                    'success' => true,
                    'message' => 'Success',
                    'data' => []
                ]);
            }
        }

        if ($_GET['type'] == 'get_antrian_selanjutnya') {
            // sql statement untuk menampilkan data "no_antrian" dari tabel "queue_antrian_admisi" berdasarkan "tanggal" dan "status = 1"
            $query = $action->getAntrianSelanjutnya();
            // ambil jumlah baris data hasil query
            $rows = mysqli_num_rows($query);
            $dataJmlAntrian = array();

            if ($rows <> 0) {
                // ambil data hasil query
                while ($row = mysqli_fetch_assoc($query)) {
                    $data['id']     = $row["id"];
                    $data['code_antrian'] = $row["code_antrian"];
                    $data['no_antrian']     = $row["no_antrian"];
                    $data['status']     = $row["status"];

                    array_push($dataJmlAntrian, $data);
                }

                echo json_encode([
                    'success' => true,
                    'message' => 'Success',
                    'data' => $dataJmlAntrian
                ]);
            }else {
                echo json_encode([
                    'success' => true,
                    'message' => 'Success',
                    'data' => []
                ]);
            }
        }

        if ($_GET['type'] == 'get_sisa_antrian') {
            // sql statement untuk menampilkan data "no_antrian" dari tabel "queue_antrian_admisi" berdasarkan "tanggal" dan "status = 1"
            $query = $action->getSisaAntrian();
            // ambil jumlah baris data hasil query
            $rows = mysqli_num_rows($query);
            $dataJmlAntrian = array();

            if ($rows <> 0) {
                // ambil data hasil query
                while ($row = mysqli_fetch_assoc($query)) {
                    $data['code_antrian'] = $row["code_antrian"];
                    $data['jumlah']     = $row["jumlah"];

                    array_push($dataJmlAntrian, $data);
                }

                echo json_encode([
                    'success' => true,
                    'message' => 'Success',
                    'data' => $dataJmlAntrian
                ]);
            }else {
                echo json_encode([
                    'success' => true,
                    'message' => 'Success',
                    'data' => []
                ]);
            }
        }

        if ($_GET['type'] == 'update_antrian') {
            $id = $_GET['id'];
            $update = $action->updateAntrian($id);
            if ($update) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Success updated data',
                    'data' => $id
                ]);
            }
        }

        if ($_GET['type'] == 'create_panggilan') {
            $antrian = $_GET['antrian'];
            $loket = $_GET['loket'];
            $update = $action->createPanggilan($antrian, $loket);
            if ($update) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Success create untuk panggilan ' . $antrian
                ]);
            }
        }
    }
}
