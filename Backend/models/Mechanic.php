<?php
class Mechanic {
    private $conn;
    private $table = 'mechanics';

    public $mechanic_id;
    public $user_id;
    public $company_id;
    public $experience_years;
    public $availability_status;
    public $rating;
    public $specialization_id;
    public $location_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET 
                mechanic_id = :mechanic_id,
                user_id = :user_id,
                company_id = :company_id,
                experience_years = :experience_years,
                availability_status = :availability_status,
                rating = :rating,
                specialization_id = :specialization_id,
                location_id = :location_id";

        $stmt = $this->conn->prepare($query);
        
        // Clean and bind data
        $stmt->bindParam(':mechanic_id', htmlspecialchars(strip_tags($this->mechanic_id)));
        $stmt->bindParam(':user_id', htmlspecialchars(strip_tags($this->user_id)));
        $stmt->bindParam(':company_id', htmlspecialchars(strip_tags($this->company_id)));
        $stmt->bindParam(':experience_years', $this->experience_years);
        $stmt->bindParam(':availability_status', htmlspecialchars(strip_tags($this->availability_status)));
        $stmt->bindParam(':rating', $this->rating);
        $stmt->bindParam(':specialization_id', htmlspecialchars(strip_tags($this->specialization_id)));
        $stmt->bindParam(':location_id', htmlspecialchars(strip_tags($this->location_id)));

        return $stmt->execute();
    }

    public function getAvailableMechanics($service_id) {
        $query = "SELECT m.*, u.name, u.email, u.phone_number, 
                 s.name as specialization, l.address, c.name as company_name
                 FROM " . $this->table . " m
                 LEFT JOIN users u ON m.user_id = u.user_id
                 LEFT JOIN specializations s ON m.specialization_id = s.specialization_id
                 LEFT JOIN locations l ON m.location_id = l.location_id
                 LEFT JOIN companies c ON m.company_id = c.company_id
                 WHERE m.availability_status = 'available'
                 AND m.mechanic_id IN (
                     SELECT mechanic_id FROM services WHERE service_id = :service_id
                 )";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':service_id', $service_id);
        $stmt->execute();
        return $stmt;
    }
}
