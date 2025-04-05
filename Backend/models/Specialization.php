<?php
class Specialization {
    private $conn;
    private $table = 'specializations';

    public $specialization_id;
    public $name;
    public $description;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET 
                specialization_id = :specialization_id,
                name = :name,
                description = :description";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':specialization_id', $this->specialization_id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);

        return $stmt->execute();
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
