<?php
class Company {
    private $conn;
    private $table = 'companies';

    public $company_id;
    public $name;
    public $description;
    public $location_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET 
                company_id = :company_id,
                name = :name,
                description = :description,
                location_id = :location_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':company_id', $this->company_id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':location_id', $this->location_id);

        return $stmt->execute();
    }
}
