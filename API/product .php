<?php
// koneksi ke database
include "database.php";


// mengambil metode request
$request_method = $_SERVER["REQUEST_METHOD"];

// metode yg digunakan client?
if($request_method == "GET") {
    $conn = new Database();
    $conn->getConnection();
    if($conn) {
        echo "berhasil";
    }
    // ambil data
    // berdasarkan kategori
    if(!empty($_GET["kategori"])) {
        $kategori = $_GET["kategori"];
        get_product($kategori);
    } else if(!empty($_GET["id_produk"])) {
        $kategori = $_GET["kategori"];
        $id_produk = $_GET["id_produk"];
        get_product($kategori, $id_produk);
    } else {
        get_products($conn);
    }

}

function get_products($conn, $kategori = null, $id_produk = 0) {
    // if ($conn->conn->connect_errno) {
    //     printf("Connect failed: %s\n", $mysqli->connect_error);
    //     exit();
    // }

    // ambil semua produk
    $query = "SELECT * FROM product";
    $result = $conn->conn->query($query);
    $respon = array();

    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $respon[] = $row;
    }

    // // menutup koneksi dari database
    //$conn->closeConnection();

    // // respon ke client dalam format JSON
     header('Content-Type: application/json');
     echo json_encode($respon);
}