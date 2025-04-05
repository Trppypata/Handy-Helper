<?php
class SupportTicketController {
    private $db;
    private $supportTicket;

    public function __construct($db) {
        $this->db = $db;
        $this->supportTicket = new SupportTicket($db);
    }

    public function createTicket($data) {
        $this->supportTicket->ticket_id = uniqid();
        $this->supportTicket->user_id = $data['user_id'];
        $this->supportTicket->message = $data['message'];
        $this->supportTicket->status = 'open';

        if($this->supportTicket->create()) {
            return ["message" => "Support ticket created successfully"];
        }
        return ["message" => "Unable to create support ticket"];
    }

    public function updateTicketStatus($ticket_id, $status) {
        $this->supportTicket->ticket_id = $ticket_id;
        if($this->supportTicket->updateStatus($status)) {
            return ["message" => "Ticket status updated successfully"];
        }
        return ["message" => "Unable to update ticket status"];
    }

    public function getTickets() {
        $result = $this->supportTicket->getAll();
        return ["tickets" => $result->fetchAll(PDO::FETCH_ASSOC)];
    }
}
