<?php
class Booking {
    private $conn;
    private $table = 'bookings';

    public $booking_id;
    public $customer_id;
    public $service_id;
    public $mechanic_id;
    public $appointment_date;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET 
                booking_id = :booking_id,
                customer_id = :customer_id,
                service_id = :service_id,
                mechanic_id = :mechanic_id,
                appointment_date = :appointment_date,
                status = :status";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':booking_id', $this->booking_id);
        $stmt->bindParam(':customer_id', $this->customer_id);
        $stmt->bindParam(':service_id', $this->service_id);
        $stmt->bindParam(':mechanic_id', $this->mechanic_id);
        $stmt->bindParam(':appointment_date', $this->appointment_date);
        $stmt->bindParam(':status', $this->status);

        return $stmt->execute();
    }

    public function getBookingDetails($id) {
        $query = "SELECT b.*, s.name as service_name, u.name as customer_name, 
                 m.rating as mechanic_rating
                 FROM " . $this->table . " b
                 LEFT JOIN services s ON b.service_id = s.service_id
                 LEFT JOIN users u ON b.customer_id = u.user_id
                 LEFT JOIN mechanics m ON b.mechanic_id = m.mechanic_id
                 WHERE b.booking_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt;
    }
}
