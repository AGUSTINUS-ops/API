<?php
// koneksi ke database
include "database.php";
// mengambil metode request
$request_method = $_SERVER["REQUEST_METHOD"];
$produk = new Product();

if($request_method == 'GET') {
    if(isset($_GET['id_product'])) {
        $produk->get_products(null, 
            $_GET['id_product']);
    } else {
        $produk->get_products();
    }
} else if($request_method == 'POST') {
    $produk->add_product($_POST['ID'], 
        $_POST['nama']);
} else if($request_method == 'PUT') {
    parse_str(file_get_contents("php://input"),$post_vars);
    $produk->put_product($_GET['id_product'], $post_vars['nama'], $post_vars['stok'], 
    $post_vars['modal'], $post_vars['jual']);
} else if($request_method == 'DELETE') {
    $produk->del_product($_GET['id_product']);
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

    public function add_product($ID, $nama) {
        $query = "INSERT INTO product (ID, Nama) 
        VALUES ('$ID', '$nama')";
        if($this->conn->conn->query($query)) {
            $respon = array(
                'status' => 1,
                'status_message' => 'Produk berhasil 
                ditambahkan'
            );
        } else {
            $respon = array(
                'status' => 0,
                'status_message' => 'Produk gagal
                 ditambahkan'
            );
        }
        $this->conn->closeConnection();
        header('Content-Type: application/json');
        echo json_encode($respon);
    }

    public function put_product($ID, $nama, $stok, $modal, $jual) {
        $query = "UPDATE product SET nama = '$nama', stok=$stok, modal='$modal', jual='$jual'
                  WHERE ID = '$ID'";
        
        if($this->conn->conn->query($query)) {
            $respon = array(
                'status' => 1,
                'status_message' => 'Produk berhasil diubah'
            );
        } else {
            $respon = array(
                'status' => 0,
                'status_message' => 'Produk gagal diubah'
            );
        }
        $this->conn->closeConnection();
        header('Content-Type: application/json');
        echo json_encode($respon);
    }

    public function del_product($ID) {
        $query = "DELETE FROM product WHERE ID='$ID'";
        if($this->conn->conn->query($query)) {
            $respon = array(
                'status' => 1,
                'status_message' => 'Produk berhasil dihapus'
            );
        } else {
            $respon = array(
                'status' => 0,
                'status_message' => 'Produk gagal dihapus'
            );
        }
        $this->conn->closeConnection();
        header('Content-Type: application/json');
        echo json_encode($respon);
    }
}