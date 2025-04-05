<?php
class Appointment {
    private $conn;
    private $table = 'appointments';

    public $appointment_id;
    public $user_id;
    public $mechanic_id;
    public $schedule_id;
    public $service_id;
    public $location_id;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET 
                appointment_id = :appointment_id,
                user_id = :user_id,
                mechanic_id = :mechanic_id,
                schedule_id = :schedule_id,
                service_id = :service_id,
                location_id = :location_id,
                status = :status";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':appointment_id', $this->appointment_id);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':mechanic_id', $this->mechanic_id);
        $stmt->bindParam(':schedule_id', $this->schedule_id);
        $stmt->bindParam(':service_id', $this->service_id);
        $stmt->bindParam(':location_id', $this->location_id);
        $stmt->bindParam(':status', $this->status);

        return $stmt->execute();
    }

    public function getAppointmentDetails($id) {
        $query = "SELECT a.*, u.name as customer_name, m.rating, s.name as service_name, 
                 l.address FROM " . $this->table . " a
                 LEFT JOIN users u ON a.user_id = u.user_id
                 LEFT JOIN mechanics m ON a.mechanic_id = m.mechanic_id
                 LEFT JOIN services s ON a.service_id = s.service_id
                 LEFT JOIN locations l ON a.location_id = l.location_id
                 WHERE a.appointment_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt;
    }
}
