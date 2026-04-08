<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Mengatasi CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header('Access-Control-Allow-Methods: GET, POST');
header("Allow: GET, POST");
// pengecekan ajax request untuk mencegah direct access file, agar file tidak bisa diakses secara langsung dari browser
// panggil file "database.php" untuk koneksi ke database
require_once "../../config/query.php";
// jika ada ajax request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    if (isset($_POST['type'])) {
        $action = new config\query;

        if ($_POST['type'] == 'get_panggilan') {
            // sql statement untuk menampilkan jumlah data dari tabel "queue_antrian_admisi" berdasarkan "tanggal"
            $query = $action->getPanggilan();
            // ambil data hasil query
            // Inisialisasi array untuk menyimpan data
            $dataAntrian = array();

            // Ambil hasil query dan masukkan ke dalam array
            while ($row = mysqli_fetch_assoc($query)) {
                $dataAntrian[] = array(
                    'id' => $row['id'],
                    'antrian' => $row['antrian'],
                    'loket' => $row['loket']
                );
            }

            echo json_encode([
                'success' => true,
                'message' => 'Success',
                'data' => $dataAntrian
            ]);
        }

        if ($_POST['type'] == 'delete_panggilan') {
            $id = $_POST['id'];
            $query = $action->deletePanggilan($id);

            if ($query) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Delete Success on id ' . $id
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error'
                ]);
            }
        }
    }
}
