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
// jika ada ajax request
if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET')) {
    if (isset($_POST)) {
        $payload = json_decode(file_get_contents("php://input"), true);

        if (isset($payload['type'])) {
            if ($payload['type'] == 'test_koneksi_server') {
                echo json_encode([
                    'success' => true,
                    'message' => 'Success',
                    'data' => 'Server connected!'
                ]);
            }
        }
    }
}
