<?php
class User {
    private $conn;
    private $table = 'users';

    public $user_id;
    public $name;
    public $email;
    public $phone_number;
    public $user_type;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                SET user_id = :user_id, 
                    name = :name, 
                    email = :email,
                    phone_number = :phone_number,
                    user_type = :user_type,
                    password = :password";

        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone_number = htmlspecialchars(strip_tags($this->phone_number));
        $this->user_type = htmlspecialchars(strip_tags($this->user_type));
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        // Bind data
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone_number', $this->phone_number);
        $stmt->bindParam(':user_type', $this->user_type);
        $stmt->bindParam(':password', $this->password);

        return $stmt->execute();
    }

    public function read($id = null) {
        $query = "SELECT * FROM " . $this->table;
        if($id) {
            $query .= " WHERE user_id = :id";
        }
        $stmt = $this->conn->prepare($query);
        if($id) {
            $stmt->bindParam(':id', $id);
        }
        $stmt->execute();
        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table . " 
                SET name = :name, 
                    email = :email,
                    phone_number = :phone_number,
                    user_type = :user_type
                WHERE user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        
        // Clean and bind data
        $stmt->bindParam(':name', htmlspecialchars(strip_tags($this->name)));
        $stmt->bindParam(':email', htmlspecialchars(strip_tags($this->email)));
        $stmt->bindParam(':phone_number', htmlspecialchars(strip_tags($this->phone_number)));
        $stmt->bindParam(':user_type', htmlspecialchars(strip_tags($this->user_type)));
        $stmt->bindParam(':user_id', htmlspecialchars(strip_tags($this->user_id)));

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        return $stmt->execute();
    }
}
?>
