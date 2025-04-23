<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'awqat';
    private $username = 'root';
    private $password = '';
    public $conn;
    
    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            error_log("Database connection error: " . $exception->getMessage());
            die("Database connection error. Please try again later.");
        }
        
        return $this->conn;
    }
}
?>