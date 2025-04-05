<?php
class Service {
    private $conn;
    private $table = 'services';

    public $service_id;
    public $name;
    public $description;
    public $price;
    public $category;
    public $estimated_time;
    public $mechanic_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET 
                service_id = :service_id,
                name = :name,
                description = :description,
                price = :price,
                category = :category,
                estimated_time = :estimated_time,
                mechanic_id = :mechanic_id";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':service_id', $this->service_id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':estimated_time', $this->estimated_time);
        $stmt->bindParam(':mechanic_id', $this->mechanic_id);

        return $stmt->execute();
    }

    public function read($id = null) {
        $query = $id ? 
            "SELECT s.*, m.rating, m.availability_status, m.experience_years 
             FROM " . $this->table . " s
             LEFT JOIN mechanics m ON s.mechanic_id = m.mechanic_id
             WHERE s.service_id = ?" :
            "SELECT * FROM " . $this->table;
        
        $stmt = $this->conn->prepare($query);
        if($id) $stmt->execute([$id]);
        else $stmt->execute();
        return $stmt;
    }
}
