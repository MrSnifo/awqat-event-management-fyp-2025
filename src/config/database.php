<?php
function getDatabaseConnection(){
    $servername = "localhost";
    $username = "root";
    $password = ""; 
    $database = "ouquat";

        $connection = mysqli_connect($servername ,$username , $password, $database);

        if ($connection->connect_error){
            die("Error failed to connect to MySQL".$connection->connect_error);
        }
        return $connection;
    }
?>
=======
class Database {
    private const DB_HOST = '127.0.0.1';
    private const DB_NAME = 'ouqat';
    private const DB_USER = 'root';
    private const DB_PASS = '';
    private const DB_CHARSET = 'utf8mb4';
    
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME . ";charset=" . self::DB_CHARSET;
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_PERSISTENT         => false
            ];
            
            $this->connection = new PDO($dsn, self::DB_USER, self::DB_PASS, $options);
            
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());            
            throw new Exception("Database connection error. Please try again later.");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->connection;
    }

    private function __clone() {}
    public function __wakeup() {
        throw new Exception("Cannot unserialize database connection");
    }
}
>>>>>>> 38d8d3b5b18ae33c9c7362932ea9fec4a3cd6eb8
