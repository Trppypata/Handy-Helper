<?php
// Database connection
class DatabaseConnection {

    private $host = "localhost";  
    private $user = "root";       
    private $password = "";       
    private $dbname = "handyhelper"; 
    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        } else {
            echo "✅ Connection successful to the database: " . $this->dbname . "<br>";
        }
    }

    public function getConnection() {
        return $this->conn;
    }

  
    public function closeConnection() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>
