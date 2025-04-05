<?php
class SupportTicket {
    private $conn;
    private $table = 'support_tickets';

    public $ticket_id;
    public $user_id;
    public $message;
    public $status;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " SET 
                ticket_id = :ticket_id,
                user_id = :user_id,
                message = :message,
                status = :status";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ticket_id', $this->ticket_id);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':message', $this->message);
        $stmt->bindParam(':status', $this->status);

        return $stmt->execute();
    }

    public function updateStatus($status) {
        $query = "UPDATE " . $this->table . " 
                SET status = :status 
                WHERE ticket_id = :ticket_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':ticket_id', $this->ticket_id);
        
        return $stmt->execute();
    }

    public function getAll() {
        $query = "SELECT t.*, u.name as user_name 
                 FROM " . $this->table . " t
                 LEFT JOIN users u ON t.user_id = u.user_id
                 ORDER BY t.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
