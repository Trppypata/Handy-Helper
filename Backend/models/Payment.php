<?php
class Payment {
    private $conn;
    private $table = 'payments';

    public $payment_id;
    public $booking_id;
    public $amount;
    public $payment_status;
    public $transaction_date;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET 
                payment_id = :payment_id,
                booking_id = :booking_id,
                amount = :amount,
                payment_status = :payment_status,
                transaction_date = :transaction_date";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':payment_id', $this->payment_id);
        $stmt->bindParam(':booking_id', $this->booking_id);
        $stmt->bindParam(':amount', $this->amount);
        $stmt->bindParam(':payment_status', $this->payment_status);
        $stmt->bindParam(':transaction_date', $this->transaction_date);

        return $stmt->execute();
    }

    public function getPaymentHistory($booking_id) {
        $query = "SELECT p.*, b.appointment_date, s.name as service_name 
                 FROM " . $this->table . " p
                 LEFT JOIN bookings b ON p.booking_id = b.booking_id
                 LEFT JOIN services s ON b.service_id = s.service_id
                 WHERE p.booking_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$booking_id]);
        return $stmt;
    }
}
