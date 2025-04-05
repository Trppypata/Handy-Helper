<?php
class BookingController {
    private $db;
    private $booking;

    public function __construct($db) {
        $this->db = $db;
        $this->booking = new Booking($db);
    }

    public function createBooking($data) {
        $this->booking->booking_id = uniqid();
        $this->booking->customer_id = $data['customer_id'];
        $this->booking->service_id = $data['service_id'];
        $this->booking->mechanic_id = $data['mechanic_id'];
        $this->booking->appointment_date = $data['appointment_date'];
        $this->booking->status = 'pending';

        if($this->booking->create()) {
            return ["message" => "Booking created successfully"];
        }
        return ["message" => "Unable to create booking"];
    }

    public function getBookings() {
        $result = $this->booking->getBookingDetails(null);
        return ["bookings" => $result->fetchAll(PDO::FETCH_ASSOC)];
    }

    public function getBooking($id) {
        $result = $this->booking->getBookingDetails($id);
        $booking = $result->fetch(PDO::FETCH_ASSOC);
        return $booking ? $booking : ["message" => "Booking not found"];
    }
}
