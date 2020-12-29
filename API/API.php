<?php
// membangun koneksi ke database
class Database {
    // atribut
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    function __construct() {
        $this->host = "localhost";
        $this->username = "root";
        $this->password = "";
        $this->db_name = "esayurmayur";
    }
    function getConnection() {
        $this->conn = null;
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

        return $this->conn;
    }
    function closeConnection() {
        $conn->close();
    }
}
