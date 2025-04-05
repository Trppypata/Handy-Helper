<?php
class AppointmentController {
    private $db;
    private $appointment;

    public function __construct($db) {
        $this->db = $db;
        $this->appointment = new Appointment($db);
    }

    public function createAppointment($data) {
        $this->appointment->appointment_id = uniqid();
        $this->appointment->user_id = $data['user_id'];
        $this->appointment->mechanic_id = $data['mechanic_id'];
        $this->appointment->schedule_id = $data['schedule_id'];
        $this->appointment->service_id = $data['service_id'];
        $this->appointment->location_id = $data['location_id'];
        $this->appointment->status = 'pending';

        if($this->appointment->create()) {
            return ["message" => "Appointment created successfully"];
        }
        return ["message" => "Unable to create appointment"];
    }
}
