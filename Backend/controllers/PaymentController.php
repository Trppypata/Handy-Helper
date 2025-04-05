<?php
class PaymentController {
    private $db;
    private $payment;

    public function __construct($db) {
        $this->db = $db;
        $this->payment = new Payment($db);
    }

    public function processPayment($data) {
        $this->payment->payment_id = uniqid();
        $this->payment->booking_id = $data['booking_id'];
        $this->payment->amount = $data['amount'];
        $this->payment->payment_status = 'pending';
        $this->payment->transaction_date = date('Y-m-d');

        if($this->payment->create()) {
            return ["message" => "Payment processed successfully"];
        }
        return ["message" => "Unable to process payment"];
    }

    public function getPaymentHistory($booking_id) {
        $result = $this->payment->getPaymentHistory($booking_id);
        return ["payments" => $result->fetchAll(PDO::FETCH_ASSOC)];
    }
}
