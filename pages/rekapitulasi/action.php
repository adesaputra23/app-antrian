<?php
// panggil file "database.php" untuk koneksi ke database
require_once "../../config/query.php";
// pengecekan ajax request untuk mencegah direct access file, agar file tidak bisa diakses secara langsung dari browser
// jika ada ajax request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    if (isset($_POST['type'])) {
        $action = new config\query;

        if ($_POST['type'] == 'list_antrian') {
            $query = $action->getListAntrianByType($_POST['type_antrian'], $_POST['start'], $_POST['end']);
            // ambil jumlah baris data hasil query
            $rows = mysqli_num_rows($query);
            $dataAntrian = array();

            if ($rows <> 0) {
                // ambil data hasil query
                while ($row = mysqli_fetch_assoc($query)) {
                    $data['id']         = $row["id"];
                    $data['no_antrian'] = $row["code_antrian"] . $row["no_antrian"];
                    $data['status']     = $row["status"];

                    array_push($dataAntrian, $data);
                }

                echo json_encode([
                    'success' => true,
                    'message' => 'Success',
                    'data' => $dataAntrian
                ]);
            } else {
                echo json_encode([
                    'success' => true,
                    'message' => 'Success',
                    'data' => []
                ]);
            }
        }
    }
}
