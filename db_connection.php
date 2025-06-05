<?php
class Database {
    private $host = "localhost";
    private $username = "root"; // Ganti dengan username database Anda
    private $password = ""; // Ganti dengan password database Anda
    private $database = "portfolio"; // Ganti dengan nama database Anda
    private $conn;

    // Method untuk koneksi database
    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->database, 
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch(PDOException $e) {
            error_log("Connection Error: " . $e->getMessage());
            die("Database connection failed. Please try again later.");
        }

        return $this->conn;
    }

    // Method untuk menutup koneksi
    public function close() {
        $this->conn = null;
    }
}
?>