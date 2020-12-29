<?php
// koneksi ke database
include "database.php";
// mengambil metode request
$request_method = $_SERVER["REQUEST_METHOD"];

if($request_method == 'GET') {
    $produk = new Product();
    if(isset($_GET['id_product'])) {
        $produk->get_products(null, 
            $_GET['id_product']);
    } else {
        $produk->get_products();
    }
}

// deklarasi kelas produk
class Product {
    // atribut
    private $ID;
    private $img;
    private $nama;
    private $stok;
    private $modal;
    private $jual;
    public $conn;

    function __construct() {
        $this->ID = "";
        $this->nama = "";
        $this->stok = 0;
        $this->modal = 0;
        $this->jual = 0;
        $this->conn = new Database();
        $this->conn->getConnection();
    }

    public function get_products($kategori = null, $id_produk = null) {
        $query = "";
        if($kategori <> null) {
            // GET BY ID or BY kategori
            // http:/// ..... /api/product/sayur
            // http:// ...../api/product/1
            $query = "SELECT * FROM product 
                WHERE kategory = '$kategori' 
                    AND ID = '$id_produk'";
        } if ($id_produk <> null) {
            $query = "SELECT * FROM product 
                WHERE ID = '$id_produk'";
         } else {
            // GET ALL
            // .../api/produk.php
            $query = "SELECT * FROM product";
        }
        $result = $this->conn->conn->query($query);
        $respon = array();
    
        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $respon[] = $row;
        }
        // menutup koneksi dari database
        $this->conn->closeConnection();
        // // respon ke client dalam format JSON
         header('Content-Type: application/json');
         echo json_encode($respon);
    }
}